<?php
session_start();
include "templates/database.php";

$messageCollection = $db->messages;

$recipient = $_POST['recipient'];
$message = $_POST['message'];

// Create the document for the message
$messageDocument = array(
    'sender' => $_SESSION['username'],
    'recipient' => $recipient,
    'message' => $message,
    'timestamp' => date('h:ia d-m-Y')
);

// Insert the document into the messages collection
$messageCollection->insertOne($messageDocument);
?>