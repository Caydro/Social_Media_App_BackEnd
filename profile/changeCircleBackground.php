<?php

include("../../collagiey/connect.php");
include '../profile/circleBackGroundAndBirthDayAddFunction.php';
function changeCircleBackground($conn, $user_id){
    
    $userID = $_POST[$user_id];
    $imageName = uploadImageFunc('c_image');

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        if ($imageName !== "Failed") {
            $dat = $conn->prepare("UPDATE `users` SET  `circle_background` = :image WHERE `user_id` = :userID");
            $dat->bindParam(':image', $imageName);
            $dat->bindParam(':userID', $userID);
            $dat->execute();
        $check=  $dat->rowCount();
            if($check > 0 ){
                echo json_encode(
                    [
                        "success"=>"Profile Circle Background Successfully Updated"
                    ]
                );
            }else{
                echo json_encode(
                    [
                        "error"=>"Somthing went wrong"
                    ]
                );
            }
        }

    }


}



changeCircleBackground($conn, "user_id");














?>