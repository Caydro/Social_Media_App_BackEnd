<?php
include("../../../collagiey/connect.php");
function checkIfCoverExists($conn, $user_id){
    $userID = $_POST[$user_id];

    $selectCover = $conn->prepare("SELECT `cover_background` FROM `users` WHERE `user_id`=:userID");
    $selectCover->execute(
        [
            ":userID"=>$userID,
        ]
    );
   $coverName = $selectCover->fetchAll();

    if($coverName[0]["cover_background"] != ""){
        echo json_encode(
            [
                "success"=>$coverName[0]["cover_background"],
            ]
        );
    }else{
        echo json_encode(
            [
                "error"=>"There's no cover available",
            ]
        );
    }

}

checkIfCoverExists($conn,"user_id");










?>