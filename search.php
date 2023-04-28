<?php
session_start();
include "templates/database.php";

$searchTerm = $_POST['searchTerm'];

$userCollection = $db->userData;
$eventCollection = $db->eventsData;

// Create an array for the search results
$searchResults = [];

// Search the user collection for usernames that contain the search term
$userResult = $userCollection->find(['username' => new MongoDB\BSON\Regex($searchTerm, 'i')]);

foreach ($userResult as $user)
{
    // Create an array for each individual user result
    $userResult = [];

    $userResult['user_id'] = $user['user_id'];
    $userResult['username'] = $user['username'];
    $userResult['type'] = 'user';

    // Insert the result into the search results array
    $searchResults[] = $userResult;
}

// Search the events collection for public events that contain the search term
$eventResult = $eventCollection->find([
    '$and' => [
        ['$or' => [
            ['title' => new MongoDB\BSON\Regex($searchTerm, 'i')],
            ['created_by' => new MongoDB\BSON\Regex($searchTerm, 'i')]
        ]],
        ['$or' => [
            ['access' => 'public'],
            ['created_by' => $_SESSION['username']],
            ['invites' => $_SESSION['id']]
        ]]
    ]
]);

foreach ($eventResult as $event)
{
    // Create an array for each individual event result
    $eventResult = [];

    $eventResult['event_id'] = $event['event_id'];
    $eventResult['title'] = $event['title'];
    $eventResult['type'] = 'event';

    // Insert the result into the search results array
    $searchResults[] = $eventResult;
}

$locationResult = $eventCollection->find([
    '$and' => [
        ['location' => new MongoDB\BSON\Regex($searchTerm, 'i')],
        ['$or' => [
            ['access' => 'public'],
            ['created_by' => $_SESSION['username']],
            ['invites' => $_SESSION['id']]
        ]]
    ]
]);

// Create a location array to prevent duplicates
$newLocation = [];

foreach ($locationResult as $location)
{
    if (!in_array($location['location'], $newLocation))
    {
        $newLocation[] = $location['location'];

        // Create an array for each individual location result
        $locationResult = [];

        $locationResult['location'] = $location['location'];
        $locationResult['type'] = 'location';

        // Insert the result into the search results array
        $searchResults[] = $locationResult;
    }
}

echo json_encode($searchResults);
?>