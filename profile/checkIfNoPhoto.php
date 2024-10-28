<?php

include("../../collagiey/connect.php");

function checkPhoto($conn, $user_id){
$userID = $_POST[$user_id];
    $checkNoPhoto = $conn->prepare("SELECT `circle_background` FROM `users` WHERE `user_id`=:userID ");

$checkNoPhoto->execute(
    [
        ":userID"=>$userID,
    ]
);
$photo = $checkNoPhoto->fetchAll(PDO::FETCH_ASSOC);

if($photo[0]['circle_background'] == "noProfile.jpeg"){
    echo json_encode(
        [
            "noPhoto"=>$photo[0]['circle_background']
        ]
    );
}else{
    echo json_encode(
        [
            "photo"=>$photo[0]['circle_background']
        ]
    );
}






}




checkPhoto($conn, "user_id");




?>