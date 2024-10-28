<?php

include("../../collagiey/connect.php");

function addStoryByUserID($conn,$userID,$storyContent,$backgroundColor,$content_Type){
$userid = $_POST[$userID];
$storyTextContent = $_POST[$storyContent];
$contentType = $_POST[$content_Type];
$backgroundCOLOR = $_POST[$backgroundColor];
$getUserCircleImage = $conn->prepare("SELECT * FROM `users` WHERE `user_id` =:userID ");
$getUserCircleImage->execute([":userID"=>$userid]);

$userData = $getUserCircleImage->fetchAll(PDO::FETCH_ASSOC);
$circlePhoto = $userData[0]['circle_background'];
$userName = $userData[0]['user_name'];

 // Insert story into the `stories` table
$addStory = $conn->prepare("INSERT INTO `stories` (`user_id`, `circlePhoto`, `content`,`user_name`, `background_color`,`content_type`) VALUES 
(:userID, :circlePhoto, :contentText,:userName,:backgroundColor,:contentType)");
$addStory->execute([
    ":userID" => $userid, ":circlePhoto" => $circlePhoto,
    ":contentText"=>$storyTextContent, ":userName"=>$userName,
    ":backgroundColor"=>$backgroundCOLOR,":contentType"=>$contentType,
]);

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
addStoryByUserID($conn, "user_id","story_text_content","background_color","content_type");
