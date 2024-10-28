<?php


include("../../../collagiey/connect.php");


function showMyFriendRequests($conn, $userID){

$myUserID = $_POST[$userID];


$getMyRequests = $conn->prepare("SELECT * FROM `friend_request` WHERE `receiver` = :userID");

$getMyRequests->execute([
    ":userID"=>$myUserID,
]);

$checking = $getMyRequests->rowCount();


if($checking > 0){
$data =   $getMyRequests->fetchAll();
    echo json_encode(
        [
            "data"=>$data,
        ]
    );
}else{
    echo json_encode([
        "error"=>"There's no friend requests",
    ]);
}


}

showMyFriendRequests($conn, "receiver");


?>