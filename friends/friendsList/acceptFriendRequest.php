<?php

include("../../../collagiey/connect.php");

function acceptFriendRequest($requestID, $conn){
    $request_id = $_POST[$requestID];
    $getTheRequest = $conn->prepare("SELECT * FROM `friend_request` WHERE `request_id` =:requestID");
    $getTheRequest->execute([
        ":requestID"=>$request_id,
    ]);
$request =  $getTheRequest->fetchAll();

$check = $getTheRequest->rowCount();

if($check > 0){
    
    $acceptTheRequest = $conn->prepare("INSERT INTO `friends` (`user_id`,`otherFriend_id`) VALUES (:userID,:otherFriendID)");
    $acceptTheRequest->execute([
        ":userID"=>$request[0]["receiver"], 
        ":otherFriendID"=>$request[0]["sender"],
    ]);
    $getTheRequest = $conn->prepare("DELETE FROM `friend_request` WHERE `request_id` =:requestID");
    $getTheRequest->execute([
        ":requestID"=>$request_id,
    ]);
    echo json_encode([
        "Success"=>"Friend added successfully"
    ]);
}else{
    echo json_encode([
        "error"=>"Somthing went wrong there's no request like this",
    ]);
}

}

acceptFriendRequest("request_id",$conn);




















?>