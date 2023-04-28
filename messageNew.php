<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Social Events | New Message</title>
</head>
<body>
    <?php
    include "templates/header.php";
    include "templates/database.php";

    echo '<div class="page2">';
    echo '<div class="title">';
    echo '<h1>New Message</h1>';

    echo '<p>Choose users to message</p>';
    echo '<form action="messaging.php" method="POST">';

    $userCollection = $db->userData;
    $followingCollection = $db->following;

    // Get all the user data from the userData Collection
    $userResult = $userCollection->find([]);

    foreach ($userResult as $user)
    {
        // Find users the session user follows
        $followingResult = $followingCollection->find([
            '$and' => [
                ['user_id' => $user->user_id],
                ['follower_id' => $_SESSION['id']]
            ]
        ]);

        foreach ($followingResult as $following)
        {
            // Check if the user follows the session user back
            $followerResult = $followingCollection->find([
                '$and' => [
                    ['user_id' => $_SESSION['id']],
                    ['follower_id' => $user->user_id]
                ]
            ]);

            foreach ($followerResult as $follower)
            {
                echo '<label><input type="checkbox" name="id[]" value="' . $user->user_id . '">' . $user->username . '</label>';
                echo '<br>';
            }
        }
    }

    // Add the session id to the form POST
    echo '<input type="hidden" name="id[]" value="' . $_SESSION['id'] . '">';
    echo '<br>';

    echo '<button type="submit" class="button">Create Message</button>';
    echo '</form>';
    echo '</div>';
    echo '</div>';
    ?>
</body>
</html>