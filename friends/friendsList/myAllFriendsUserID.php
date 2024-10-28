<?php

include("../../../collagiey/connect.php");

function getAllMyFriendsID($conn,$user_id){
    $userID = $_POST[$user_id];
$getAllFriends = $conn->prepare("SELECT * FROM `friends` WHERE `user_id`=:userID");
$getAllFriends->execute([
    ":userID"=>$userID,
]);
$allMyUsers = $getAllFriends->fetchAll();
$check = $getAllFriends->rowCount();

if($check > 0){
    
echo json_encode(
    [
        "data"=>$allMyUsers,
    ]
);
}else{
    echo json_encode([
        "error"=>"There's no friends try to add some",
    ]);
}
}

getAllMyFriendsID($conn,"user_id");


?>