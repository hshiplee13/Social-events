<?php
session_start();
include "templates/database.php";

$eventCollection = $db->eventsData;

if (isset($_POST['deleteEvent']))
{
    $eventID = $_POST['deleteEvent'];

    $ticketCollection = $db->purchasedEvents;

    // Delete the tickets for the event
    $ticketDelete = $ticketCollection->deleteMany(['event_id' => $eventID]);

    // Delete the event from the database
    $eventsDelete = $eventCollection->deleteMany(['event_id' => $eventID]);

    header('Location: profile.php');
    exit;
}
else if (isset($_POST['editEvent']))
{
    $eventID = $_POST['editEvent'];

    // Get the details for the event
    $eventResult = $eventCollection->find(['event_id' => $eventID]);

    foreach($eventResult as $event)
    {
        $title = $event->title;
        $description = $event->description;
        $start = $event->start_time;
        $end = $event->end_time;
        $date = $event->date;
        $address = $event->address;
        $postcode = $event->postcode;
        $location = $event->location;
        $price = $event->price;
        $capacity = $event->capacity;
        $category = $event->category;
        $invites = $event->invites;
    }

    // Check if the event is a duplicate
    $duplicateCheck = $eventCollection->find([
        '$and' => [
            ['title' => $_POST['title']],
            ['date' => $_POST['date']]
        ]
    ]);

    // Return the duplicate error code
    if (count($duplicateCheck->toArray()) > 0) 
    {
        header('Location: editEvent.php?error=duplicate');
        exit;
    }

    // Check if the title has been updated
    if(isset($_POST['title']) && $_POST['title'] != null)
    {
        // Check if the title is the already taken
        $titleCheck = $eventCollection->find(['title' => $_POST['title']]);

        if (count($titleCheck->toArray()) > 0) 
        {
            header('Location: editEvent.php?error=title');
            exit;
        }
        else
        {
            $title = $_POST['title'];
        }
    }

    // Check if the description has been updated
    if (isset($_POST['description']) && $_POST['description'] != null) 
    {
        $description = $_POST['description'];
    }

    // Check if the start time has been updated
    if (isset($_POST['startTime']) && $_POST['startTime'] != null) 
    {
        $start = $_POST['startTime'];
    }

    // Check if the end time has been updated
    if (isset($_POST['endTime']) && $_POST['endTime'] != null) 
    {
        $end = $_POST['endTime'];
    }

    // Check if the date has been updated
    if (isset($_POST['date']) && $_POST['date'] != null) 
    {
        $date = $_POST['date'];
    }

    // Check if the address has been updated
    if (isset($_POST['address']) && $_POST['address'] != null) 
    {
        $address = $_POST['address'];
    }

    // Check if the location has been updated
    if (isset($_POST['postcode']) && $_POST['postcode'] != null) 
    {
        $postcode = $_POST['postcode'];
    }

    // Check if the postcode has been updated
    if (isset($_POST['location']) && $_POST['location'] != null) 
    {
        $location = $_POST['location'];
    }

    // Check if the price has been updated
    if (isset($_POST['price']) && $_POST['price'] != null) 
    {
        $price = $_POST['price'];
    }

    // Check if the capacity has been updated
    if (isset($_POST['capacity']) && $_POST['capacity'] != null) 
    {
        $capacity = $_POST['capacity'];
    }

    // Check if the category has been updated
    if (isset($_POST['category']) && $_POST['category'] != null) 
    {
        $category = $_POST['category'];
    }

    // Check if the invites has been updated
    if (isset($_POST['invites']) && $_POST['invites'] != null) 
    {
        $invites = $_POST['invites'];
    }

    // Check if the event is private
    if($_POST['access'] == 'private' && isset($_POST['invites']))
    {
        // Update the event in the database if its private
        $eventCollection->updateOne(
            ['event_id' => $eventID],
            ['$set' => [
                "event_id" => $eventID,
                "title" => $title,
                "created_by" => $_SESSION['username'],
                "description" => $description,
                "start_time" => $start,
                "end_time" => $end,
                "date" => $date,
                "address" => $address,
                "postcode" => $postcode,
                "location" => $location,
                "price" => intval($price, 10),
                "capacity" => intval($capacity, 10),
                "category" => $category,
                "access" => 'private',
                "invites" => $invites
            ]]
        );

        header('Location: profile.php');
        exit;
    }
    else
    {
        // Update the event in the database if its public
        $eventCollection->updateOne(
            ['event_id' => $eventID],
            ['$set' => [
                "event_id" => $eventID,
                "title" => $title,
                "created_by" => $_SESSION['username'],
                "description" => $description,
                "start_time" => $start,
                "end_time" => $end,
                "date" => $date,
                "address" => $address,
                "postcode" => $postcode,
                "location" => $location,
                "price" => intval($price, 10),
                "capacity" => intval($capacity, 10),
                "category" => $category,
                "access" => 'public',
                "invites" => null
            ]]
        );
        
        header('Location: profile.php');
        exit;
    }
}
?>