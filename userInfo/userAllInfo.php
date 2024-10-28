<?php
include "../connect.php";
function userInfo($conn,$userID){
    
$userID = $_POST[$userID];
// there's a file that have filter request to make you just put the parameter here and its filtered  there's one also for GET Method have fun..

   $data = $conn->prepare("SELECT * FROM `users` WHERE `user_id`=:userID");

$data->execute([':userID' => $userID]);
$dataFetch = $data->fetchall(PDO::FETCH_ASSOC);
$count = $data->rowCount();
if($count > 0){
echo json_encode(array(
    "success"=>"Data successfully stored",
    "email"=>$dataFetch[0]["email"],
    "userName"=>$dataFetch[0]["user_name"],
    'birthDay'=>$dataFetch[0]["birth_day"],
    'circle_background'=>$dataFetch[0]["circle_background"],
    'cover_background'=>$dataFetch[0]["cover_background"],
));

}else{
    echo json_encode(array("error"=>"wrong data"));
}

}
//check if email exists//////////
userInfo($conn, "userID");
?>