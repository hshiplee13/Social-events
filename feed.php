<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Social Events | Feed</title>
</head>
<body>
    <?php
    include "templates/header.php";
    include "templates/database.php";

    $userCollection = $db->userData;
    $eventCollection = $db->eventsData;
    $followingCollection = $db->following;
    $postCollection = $db->posts;
    $repliesCollection = $db->replies;

    // Create an array to store the events retrieved from the database
    $events = [];

    // Create an array to store the posts retrieved from the database
    $posts = [];

    echo '<div class="page">';
    echo '<h1>Feed</h1>';

    echo '<form action="feedPost.php" class="form" method="POST">';
    echo '<input type="text" name="message" placeholder="Type your message...">';
    echo '<button type="submit" class="button">Post</button>';
    echo '</form>';
    echo '<br>';

    // Find posts by the session user
    $userPostResult = $postCollection->find(['poster' => $_SESSION['id']]);

    foreach ($userPostResult as $userPost)
    {
        // Insert the users posts into the posts array
        $posts[] = $userPost;
    }

    // Find events by the session user
    $userEventResult = $eventCollection->find(['created_by' => $_SESSION['username']]);

    foreach ($userEventResult as $userEvent)
    {
        // Insert the user events into the events array
        $events[] = $userEvent;
    }

    // Find users that the session user follows
    $followingResult = $followingCollection->find(['follower_id' => $_SESSION['id']]);

    foreach ($followingResult as $following)
    {
        // Retrieve user data of the users that the session user follows
        $userResult = $userCollection->find(['user_id' => $following->user_id]);

        foreach ($userResult as $user)
        {
            // Retrieve events created by users that the session user follows
            $eventResult = $eventCollection->find([
                '$and' => [
                    ['created_by' => $user->username],
                    ['$or' => [
                        ['access' => 'public'],
                        ['invites' => $_SESSION['id']]
                    ]]
                ]
            ]);

            foreach ($eventResult as $event)
            {
                // Store the events in the events array
                $events[] = $event;
            }

            // Retrieve posts created by users that the session user follows
            $postResult = $postCollection->find(['poster' => $user->user_id]);

            foreach ($postResult as $post)
            {
                // Store the posts in the posts array
                $posts[] = $post;
            }
        }
    }

    // Remove any duplicate events
    $events = array_unique($events, SORT_REGULAR);

    // Remove any duplicate posts
    $posts = array_unique($posts, SORT_REGULAR);

    /*
    function eventSort($a, $b)
    {
        return $a <=> $b;
    }

    function postSort ($a, $b)
    {
        return $b <=> $a;
    }

    usort($eventsCombined, "eventsSort");
    usort($postsCombined, "postSort");
    */

    echo '<div class="row">';
    echo '<div class="column feed">';
    echo '<h2>Events</h2>';
    echo '<br>';
    echo '<br>';

    foreach ($events as $feedEvent => $event)
    {
        // Find any replies for this event
        $repliesEvent = $repliesCollection->find(['post_id' => $event->event_id]);

        // Turn the date into a DateTime object
        $eventDate = new DateTime($event->date);

        // Formatting the date into Day Month Year
        $formattedDate = $eventDate->format('js F Y');

        echo '<form action="eventPage.php"  method="GET">';
        echo '<button class="button-large">';
        echo '<h3>' . $event->title . '</h3>';
        echo '<p>' . $event->start_time . '-' . $event->end_time . ', ' . $formattedDate . '</p>';
        echo '<p>' . $event->address . ', ' . $event->location . ', ' . $event->postcode . '</p>';
        echo '<p>£' . $event->price . '</p>';
        echo '<input type="hidden" name="id" value="' . $event->event_id . '">';
        echo '</button>';
        echo '</form>';
        echo '<form action="feedPost.php" class="form feed-input" method="POST">';
        echo '<input type="text" name="message" placeholder="Type your reply here...">';
        echo '<input type="hidden" name="id" value="' . $event->event_id . '">';
        echo '<button type="submit" class="button">Reply</button>';
        echo '</form>';
        echo '<br>';
        echo '<br>';

        foreach ($repliesEvent as $eventReply)
        {
            // Get the user data for the reply
            $eventsUserResult = $userCollection->find(['user_id' => $eventReply->poster]);
    
            foreach ($eventsUserResult as $userReply)
            {
                $replyDate = new DateTime($eventReply->timestamp);
                $formmattedReply = $replyDate->format('l, F j, Y - g:i A');
                echo '<div class="reply">';
                echo '<h2>' . $userReply->username . ' replied: </h2>';
                echo '<h3>' . $eventReply->content . '</h3>';
                echo '<p>' . $formmattedReply . '</p>';
                echo '</div>';
                echo '<br>';
            }
        }
    }
    echo '</div>';

    echo '<div class="column feed">';
    echo '<h2>Posts</h2>';
    echo '<br>';
    echo '<br>';

    foreach ($posts as $feedPost => $post)
    {
        // Get the user data for the post
        $postUserResult = $userCollection->find(['user_id' => $post->poster]);

        foreach ($postUserResult as $postUser)
        {
            // Find any replies for this post
            $repliesPost = $repliesCollection->find(['post_id' => $post->post_id]);

            // Turn the date into a DateTime object
            $postDate = new DateTime($post->timestamp);

            // Formatting the date into Day Month Year
            $formmattedDate = $postDate->format('l, F j, Y - g:i A');

            echo '<div class="post">';
            echo "<h2>" . $postUser->username . " posted: </h2>";
            echo '<h3>' . $post->content . '</h3>';
            echo '<p>' . $formmattedDate . '</p>';
            echo '</div>';

            echo '<form action="feedPost.php" class="form feed-input" method="POST">';
            echo '<input type="text" name="message" placeholder="Type your reply here...">';
            echo '<input type="hidden" name="id" value="' . $post->post_id . '">';
            echo '<button type="submit" class="button">Reply</button>';
            echo '</form>';
            echo '<br>';
            echo '<br>';

            foreach ($repliesPost as $postReply)
            {
                // Get the user data for the reply
                $postsUserResult = $userCollection->find(['user_id' => $postReply->poster]);
        
                foreach ($postsUserResult as $userReply)
                {
                    $replyDate = new DateTime($postReply->timestamp);
                    $formmattedReply = $replyDate->format('l, F j, Y - g:i A');
                    echo '<div class="reply">';
                    echo '<h2>' . $userReply->username . ' replied: </h2>';
                    echo '<h3>' . $postReply->content . '</h3>';
                    echo '<p>' . $formmattedReply . '</p>';
                    echo '</div>';
                    echo '<br>';
                }
            }
            
        }
    }
    echo '</div>';
    echo '</div>';

    echo '</div>';
    ?>
</body>
</html>