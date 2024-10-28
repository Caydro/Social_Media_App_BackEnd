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
if($checkForExistRequest > 0){
    echo json_encode(
        [
            "already"=>"there's a request has those 2 ID",
        ]
    );
}else{
    echo json_encode(
        [
            "noRequest"=>"There's no request has those 2 ID",
        ]
    );
}


}

addFriendRequest($conn,"sender","receiver");













?>