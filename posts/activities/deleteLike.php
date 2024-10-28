<?php

include("../../../collagiey/connect.php");

function addLike($conn, $post_ID,$user_id) {
    // Accessing the POST variable directly
    $postID = $_POST[$post_ID];
    $userID = $_POST[$user_id];

    // Fetch current likes from the database
    $getLikesFromPost = $conn->prepare("SELECT `likes` FROM `posts` WHERE `post_id` = :postID");
    $getLikesFromPost->execute([
        "postID" => $postID
    ]);
    $fetchlikes = $getLikesFromPost->fetch(PDO::FETCH_ASSOC);

    // Check if the post exists and has likes
    if ($fetchlikes) {
        $likes = $fetchlikes['likes'];
        $LikesAfterAddOneLike = $likes - 1;

        // Update the likes count in the database
        $deleteNewLikesToDatabase = $conn->prepare("UPDATE `posts` SET `likes` = :likes WHERE `post_id` = :postID");
        $deleteNewLikesToDatabase->execute([
            ":likes" => $LikesAfterAddOneLike,
            ":postID" => $postID,
        ]);
        if($deleteNewLikesToDatabase->rowCount()){
        $deleteUserThatAddLike = $conn->prepare("DELETE FROM `post_likes` WHERE `post_id` = :postID AND `user_liked_id` = :userID");
        $deleteUserThatAddLike->execute(
            [
                ":postID"=>$postID,
                ":userID"=>$userID,
            ]
        );
        if($deleteUserThatAddLike->rowCount() > 0){
            echo json_encode([
                "success"=>"like deleted successfully"
            ]);
        }else{
            echo json_encode([
                "error"=>"Somthing happend Wrong"
            ]);
        }
        }else{
            echo json_encode([
                "error"=>"Somthing Happend wrong while Adding your like"
            ]);
        }
    } else {
        // Handle the case where the post does not exist
        echo json_encode([
            "error"=>"Post not found.",
        ]);
    }
}

addLike($conn, "post_id","user_id");
?>