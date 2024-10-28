<?php

include("../../../collagiey/connect.php");



function addFriendRequest($conn, $senderID,$receiverID){
$sender = $_POST[$senderID];
$receiver = $_POST[$receiverID];

$makeSureThereIsNoRequest = $conn->prepare("SELECT * FROM `friend_request` WHERE `sender` = :sender AND `receiver` = :receiver");
$makeSureThereIsNoRequest->execute(
    [":sender"=>$sender,":receiver"=>$receiver]
);
$checkForExistRequest = $makeSureThereIsNoRequest->rowCount();
if($checkForExistRequest == 0){
    $makeAFriendRequest = $conn->prepare("INSERT INTO `friend_request` (`sender`, `receiver`) VALUES (:sender, :receiver)");
    $makeAFriendRequest->execute(
        [":sender"=>$sender,":receiver"=>$receiver]
    );
    $check = $makeAFriendRequest->rowCount();
    
    if($check > 0 ){
        echo json_encode([
                "success"=> "Friend request has been successfully sent",
        ]);
    }else {
        echo json_encode(
            [
                "error"=>"Something went wrong sending friend request",
            ]
        );
    }
}else{
    echo json_encode(
        [
            "error"=>"request Already sent",
        ]
    );
}


}

addFriendRequest($conn,"sender","receiver");













?>