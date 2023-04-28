<?php
session_start();
include "templates/database.php";

// Check if the user is following another user
if ($_POST['action'] == 'follow')
{
    $followCollection = $db->following;

    $userID = $_POST['id'];
    $followerID = $_SESSION['id'];

    // Build the following document
    $followingDocument = array(
        "user_id" => $userID,
        "follower_id" => $followerID
    );

    // Insert the document into the following collection
    $followCollection->insertOne($followingDocument);

    header('Location: profile.php?id=' . $userID);
    exit;
}
// Else check if the user is unfollowing another user
else if ($_POST['action'] == 'unfollow')
{
    $followCollection = $db->following;

    $userID = $_POST['id'];
    $followerID = $_SESSION['id'];

    // Delete the follow document
    $followCollection->deleteOne([
        '$and' => [
            ['user_id' => $userID],
            ['follower_id' => $followerID] 
        ]
    ]);

    header('Location: profile.php?id=' . $userID);
    exit;
}
?>