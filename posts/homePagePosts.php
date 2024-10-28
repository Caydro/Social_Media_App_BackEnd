<?php

include("../../collagiey/connect.php");

function getAllMyFriendsID($conn, $user_id) {
    // Correct way to access POST data
    $userID = $_POST[$user_id];

    // Prepare and execute the query to get all friends
    $getAllFriends = $conn->prepare("SELECT * FROM `friends` WHERE `user_id` = :userID OR `otherFriend_id` = :userID");
    $getAllFriends->execute([
        ":userID" => $userID,
    ]);
    $allMyUsers = $getAllFriends->fetchAll();
    $check = $getAllFriends->rowCount();

    // Initialize an array to hold all posts (for user and friends)
    $allPostsArray = [];

    // Fetch main posts by the logged-in user
    $getMyPosts = $conn->prepare("SELECT * FROM `posts` WHERE `userID` = :userID ORDER BY `time` DESC");
    $getMyPosts->execute([":userID" => $userID]);
    $myPosts = $getMyPosts->fetchAll();

    // Add the user's own posts to the array
    if ($getMyPosts->rowCount() > 0) {
        foreach ($myPosts as $post) {
            // Format the time difference for each post
            $post['time_ago'] = timeAgo($post['time']);
            $allPostsArray[] = $post;
        }
    }

    // Check if the user has any friends
    if ($check > 0) {
        for ($i = 0; $i < count($allMyUsers); $i++) {
            // Get posts for each friend
            $getAllPosts = $conn->prepare("SELECT * FROM `posts` WHERE `userID` = :userID AND `post_privacy` = :post_privacy ORDER BY `time` DESC");
            if ($allMyUsers[$i]['otherFriend_id'] == $userID) {
                $getAllPosts->execute([
                    ":userID" => $allMyUsers[$i]["user_id"],
                    ":post_privacy" => "anyOne",
                ]);
            } else {
                $getAllPosts->execute([
                    ":userID" => $allMyUsers[$i]["otherFriend_id"],
                    ":post_privacy" => "anyOne",
                ]);
            }

            $allPosts = $getAllPosts->fetchAll();

            // Add the friend's posts to the allPostsArray if there are any
            if ($getAllPosts->rowCount() > 0) {
                foreach ($allPosts as $post) {
                    // Format the time difference for each post
                    $post['time_ago'] = timeAgo($post['time']);
                    $allPostsArray[] = $post;
                }
            }
        }

        // Sort all posts (user's and friends') by time in descending order
        usort($allPostsArray, function ($a, $b) {
            return strtotime($b['time']) - strtotime($a['time']); // Sort by time in descending order
        });

        // Check if we found any posts
        if (count($allPostsArray) > 0) {
            echo json_encode([
                "data" => $allPostsArray,
            ]);
        } else {
            echo json_encode([
                "error" => "There's no posts for any friends",
            ]);
        }
    } else {
        echo json_encode([
            "error" => "There's no friends, try to add some",
        ]);
    }
}

function timeAgo($postTime) {
    $currentTime = time();
    $timeDifference = $currentTime - strtotime($postTime);

    if ($timeDifference < 60) {
        return $timeDifference . " seconds ago";
    } elseif ($timeDifference < 3600) {
        return floor($timeDifference / 60) . " minutes ago";
    } elseif ($timeDifference < 86400) {
        return floor($timeDifference / 3600) . " hours ago";
    } elseif ($timeDifference < 604800) {
        return floor($timeDifference / 86400) . " days ago";
    } elseif ($timeDifference < 2419200) {
        return floor($timeDifference / 604800) . " weeks ago";
    } else {
        return date("F j, Y", strtotime($postTime)); // display as formatted date for older posts
    }
}

// Call the function
getAllMyFriendsID($conn, "user_id");

?>