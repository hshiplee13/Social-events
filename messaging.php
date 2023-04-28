<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="js/messageSend.js"></script>
    <script src="js/messageRecieve.js"></script>
    <title>Social Events | Messaging</title>
</head>
<body>
    <?php
    include "templates/header.php";
    include "templates/database.php";

    echo '<div class="page2">';

    $userCollection = $db->userData;

    if (isset($_POST['id']))
    {
        $groupID = $_POST['id'];

        // Create an array to hold the ids
        $chatID = [];

        foreach ($groupID as $id)
        {
            $comma = ',';

            // Check if the id has a comma in it
            // If it does split it where the comma is and add it to the array
            // If it doesn't add it straight to the array
            if (strpos($id, $comma) !== false)
            {
                $splitID = explode($comma, $id);

                foreach ($splitID as $split)
                {
                    $chatID[] = $split;
                }
            }
            else
            {
                $chatID[] = $id;
            }
        }

        // Check how many IDs are in the array
        if (count($chatID) == 1 || count($chatID) == 2 && in_array($_SESSION['id'], $chatID))
        {
            $userID = $chatID[0];

            // Get the user information for individual chat
            $userResult = $userCollection->find(['user_id' => $userID]);

            foreach ($userResult as $user)
            {
                echo '<div class="title">';
                echo '<h1>' . $user->username . '</h1>';
                echo '</div>';
                echo '<br>';
                echo '<br>';

                echo '<div class="messages">';
                echo '<div id="message-feed"></div>';
                echo '<br>';
                echo '<br>';
                echo '<br>';
                echo '<br>';
                echo '<br>';
                echo '<br>';
                echo '<br>';
                echo '<br>';
                echo '<br>';

                echo '<div class="form">';
                echo '<form action="#" id="message-form" method="POST">';
                echo '<div class="row">';
                echo '<div class="column">';
                echo '<input type="text" id="message" placeholder="Type your message here...">';
                echo '</div>';
                
                echo '<div class="column">';
                echo '<input type="hidden" id="id" value="' . $userID . '">';
                echo '<button type="submit" class="button">Send</button>';
                echo '</div>';
                echo '</div>';
                echo '</form>';

                echo '</div>';
            }
        }
        else
        {
            // Create an array for the group members usernames
            $usernames = [];

            foreach ($chatID as $id)
            {
                // Get the user details for each group member
                $groupResult = $userCollection->find(['user_id' => $id]);

                foreach ($groupResult as $member)
                {
                    $usernames[] = $member->username;
                }
            }

            $groupName = implode(', ', $usernames);

            echo '<div class="title">';
            echo '<h1>' . $groupName . '</h1>';
            echo '</div>';
            echo '<br>';
            echo '<br>';

            echo '<div class="messages">';
            echo '<div id="message-feed"></div>';
            echo '<br>';
            echo '<br>';
            echo '<br>';
            echo '<br>';
            echo '<br>';
            echo '<br>';
            echo '<br>';
            echo '<br>';
            echo '<br>';

            echo '<div class="form">';
            echo '<form action="#" id="message-form" method="POST">';
            echo '<div class="row">';
            echo '<div class="column">';
            echo '<input type="text" id="message" placeholder="Type your message here...">';
            echo '</div>';

            echo '<div class="column">';
            echo '<input type="hidden" id="id" value="' . implode(',', $chatID) . '">';
            echo '<button type="submit" class="button">Send</button>';
            echo '</div>';
            echo '</div>';
            echo '</form>';

            echo '</div>';
        }
    }

    echo '</div>';
    echo '</div>';
    ?>
</body>
</html>