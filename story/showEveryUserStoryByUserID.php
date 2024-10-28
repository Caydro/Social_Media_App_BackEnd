<?php

include("../../collagiey/connect.php");

function showMainStories($conn, $userid) {
    // Get the user ID from the POST request (ensure the user_id is correctly passed in the POST request)
    if(isset($_POST[$userid])) {
        $userID = $_POST[$userid];

        // Prepare the SQL statement to get stories for the user ordered by creation time (most recent first)
        $getAllYourStories = $conn->prepare("SELECT * FROM `stories` WHERE `user_id`=:userID ORDER BY `created_at` DESC");
        
        // Execute the statement and pass the user ID
        $getAllYourStories->execute([":userID" => $userID]);
        
        // Fetch all stories as associative array
        $data = $getAllYourStories->fetchAll(PDO::FETCH_ASSOC);

        // Check if there are any stories returned
        $check = $getAllYourStories->rowCount();
        if ($check > 0) {
            // Send the stories data in JSON format
        
            echo json_encode([
                "data" => $data
            ]);
        } else {
            // If no stories found, return an error message
            echo json_encode([
                "error" => "Invalid user or no stories available"
            ]);
        }
    } else {
        // If user ID is not set in POST request, return an error message
        echo json_encode([
            "error" => "User ID not provided"
        ]);
    }
}

// Correctly pass the 'user_id' to the function
showMainStories($conn, "user_id");

?>