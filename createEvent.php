<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="js/access.js"></script>
    <title>Social Events | Create Event</title>
</head>
<body>
    <?php
    include "templates/header.php";
    include "templates/database.php";
    ?>

    <form action="createdEvent.php" class="form" method="POST">
        <h1>Create a new event</h1>
        <p>Please fill in the event details</p>
        <br>
        <?php
        // Error message if the event is a duplicate
        if (isset($_GET['error']) && $_GET['error'] == 'duplicate')
        {
            echo '<p class="error">Duplicate events not allowed</p>';
            echo '<br>';
        }
        ?>

        <label for="title"><b>Event Title</b></label>
        <input type="text" name="title" placeholder="Enter the event title..." required>
        <br>
        <br>

        <label for="description"><b>Event Description</b></label>
        <textarea name="description" cols="30" rows="5" required></textarea>
        <br>
        <br>

        <div class="row">
            <div class="column">
                <label for="startTime"><b>Event Start</b></label>
                <input type="time" name="startTime" required>
            </div>

            <div class="column">
                <label for="endTime"><b>Event End</b></label>
                <input type="time" name="endTime" required>
            </div>

            <div class="column">
                <label for="date"><b>Event Date</b></label>
                <input type="date" name="date" required>
            </div>
        </div>
        <br>
        <br>

        <div class="row">
            <div class="column">
                <label for="address"><b>Event Address</b></label>
                <input type="text" name="address" placeholder="Enter the event address..." required>
            </div>

            <div class="column">
                <label for="location"><b>Event City</b></label>
                <input type="text" name="location" placeholder="Enter the event city..." required>
            </div>

            <div class="column">
                <label for="postcode"><b>Event Postcode</b></label>
                <input type="text" name="postcode" placeholder="Enter the event postcode..." required>
            </div>
        </div>
        <br>
        <br>

        <div class="row">
            <div class="column">
                <label for="price"><b>Event Price</b></label>
                <input type="number" name="price" required>
            </div>

            <div class="column">
                <label for="capacity"><b>Event Capacity</b></label>
                <input type="number" name="capacity" required>
            </div>
        </div>
        <br>
        <br>

        <label for="category"><b>Event Category</b></label>
        <select name="category" class="category-button">
            <option value="Club Night">Club Night</option>
            <option value="Concert">Festival</option>
            <option value="Sports Event">Sports Event</option>
            <option value="Party">Party</option>
            <option value="Live Music">Live Music</option>
        </select>
        <br>
        <br>

        <label for="access"><b>Who Can See This Event</b></label>
        <div class="row">
            <div class="column">
                <input type="radio" name="access" id="public" value="public" checked>
                <label for="public">Public</label>
            </div>

            <div class="column">
                <input type="radio" name="access" id="private" value="private">
                <label for="private">Private</label>
            </div>
        </div>
        <br>

        <div id="friend-list" class="friend-list">
            <label>Select friends to invite:</label>
            <br>

            <?php
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
            ?>
        </div>

        <button type="submit" class="button">Create Event</button>
    </form>
</body>
</html>