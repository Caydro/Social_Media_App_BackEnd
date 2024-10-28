<?php

include("../../collagiey/connect.php");
include("../story/storyDownloadFunction.php");


function addStoryByUserID($conn,$userID,$photoOfThePost, $backgroundColor,$content_Type){
$userid = $_POST[$userID];
$contentType = $_POST[$content_Type];
$photoOfThePostName = uploadImageFunc($photoOfThePost);
$getUserCircleImage = $conn->prepare("SELECT * FROM `users` WHERE `user_id` =:userID ");
$getUserCircleImage->execute([":userID"=>$userid]);

$userData = $getUserCircleImage->fetchAll(PDO::FETCH_ASSOC);
$circlePhoto = $userData[0]['circle_background'];
$userName = $userData[0]['user_name'];

 // Insert story into the `stories` table
$addStory = $conn->prepare("INSERT INTO `stories` (`user_id`, `circlePhoto`, `content`,`user_name`, `content_type`) VALUES 
(:userID, :circlePhoto, :contentText,:userName,:contentType)");
$addStory->execute(
    [
    ":userID" => $userid, ":circlePhoto" => $circlePhoto,
    ":contentText"=>$photoOfThePostName, ":userName"=>$userName,
    ":contentType"=>$contentType,
]
);
$checking = $addStory->rowCount();

if($checking > 0){
    echo json_encode([
        "success"=>"Story added successfully"
    ]);
}else{
    echo json_encode(
    [ "error"=>"Something went wrong with adding this story",]
    );
}

}
addStoryByUserID($conn, "user_id","c_image","background_color","content_type");



























?>