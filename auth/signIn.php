<?php
include("../connect.php");

function loginMethod($email, $password, $conn)
{
    // Validate and sanitize email and password
    $email = filter_input(INPUT_POST, $email, FILTER_VALIDATE_EMAIL);
    $password = htmlspecialchars(strip_tags($_POST[$password]));

    if (empty($email)) {
        http_response_code(401);
        echo json_encode(["error" => "Invalid email"]);
        return;
    } elseif (empty($password)) {
        http_response_code(401);
        echo json_encode(["error" => "Password is required"]);
        return;
    }

    try {
        // Retrieve the user's ID, name, and hashed password from the database based on the email
        $stmt = $conn->prepare("SELECT `user_id`, `user_name`, `password` FROM `users` WHERE `email` = :email");
        $stmt->execute(['email' => $email]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row && password_verify($password, $row['password'])) {
            // Generate a token
            $token = generateToken();

            // Check if user is already logged in
            $check = $conn->prepare("SELECT `user_id` FROM `token_table` WHERE `user_id` = :user_id");
            $check->execute(['user_id' => $row['user_id']]);
            $result = $check->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                http_response_code(403);
                echo json_encode(['error' => 'User already logged in']);
                return;
            }

            // Insert token into token_table
            $stmt = $conn->prepare('INSERT INTO `token_table` (`user_id`, `token`) VALUES (:user_id, :token)');
            $stmt->execute(['user_id' => $row['user_id'], 'token' => $token]);

            // Return success response with user details and token
            http_response_code(200);
            echo json_encode([
                "success" => "Login Successful",
                "user_id" => $row['user_id'],
                "user_name" => $row['user_name'],
                "token" => $token
            ]);
        } else {
            http_response_code(401);
            echo json_encode(["error" => "Wrong Username or Password"]);
        }
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(["error" => "An unexpected error occurred"]);
        // Log the error for debugging purposes
        // error_log($e->getMessage());
    }
}

// Function to generate a random token
function generateToken() {
    return bin2hex(random_bytes(32)); // Generate a 64-character hexadecimal token
}

loginMethod('email', 'password', $conn);
?>