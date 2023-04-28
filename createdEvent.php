<?php
session_start();
include "templates/database.php";

$eventCollection = $db->eventsData;

// Check if the created event is a duplicate
$duplicateResult = $eventCollection->find([
    '$and' => [
        ['title' => $_POST['title']],
        ['date' => $_POST['date']]
    ]
]);

// Return the error code
if (count($duplicateResult->toArray()) > 0)
{
    header('Location: createEvent.php?error=duplicate');
    exit;
}

// Create a unique event id
$eventID = uniqid('event_');

if ($_POST['access'] == 'private' && isset($_POST['invites']))
{
    // Create the event document for a private event
    $eventDocument = array(
        "event_id" => $eventID,
        "title" => $_POST['title'],
        "created_by" => $_SESSION['username'],
        "description" => $_POST['description'],
        "start_time" => $_POST['startTime'],
        "end_time" => $_POST['endTime'],
        "date" => $_POST['date'],
        "address" => $_POST['address'],
        "postcode" => $_POST['postcode'],
        "location" => $_POST['location'],
        "price" => intval($_POST['price'], 10),
        "capacity" => intval($_POST['capacity'], 10),
        "category" => $_POST['category'],
        "access" => 'private',
        "invites" => $_POST['invites']
    );
}
else
{
    // Create the event document for a public event
    $eventDocument = array(
        "event_id" => $eventID,
        "title" => $_POST['title'],
        "created_by" => $_SESSION['username'],
        "description" => $_POST['description'],
        "start_time" => $_POST['startTime'],
        "end_time" => $_POST['endTime'],
        "date" => $_POST['date'],
        "address" => $_POST['address'],
        "postcode" => $_POST['postcode'],
        "location" => $_POST['location'],
        "price" => intval($_POST['price'], 10),
        "capacity" => intval($_POST['capacity'], 10),
        "category" => $_POST['category'],
        "access" => 'public',
        "invites" => null
    );
}

$eventCollection->insertOne($eventDocument);

header('Location: profile.php');
?>