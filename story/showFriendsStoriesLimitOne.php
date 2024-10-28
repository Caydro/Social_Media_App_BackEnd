<?php
include('../../collagiey/connect.php');

function getUserAndFriendsStories($conn, $user_id) {
    $userID = $_POST[$user_id];

    // Initialize an array to hold all stories (your story + friends' stories)
    $allStoriesArray = [];

    // 1. Fetch the user's (main) most recent story
    $getUserStory = $conn->prepare("SELECT * FROM `stories` WHERE `user_id` = :userID ORDER BY `created_at` DESC LIMIT 1");
    $getUserStory->execute([":userID" => $userID]);
    $userStory = $getUserStory->fetch();

    // If the user has a story, add it to the top of the stories array
    if ($getUserStory->rowCount() > 0) {
        $userStory['time_ago'] = timeAgo($userStory['created_at']);
        $userStory['is_main_story'] = true; // Mark it as the main story
        $allStoriesArray[] = $userStory;
    }

    // 2. Fetch friends' first stories
    $getAllFriends = $conn->prepare("SELECT * FROM `friends` WHERE `user_id` = :userID OR `otherFriend_id` = :userID");
    $getAllFriends->execute([":userID" => $userID]);
    $allMyUsers = $getAllFriends->fetchAll();
    $check = $getAllFriends->rowCount();

    if ($check > 0) {
        for ($i = 0; $i < count($allMyUsers); $i++) {
            // Determine the friend's user ID
            $friendID = ($allMyUsers[$i]['otherFriend_id'] == $userID) 
                ? $allMyUsers[$i]["user_id"] 
                : $allMyUsers[$i]["otherFriend_id"];

            // Fetch the first story for each friend
            $getFirstStory = $conn->prepare("SELECT * FROM `stories` WHERE `user_id` = :userID ORDER BY `created_at` DESC LIMIT 1");
            $getFirstStory->execute([":userID" => $friendID]);

            $story = $getFirstStory->fetch();

            // If a story exists, add it to the stories array
            if ($getFirstStory->rowCount() > 0) {
                $story['time_ago'] = timeAgo($story['created_at']);
                $story['is_main_story'] = false; // Mark it as a friend's story
                $allStoriesArray[] = $story;
            }
        }

        // 3. Sort the stories array by `created_at` time in descending order (newest first)
        usort($allStoriesArray, function ($a, $b) {
            return strtotime($b['created_at']) - strtotime($a['created_at']);
        });

        // 4. Return the sorted stories
        if (count($allStoriesArray) > 0) {
            echo json_encode([
                "data" => $allStoriesArray,
            ]);
        } else {
            echo json_encode([
                "noStories" => "There are no stories to see",
            ]);
        }
    } else {
        echo json_encode([
            "error" => "You don't have any friends yet.",
        ]);
    }
}

// Function to calculate time ago (same as before)
function timeAgo($storyTime) {
    $currentTime = time();
    $timeDifference = $currentTime - strtotime($storyTime);

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
        return date("F j, Y", strtotime($storyTime)); // format older dates
    }
}

// Call the function
getUserAndFriendsStories($conn, "user_id");