<?php
session_start();
include "templates/database.php";

$poster = $_SESSION['id'];
$post = $_POST['message'];
$postTime = date("Y-m-d H:i:s");

// Check if it is a post or a reply
if (!isset($_POST['id']))
{
    // If it is a post then create a unique post id
    $postID = uniqid('post_');

    $postCollection = $db->posts;

    $postDocument = array(
        "post_id" => $postID,
        "poster" => $poster,
        "content" => $post,
        "timestamp" => $postTime
    );

    $postCollection->insertOne($postDocument);
}
else
{
    // If it is a reply than create a variable for the ID
    $postID = $_POST['id'];

    $repliesCollection = $db->replies;

    $replyDocument = array(
        "post_id" => $postID,
        "poster" => $poster,
        "content" => $post,
        "timestamp" => $postTime
    );

    $repliesCollection->insertOne($replyDocument);
}

header('Location: feed.php');
?>