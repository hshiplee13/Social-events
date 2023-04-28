<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="js/access.js"></script>
    <title>Social Events | Edit Event</title>
</head>
<body>
    <?php
    include "templates/header.php";
    include "templates/database.php";

    $eventID = $_GET['id'];

    $eventCollection = $db->eventsData;

    // Get the events details from the database
    $eventResult = $eventCollection->find(['event_id' => $eventID]);

    foreach ($eventResult as $event)
    {
        echo '<form action="editedEvent.php" method="POST" class="form">';
        echo '<h1>Edit Event Details</h1>';
        echo '<br>';

        // Error checking
        // Error message if the event is a duplicate
        if (isset($_GET['error']) && $_GET['error'] == 'duplicate')
        {
            echo '<p class="error">Duplicate events not allowed</p>';
        }

        // Error message if the title is the same
        if (isset($_GET['error']) && $_GET['error'] == 'title')
        {
            echo '<p class="error">You cant use the same title</p>';
        }

        echo '<label for="title"><b>Event Title</b></label>';
        echo '<input type="text" name="title" placeholder="' . $event->title . '">';
        echo '<br>';
        echo '<br>';

        echo '<label for="description"><b>Event Description</b></label>';
        echo '<textarea name="description" cols="30" rows="5" placeholder="' . $event->description . '"></textarea>';
        echo '<br>';
        echo '<br>';

        echo '<div class="row">';
        echo '<div class="column">';
        echo '<label for="startTime"><b>Event Start</b></label>';
        echo '<input type="time" name="startTime">';
        echo '</div>';

        echo '<div class="column">';
        echo '<label for="endTime"><b>Event End</b></label>';
        echo '<input type="time" name="endTime">';
        echo '</div>';

        echo '<div class="column">';
        echo '<label for="date"><b>Event Date</b></label>';
        echo '<input type="date" name="date">';
        echo '</div>';
        echo '</div>';
        echo '<br>';
        echo '<br>';

        echo '<div class="row">';
        echo '<div class="column">';
        echo '<label for="address"><b>Event Address</b></label>';
        echo '<input type="text" name="address" placeholder="' . $event->address . '">';
        echo '</div>';

        echo '<div class="column">';
        echo '<label for="location"><b>Event City</b></label>';
        echo '<input type="text" name="location" placeholder="' . $event->location . '">';
        echo '</div>';

        echo '<div class="column">';
        echo '<label for="postcode"><b>Event Postcode</b></label>';
        echo '<input type="text" name="postcode" placeholder="' . $event->postcode . '">';
        echo '</div>';
        echo '</div>';
        echo '<br>';
        echo '<br>';

        echo '<div class="row">';
        echo '<div class="column">';
        echo '<label for="price"><b>Event Price</b></label>';
        echo '<input type="number" name="price" placeholder="' . $event->price . '">';
        echo '</div>';

        echo '<div class="column">';
        echo '<label for="capacity"><b>Event Capacity</b></label>';
        echo '<input type="number" name="capacity" placeholder="' . $event->capacity . '">';
        echo '</div>';
        echo '</div>';
        echo '<br>';
        echo '<br>';

        echo '<label for="category"><b>Event Category</b></label>';
        echo '<select name="category" class="category-button">';
        echo '<option value="Club Night">Club Night</option>';
        echo '<option value="Concert">Festival</option>';
        echo '<option value="Sports Event">Sports Event</option>';
        echo '<option value="Party">Party</option>';
        echo '<option value="Live Music">Live Music</option>';
        echo '</select>';
        echo '<br>';
        echo '<br>';

        echo '<label for="access"><b>Who Can See This Event</b></label>';

        echo '<div class="row">';
        echo '<div class="column">';
        echo '<input type="radio" name="access" id="public" value="public" checked>';
        echo '<label for="public">Public</label>';
        echo '</div>';

        echo '<div class="column">';
        echo '<input type="radio" name="access" id="private" value="private">';
        echo '<label for="private">Private</label>';
        echo '</div>';
        echo '</div>';
        echo '<br>';

        echo '<div id="friend-list" class="friend-list">';
        echo '<label>Select friends to invite:</label>';
        echo '<br>';

        $userCollection = $db->userData;
        $followingCollection = $db->following;

        // Find all users
        $userResult = $userCollection->find([]);

        foreach ($userResult as $user)
        {
            $userID = $user->user_id;

            // Check if the session user follows the user
            $followingResult = $followingCollection->find([
                '$and' => [
                    ['user_id' => $userID],
                    ['follower_id' => $_SESSION['id']]
                ]
            ]);

            if (count($followingResult->toArray()) > 0)
            {
                // Check if the user follows the session user back
                $followerResult = $followingCollection->find([
                    '$and' => [
                        ['user_id' => $_SESSION['id']],
                        ['follower_id' => $userID]
                    ]
                ]);

                if (count($followerResult->toArray()) > 0)
                {
                    echo '<label><input type="checkbox" name="invites[]" value="' . $userID . '"> ' . $user->username . '</label>';
                    echo '<br>';
                }
            }
        }
        echo '</div>';

        echo '<button type="submit" name="editEvent" value="' . $eventID . '" class="button">Submit</button>';
        echo '<br>';
        echo '<br>';
        
        echo '<p>Or</p>';
        echo '<br>';

        echo '<button type="submit" name="deleteEvent" value="' . $eventID . '" class="button">Delete Event</button>';
        echo '</form>';
    }
    ?>
</body>
</html>