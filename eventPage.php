<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Social Events</title>
</head>
<body>
    <?php 
    include "templates/header.php"; 
    include "templates/database.php";

    $eventsCollection = $db->eventsData;
    $eventID = $_GET['id'];

    // Search the database for the specific event
    $eventResult = $eventsCollection->find(['event_id' => $eventID]);

    foreach ($eventResult as $event)
    {
        // Turn the date into a DateTime object
        $eventDate = new DateTime($event->date);

        // Formatting the date into Day Month Year
        $formattedDate = $eventDate->format('l, F j Y');

        echo '<div class="page">';
        echo '<h1>' . $event->title . '</h1>';
        echo '<p><b>By ' . $event->created_by . '</b></p>';
        echo '<br>';

        // Error catching if the user is not invited to the event
        if (isset($_GET['error']) && $_GET['error'] == 'invite')
        {
            echo '<p class="error">You are not invited to this event</p>';
        }
        echo '<form action="booking.php" method="GET">';
        echo '<button class="button">Purchase Tickets</button>';
        echo '<input type="hidden" name="id" value="' . $event->event_id . '">';
        echo '</form>';
        echo '<br>';

        if ($event->created_by == $_SESSION['username'])
        {
            echo '<form action="editEvent.php" method="GET">';
            echo '<button class="button">Edit Event</button>';
            echo '<input type="hidden" name="id" value="' . $event->event_id . '">';
            echo '</form>';
        }
        echo '<br>';
        echo '<hr class="event-seperator">';
        echo '<br>';

        echo '<div class="event-details">';
        echo '<p><b>Price: £' . $event->price . '</b></p>';
        echo '<p><b>Start Time: ' . $event->start_time . '</b></p>';
        echo '<p><b>End Time: ' . $event->end_time . '</b></p>';
        echo '<p><b>Date: ' . $formattedDate . '</b></p>';
        echo '<p><b>Address: ' . $event->address . ', ' . $event->location . ', ' . $event->postcode . '</b></p>';
        echo '<br>';
        echo '<p><b>Description: </b>' . $event->description . '</p>';
        echo '</div>';

        echo '</div>';
    }
    ?>
</body>
</html>