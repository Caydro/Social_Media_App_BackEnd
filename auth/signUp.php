<?php


include("../connect.php");



function startDB($post, $dataDocuments, $image,$posi,$conn)
{
    if (isset($_POST[$post])) {
        // Validate and sanitize email
        $email = filter_input(INPUT_POST, $post, FILTER_VALIDATE_EMAIL);
        // Sanitize name and password
        $name = htmlspecialchars(strip_tags($_POST[$dataDocuments]));
        $password = htmlspecialchars(strip_tags($_POST[$image]));
        $position = htmlspecialchars(strip_tags($_POST[$posi]));

        if (empty($email)) {
            http_response_code(400); // Bad Request
            echo json_encode(["error" => "Invalid email"]);
            return;
        } elseif (empty($name)) {
            http_response_code(400); // Bad Request
            echo json_encode(["error" => "Name is required"]);
            return;
        } elseif (empty($password)) {
            http_response_code(400); // Bad Request
            echo json_encode(["error" => "Password is required"]);
            return;
        }

        // Password validation
        if (strlen($password) < 8) {
            http_response_code(400); // Bad Request
            echo json_encode(["error" => "Password must be at least 8 characters long"]);
            return;
        }

        // Check if password contains at least one special character using regular expression
        if (!preg_match('/[!@#$%^&*(),.?":{}|<>]/', $password)) {
            http_response_code(400); // Bad Request
            echo json_encode(["error" => "Password must contain at least one special character"]);
            return;
        }

        if (strlen($name) < 3) {
            http_response_code(400); // Bad Request
            echo json_encode(["error" => "Name must be at least 3 characters long"]);
            return;
        }

        // Check if email already exists in the database
        $stmt = $conn->prepare("SELECT `email` FROM `users` WHERE `email` = :email ");
        $stmt->execute(['email' => $email]);
        $rowCount = $stmt->rowCount();

        if ($rowCount > 0) {
            http_response_code(409); // Conflict
            echo json_encode(["error" => "Email Already Exists"]);
            return;
        }

        // Password Hashing
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // If everything is valid, proceed with database insertion
        $stmt = $conn->prepare("INSERT INTO `users` (`email`, `user_name`, `password`, `position`) VALUES ( :email , :name, :password, :position)");
        $stmt->execute(['email'=> $email , 'name' => $name, 'password' => $hashedPassword, 'position'=>$position]);
        http_response_code(200); // Success
        $stmt = $conn->prepare("SELECT `user_id` FROM `users` WHERE `email`=:email");
        $stmt->execute(['email'=> $email]);
       $userID= $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(["message" => "User Successfully Created", "userID"=>$userID[0]["user_id"]]);
    }
}

startDB('email', 'user_name', 'password','position', $conn);

?>