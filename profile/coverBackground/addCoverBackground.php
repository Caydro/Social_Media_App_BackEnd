<?php

include("../../../collagiey/connect.php");
include("../coverBackground/coverBackgroundFunction.php");

function addNewCoverPhoto($conn, $user_id) {
    // Use a safe way to retrieve POST data
    $userID = isset($_POST[$user_id]) ? $_POST[$user_id] : null;
    
    // Ensure user ID is valid
    if (empty($userID)) {
        echo json_encode([
            "error" => "Invalid user ID"
        ]);
        return;
    }

    $imageName = uploadImageFunc('c_image');

    // Check if image upload was successful
    if ($imageName === false) {
        echo json_encode([
            "error" => "Failed to upload image"
        ]);
        return;
    }

    // Prepare and execute the query
    $changePhotoQuery = $conn->prepare("UPDATE `users` SET `cover_background` = :image WHERE `user_id` = :userID");
    $changePhotoQuery->execute([
        ":userID" => $userID,
        ":image" => $imageName,
    ]);

    // Check if the update was successful
    $check = $changePhotoQuery->rowCount();

    if ($check > 0) {
        echo json_encode([
            "success" => "Cover photo successfully updated"
        ]);
    } else {
        echo json_encode([
            "error" => "Failed to add your cover; something went wrong",
        ]);
    }
}

addNewCoverPhoto($conn, "user_id");
?>
