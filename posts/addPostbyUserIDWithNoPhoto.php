<?php

include("../../collagiey/connect.php");

function addUserByID($conn,$user_id,$postString,$postPrivacy,$postStatus){

    $userID = $_POST[$user_id];
    $post_string =$_POST[$postString];
    $post_privacy = $_POST[$postPrivacy];
    $post_status = $_POST[$postStatus];
    $checkUser = $conn->prepare("SELECT `user_name`, `circle_background` FROM `users` WHERE `user_id`=:userID");
    $checkUser->execute(
        [":userID"=>$userID]
    );
$data= $checkUser->fetchAll(PDO::FETCH_ASSOC);
    $user_name = $data[0]['user_name'];
    $circleBackground = $data[0]['circle_background'];
    $timeNow = date('Y-m-d H:i:s');
    $addPost = $conn->prepare("INSERT INTO `posts` (`nameOfUser`,`circlePhoto`,`post_privacy`,`post_string`,`userID`,`post_status`,`time`)
    VALUES (:nameOfUser, :circlePhoto, :post_privacy,:post_string,:userID,:postStatus,:time)");
    $addPost->execute([":nameOfUser"=>$user_name, ":circlePhoto"=>$circleBackground,":post_privacy"=>$post_privacy,
        ":post_string"=>$post_string,"userID"=>$userID,"postStatus"=>$post_status, ":time"=>$timeNow]);
    $check = $addPost->rowCount();
    if($check>0){
echo json_encode([
    "success"=>"Post successfully added"
]);
    }else{
        echo json_encode([
            "error"=>"Something went wrong"
        ]);
    }
}



addUserByID($conn,"user_id", "post_string","post_privacy","post_status");




