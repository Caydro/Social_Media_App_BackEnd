<?php



include("../../collagiey/connect.php");



function showMyPostsInProfile($conn,$userID){
    $user_id = $_POST[$userID];

$posts = $conn->prepare("SELECT * FROM `posts`  WHERE `userID`=:userID ORDER BY `time` DESC ");

$posts->execute([
    ":userID"=>$user_id,
]);

$fetchAllPosts= $posts->fetchAll(PDO::FETCH_ASSOC);

$check = $posts->rowCount();

if($check > 0){
    echo json_encode([
        "data"=>$fetchAllPosts,
    ]);
}else{
    echo json_encode([
        "noPosts"=>"There's no post to show",
    ]);
}

}



showMyPostsInProfile($conn,"user_id");



















