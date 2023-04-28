<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Social Events | Inbox</title>
</head>
<body>
    <?php
    include "templates/header.php";
    include "templates/database.php";

    echo '<div class="page2">';
    echo '<div class="title">';
    echo '<h1>Inbox<h1>';
    echo '</div>';
    echo '<br>';

    echo '<form action="messageNew.php">';
    echo '<button type="submit" class="button-large">';
    echo '<h3>New Message</h3>';
    echo '</button>';
    echo '</form>';

    $messageCollection = $db->messages;
    $userCollection = $db->userData;

    // Find chats that the session user is part of
    $messageResult = $messageCollection->find([
        '$or' => [
            ['recipient' => new MongoDB\BSON\Regex($_SESSION['id'], 'i')], // Match with recipients that contain the session user id using regular expression
            ['sender' => $_SESSION['username']]
        ]
    ]);

    // Create an associative array to store the seperate chats
    $chats = [];

    foreach ($messageResult as $message)
    {
        $chatID = $message->recipient;

        // Check if the message is part of a group chat
        $group = ',';
        if (strpos($chatID, $group) !== false)
        {
            // Seperate the recipients string into an array of IDs
            $groupRecipients = explode($group, $chatID);

            // Create an array to store the usernames of the group members
            $groupUsernames = [];

            foreach ($groupRecipients as $groupMember)
            {
                // Get the user data for each group member
                $groupResult = $userCollection->find(['user_id' => $groupMember]);

                foreach ($groupResult as $member)
                {
                    $groupUsernames[] = $member->username;
                }
            }

            // Turn the group usernames array into a string
            $groupName = implode(", ", $groupUsernames);

            // Check if the associative array already contains the chat
            if (!isset($chats[$groupName]))
            {
                $chats[$groupName]['name'] = $groupName;
                $chats[$groupName]['id'] = $chatID;
            }

            // Create a timestamp variable for the current saved message
            $currentTimestamp = $chats[$groupName]['timestamp'] ?? null;

            $messageTime = new DateTime($message->timestamp);
            $currentTime = new DateTime($currentTimestamp);

            // Save message details if its newer or there isn't already a message saved
            if ($currentTimestamp == null || $messageTime > $currentTime)
            {
                $chats[$groupName]['message'] = $message->message;
                $chats[$groupName]['timestamp'] = $message->timestamp;
            }
        }
        else
        {
            // Find the user data for individual chats
            $userResult = $userCollection->find([
                '$or' => [
                    ['user_id' => $message->recipient],
                    ['username' => $message->sender]
                ]
            ]);

            foreach ($userResult as $user)
            {
                // Check if the user data is the session user
                if ($user->user_id != $_SESSION['id'])
                {
                    // Create an array for the user ID
                    $userID = [];

                    $userID[] = $user->user_id;

                    // Check if the associative array already contains the chat
                    if (!isset($chats[$user->username]))
                    {
                        $chats[$user->username]['name'] = $user->username;
                        $chats[$user->username]['id'] = implode(',', $userID);
                    }

                    // Create a timestamp variable for the current saved message
                    $currentTimestamp = $chats[$user->username]['timestamp'] ?? null;

                    $messageTime = new DateTime($message->timestamp);
                    $currentTime = new DateTime($currentTimestamp);

                    // Save message details if its newer or there isn't already a message saved
                    if ($currentTimestamp == null || $messageTime > $currentTime)
                    {
                        $chats[$user->username]['message'] = $message->message;
                        $chats[$user->username]['timestamp'] = $message->timestamp;
                    }
                }
            }
        }
    }

    // Create the chat button
    foreach ($chats as $inbox => $chat)
    {
        echo '<form action="messaging.php" method="POST">';
        echo '<button type="submit" name="id[]" value="' . $chat['id'] . '" class="button-large">';
        echo '<h3>' . $chat['name'] . '</h3>';
        echo '<p>' . $chat['message'] . '</p>';
        echo '<p>' . $chat['timestamp'] . '</p>';
        echo '</button>';
        echo '</form>';
    }

    echo '</div>';
    ?>
</body>
</html>