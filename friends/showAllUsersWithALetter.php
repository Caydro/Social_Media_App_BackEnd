<?php

include('../../collagiey/connect.php');

function showAllUsers($conn,$username,$user_id){
    $userName =$_POST[$username]."%";
    $userID = $_POST[$user_id];
    $usersQuery = $conn->prepare("SELECT * FROM `users` WHERE `user_name` LIKE :userName AND `user_id` NOT LIKE :userID");

    $usersQuery->execute([
        ":userName"=>$userName,
        "userID"=> $userID,
]);

    $usersData = $usersQuery->fetchAll(PDO::FETCH_ASSOC);


    $checking = $usersQuery->rowCount();


if($checking > 0){
    echo json_encode([
        "data"=>$usersData,
    ]);
}else{
    echo json_encode([
        "error"=>"There's no user with the given username"
    ]);
}

}



showAllUsers($conn,"user_name","user_id" );


















?>