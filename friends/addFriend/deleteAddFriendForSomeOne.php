<?php

include("../../../collagiey/connect.php");



function deleteFriendRequest($conn, $request_id){
$requestID = $_POST[$request_id];



$deleteAFriendRequest = $conn->prepare("DELETE FROM `friend_request` WHERE `request_id`=:requestID");
$deleteAFriendRequest->execute(
    [":requestID"=>$requestID],
);
$check = $deleteAFriendRequest->rowCount();

if($check > 0 ){
    echo json_encode([
            "success"=> "Request deleted successfully",
    ]);
}else {
    echo json_encode(
        [
            "error"=>"Something went wrong sending friend request",
        ]
    );
}

}

deleteFriendRequest($conn,"request_id");

?>