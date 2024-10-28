<?php

include("../../collagiey/connect.php");
function deleteCircleBackground($conn,$user_id){
    $userID = $_POST[$user_id];
    $selectHisBackgroundName = $conn->prepare("SELECT `circle_background` FROM `users` WHERE `user_id`=:userID");
    $selectHisBackgroundName->execute([
        "userID"=>$userID,
    ],
);
$photoName = $selectHisBackgroundName->fetchAll(PDO::FETCH_ASSOC);

$deleteCircleBackground = $conn->prepare("UPDATE `users` SET `circle_background` = :noProfilePhoto WHERE `user_id` = :userID");

$deleteCircleBackground->execute(
    [
    ":userID"=>$userID,
    ":noProfilePhoto"=>"noProfile.jpeg",
]
);

$imagePath = $_SERVER['DOCUMENT_ROOT'] .'/collagiey/profile/circleBackground/'.$photoName[0]["circle_background"];

// Check if the file exists before deleting
if (file_exists($imagePath)) {
    unlink($imagePath);
    echo json_encode([
        "success"=>"File deleted!"
    ]);
} else {
    echo json_encode([
        "error"=>"this user has no profile circle background"
    ]);
}

}

deleteCircleBackground($conn, "user_id");

?>