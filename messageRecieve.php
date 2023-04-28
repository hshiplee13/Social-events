<?php
session_start();
include "templates/database.php";

// Turn the userID string into an array, seperated where the comma is
$userID = explode(',', $_GET['userID']);

$messageCollection = $db->messages;
$userCollection = $db->userData;

// Count how many ids are part of the userID array
if (count($userID) == 1)
{
    // Find the user details for the first user
    $userResult = $userCollection->find(['user_id' => $userID[0]]);

    foreach ($userResult as $user)
    {
        $messageResult = $messageCollection->find([
            '$or' => [
                ['$and' => [
                    ['recipient' => $userID[0]],
                    ['sender' => $_SESSION['username']]
                ]],
                ['$and' => [
                    ['recipient' => $_SESSION['id']],
                    ['sender' => $user->username]
                ]]
            ]
        ]);
    }
}
else
{
    // Turn the user ids back into a string if there a more than one
    $groupID = implode(',', $userID);

    // Search the message collection for messages sent to that group
    $messageResult = $messageCollection->find(['recipient' => $groupID]);
}

// Create an array for the messages
$messages = [];

foreach ($messageResult as $message)
{
    $messages[] = $message;
}

// Turn the messages into JSON format
echo json_encode($messages);
?>