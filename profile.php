<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Social Events | Profile</title>
</head>
<body>
    <?php
    include "templates/database.php";
    include "templates/header.php";

    // Verify the user is logged in otherwise take them to the Sign In page
    if ($_SESSION['logged_in'] == false)
    {
        header('Location: signIn.php');
        exit;
    }

    // Check if it is the user's own profile or someone elses profile
    if (!isset($_GET['id']) || $_GET['id'] == $_SESSION['id'])
    {
        $personalCollection = $db->userData;

        // Get the user details
        $personalResult = $personalCollection->find(['user_id' => $_SESSION['id']]);

        foreach ($personalResult as $personal)
        {
            $personalFollowCollection = $db->following;
            echo '<div class="page">';
            echo '<div class="profile-page">';
            echo '<h1>' . $personal->username . '</h1>';
            echo '<br>';

            echo '<div class="row">';
            echo '<div class="column">';

            // Count the number of other users that follow the session user
            $personalFollowersResult = $personalFollowCollection->count(['user_id' => $personal->user_id]);

            echo '<a href="following.php?action=followers&id=' . $_SESSION['id'] . '">Followers: ' . $personalFollowersResult . '</a>';
            echo '</div>';
            echo '<div class="column">';

            // Count the number of other users that the session user follows
            $personalFollowingResult = $personalFollowCollection->count(['follower_id' => $personal->user_id]);

            echo '<a href="following.php?action=following&id=' . $_SESSION['id'] . '">Following: ' . $personalFollowingResult . '</a>';
            echo '</div>';
            echo '</div>';
            echo '<br>';

            echo '<form action="editProfile.php">';
            echo '<button class="button">Edit Profile</button>';
            echo '</form>';
            echo '<br>';
            echo '<br>';

            echo '<h3>Created Events</h3>';
            echo '<br>';

            $personalEventsCollection = $db->eventsData;

            // Search database for events created be session user
            $personalCreatedResult = $personalEventsCollection->find(['created_by' => $_SESSION['username']]);

            echo '<div class="row">';

            foreach ($personalCreatedResult as $personalCreated)
            {
                echo '<div class="column">';

                // Turn the date into a DateTime object
                $eventDate = new DateTime($personalCreated->date);

                // Formatting the date into Day Month Year
                $formattedDate = $eventDate->format('l, F j Y');

                $invites = $personalCreated['invites'];

                // Create a username array
                $usernames = [];

                $userCollection = $db->userData;

                foreach ($invites as $invitee)
                {
                    // Search the database for the invited users details
                    $inviteResult = $userCollection->find(['user_id' => $invitee]);

                    foreach ($inviteResult as $invitedUser)
                    {
                        // Insert the username into the usernames array
                        $usernames[] = $invitedUser->username;
                    }
                }

                // Turn the array into a string
                $invited = implode(", ", $usernames);


                echo '<form action="eventPage.php" method="GET">';
                echo '<button class="button-large">';
                echo '<h3>' . $personalCreated->title . '</h3>';
                echo '<p>' . $personalCreated->category . '</p>';
                echo '<p>' . $personalCreated->start_time . '-' . $personalCreated->end_time . ', ' . $formattedDate . '</p>';
                echo '<p>' . $personalCreated->address . ', ' . $personalCreated->location . ', ' . $personalCreated->postcode . '</p>';
                echo '<p>£' . $personalCreated->price . ' and Capacity: ' . $personalCreated->capacity . '</p>';
                echo '<p>Invited: ' . $invited . '</p>';
                echo '<input type="hidden" name="id" value="' . $personalCreated->event_id . '">';
                echo '</button>';
                echo '</form>';
                echo '</div>';
            }

            echo '</div>';
            echo '<br>';
            echo '<br>';

            echo '<h3>Purchased Events</h3>';
            echo '<br>';

            $personalPurchasedCollection = $db->purchasedEvents;

            // Search database for events purchased be session user
            $personalPurchasedResult = $personalPurchasedCollection->find(['user_id' => $_SESSION['id']]);

            echo '<div class="row">';

            foreach ($personalPurchasedResult as $personalPurchased)
            {
                // Retrieve the purchased events details
                $purchasedResult = $personalEventsCollection->find(['event_id' => $personalPurchased->event_id]);

                foreach ($purchasedResult as $purchasedEvent)
                {
                    echo '<div class="column">';

                    // Turn the date into a DateTime object
                    $eventDate = new DateTime($purchasedEvent->date);
    
                    // Formatting the date into Day Month Year
                    $formattedDate = $eventDate->format('l, F j Y');
    
                    echo '<form action="eventPage.php" method="GET">';
                    echo '<button class="button-large">';
                    echo '<h3>' . $purchasedEvent->title . '</h3>';
                    echo '<p>' . $purchasedEvent->start_time . '-' . $purchasedEvent->end_time . ', ' . $formattedDate . '</p>';
                    echo '<p>' . $purchasedEvent->address . ', ' . $purchasedEvent->location . ', ' . $purchasedEvent->postcode . '</p>';
                    echo '<p><b>Reference Number: ' . $personalPurchased->reference_number . '</b></p>';
                    echo '<input type="hidden" name="id" value="' . $personalPurchased->event_id . '">';
                    echo '</button>';
                    echo '</form>';
                    echo '</div>';
                }
            }

            echo '</div>';
            echo '<br>';
            echo '<br>';

            echo '<h3>Invited To</h3>';
            echo '<br>';

            // Search database for evente session user is invited to
            $personalInvitedResult = $personalEventsCollection->find(['invites' => $_SESSION['id']]);

            echo '<div class="row">';

            foreach ($personalInvitedResult as $personalInvited)
            {
                echo '<div class="column">';

                // Turn the date into a DateTime object
                $eventDate = new DateTime($personalInvited->date);

                // Formatting the date into Day Month Year
                $formattedDate = $eventDate->format('l, F j Y');

                echo '<form action="eventPage.php" method="GET">';
                echo '<button class="button-large">';
                echo '<h3>' . $personalInvited->title . '</h3>';
                echo '<p>' . $personalInvited->start_time . '-' . $personalInvited->end_time . ', ' . $formattedDate . '</p>';
                echo '<p>' . $personalInvited->address . ', ' . $personalInvited->location . ', ' . $personalInvited->postcode . '</p>';
                echo '<p>£' . $personalInvited->price . '</p>';
                echo '<input type="hidden" name="id" value="' . $personalInvited->event_id . '">';
                echo '</button>';
                echo '</form>';
                echo '</div>';
            }

            echo '</div>';
            echo '<br>';
            echo '<br>';

            echo '</div>';
            echo '</div>';
        }
    }
    else
    {
        $userCollection = $db->userData;
        
        // Get the user's details from the database
        $userResult = $userCollection->find(['user_id' => $_GET['id']]);

        foreach ($userResult as $user)
        {
            // Save the user_id as a variable
            $userID = $user->user_id;

            $followCollection = $db->following;
            echo '<div class="page">';
            echo '<div class="profile-page">';
            echo '<h1>' . $user->username . '</h1>';
            echo '<br>';

            echo '<div class="row">';

            // Check if the session user follows the user
            $checkFollower = $followCollection->find([
                '$and' => [
                    ['user_id' => $userID],
                    ['follower_id' => $_SESSION['id']]
                ]
            ]);

            // Check if the user follows the session user
            $checkFollowed = $followCollection->find([
                '$and' => [
                    ['user_id' => $_SESSION['id']],
                    ['follower_id' => $userID]
                ]
            ]);

            $following = count($checkFollower->toArray());
            $followed = count($checkFollowed->toArray());

            echo '<div class="column">';

            // Count the number of followers the user has
            $followersResult = $followCollection->count(['user_id' => $userID]);

            if ($following > 0)
            {
                if ($followed > 0)
                {
                    // If the user and session user follow each other, let the session user access their followers
                    echo '<a href="following.php?action=followers&id=' . $user->user_id . '">Followers: ' . $followersResult . '</a>';
                }
                else
                {
                    echo '<p>Followers: ' . $followersResult . '</p>';
                }
            }
            else
            {
                echo '<p>Followers: ' . $followersResult . '</p>';
            }
            echo '</div>';

            echo '<div class="column">';

            // Count the number of users they follow
            $followingResult = $followCollection->count(['follower_id' => $userID]);

            if ($following > 0)
            {
                if ($followed > 0)
                {
                    // If the user and session user follow each other, let the session user access their followers
                    echo '<a href="following.php?action=following&id=' . $user->user_id . '">Following: ' . $followingResult . '</a>';
                }
                else
                {
                    echo '<p>Following: ' . $followingResult . '</p>';
                }
            }
            else
            {
                echo '<p>Following: ' . $followingResult . '</p>';
            }
            echo '</div>';
            echo '</div>';
            echo '<br>';

            if ($following > 0)
            {
                if ($followed > 0)
                {
                    // If the session user and the user follow each other, let the session user unfollow and message them
                    echo '<div class="row">';
                    echo '<div class="column">';
                    echo '<form action="follow.php" method="POST">';
                    echo '<button class="button">Unfollow</button>';
                    echo '<input type="hidden" name="action" value="unfollow">';
                    echo '<input type="hidden" name="id" value="' . $userID . '">';
                    echo '</form>';
                    echo '</div';

                    echo '<div class="column">';
                    echo '<form action="messaging.php" method="POST">';
                    echo '<button class="button">Message</button>';
                    echo '<input type="hidden" name="id[]" value="' . $userID . '">';
                    echo '</form>';
                    echo '</div';
                    echo '</div';
                }
                else
                {
                    // If the only the session user follows the user, let them just unfollow
                    echo '<form action="follow.php" method="POST">';
                    echo '<button class="button">Unfollow</button>';
                    echo '<input type="hidden" name="action" value="unfollow">';
                    echo '<input type="hidden" name="id" value="' . $userID . '">';
                    echo '</form>';
                }
            }
            else
            {
                // If neither user follows the other, let the session user follow them
                echo '<form action="follow.php" method="POST">';
                echo '<button class="button">Follow</button>';
                echo '<input type="hidden" name="action" value="follow">';
                echo '<input type="hidden" name="id" value="' . $userID . '">';
                echo '</form>';
            }
            echo '<br>';
            echo '<br>';

            echo '<h3>Created Events</h3>';
            echo '<br>';

            $eventCollection = $db->eventsData;

            // Retrieve events the user has created
            // Check they are set to public or that the session user is invited to the event
            $userCreated = $eventCollection->find([
                '$and' => [
                    ['created_by' => $user->username],
                    ['$or' => [
                        ['access' => 'public'],
                        ['invites' => $_SESSION['username']]
                    ]]
                ]
            ]);

            echo '<div class="row">';

            foreach ($userCreated as $created)
            {
                echo '<div class="column">';

                // Turn the date into a DateTime object
                $eventDate = new DateTime($created->date);

                // Formatting the date into Day Month Year
                $formattedDate = $eventDate->format('l, F j Y');

                echo '<form action="eventPage.php" method="GET">';
                echo '<button class="button-large">';
                echo '<h3>' . $created->title . '</h3>';
                echo '<p>' . $created->start_time . '-' . $created->end_time . ', ' . $formattedDate . '</p>';
                echo '<p>' . $created->address . ', ' . $created->location . ', ' . $created->postcode . '</p>';
                echo '<p>£' . $created->price . '</p>';
                echo '<input type="hidden" name="id" value="' . $created->event_id . '">';
                echo '</button>';
                echo '</form>';
                echo '</div>';
            }

            echo '</div>';
            echo '<br>';
            echo '<br>';

            echo '</div>';
            echo '</div>';
        }
    }
    ?>
</body>
</html>