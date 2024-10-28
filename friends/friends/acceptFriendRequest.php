<?php

include("../../../collagiey/connect.php");

function acceptFriendRequest($requestID, $conn){
    $request_id = $_POST[$requestID];
    $getTheRequest = $conn->prepare("SELECT * FROM `friend_request` WHERE `request_id` =:requestID");
    $getTheRequest->execute([
        "requestID"=>$request_id,
    ]);
   $request =  $getTheRequest->fetchAll();

 $check = $getTheRequest->rowCount();

 if($check > 0){
    echo json_encode([
        "friend_request"=>$request[0]["sender"],
       ]);
 }else{
    echo json_encode([
        "error"=>"Somthing went wrong there's no request like this",
       ]);
 }

}





acceptFriendRequest("request_id",$conn);




















?>