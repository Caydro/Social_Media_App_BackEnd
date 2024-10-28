<?php
// Include the file that contains the database connection
include '../connect.php'; 

// Include the file that contains the function 'uploadImageFunc' 
include '../profile/circleBackGroundAndBirthDayAddFunction.php';

// Define a function to upload the birthday and image for a user
function uploadItemsType($conn, $birthday, $user_id) {
    // Retrieve the birthday and user ID from the POST request
    $birthDay = $_POST[$birthday];
    $userID = $_POST[$user_id];

    // Call the function to upload the image and store the resulting file name
    $imageName = uploadImageFunc('c_image');

    // Check if the request method is POST
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        // Check if the image upload was successful
        if ($imageName !== "Failed") {
            // Prepare the SQL statement to update the user's record
            $dat = $conn->prepare("UPDATE `users` SET `birth_day` = :birthDay, `circle_background` = :image WHERE `user_id` = :userID");
            $dat->bindParam(':birthDay', $birthDay);
            $dat->bindParam(':image', $imageName);
            $dat->bindParam(':userID', $userID);
            $dat->execute();

            // Check if the update was successful
            $count = $dat->rowCount();
            if ($count > 0) {
                // Prepare a new SQL statement to retrieve the updated 'circle_background' field
                $dat = $conn->prepare("SELECT `circle_background` FROM `users` WHERE `user_id` = :userID");
                $dat->bindParam(':userID', $userID);
                $dat->execute();

                // Fetch the result and store it in the $userImage variable
                $userImage = $dat->fetchAll(PDO::FETCH_ASSOC);

                // Respond with a success message and the updated image data
                echo json_encode(array("success" => "Image uploaded successfully.", "image" => $userImage));
            } else {
                // Respond with an error message if the update failed
                echo json_encode(array("error" => "Failed to insert image details into the database."));
            }
        } else {
            // Respond with an error message if the image upload failed
            echo json_encode(array("error" => "Failed to upload image."));
        }
    } else {
        // Respond with an error message if the request method is not POST
        echo json_encode(array("error" => "Invalid request method."));
    }
}

// Call the function with the database connection and expected POST parameters
uploadItemsType($conn, "birthDay", "userID");
?>