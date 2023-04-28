<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Social Events | Following</title>
</head>
<body>
    <?php
    include "templates/header.php";
    include "templates/database.php";

    $followingCollection = $db->following;
    $userCollection = $db->userData;

    // Check if the session user is checking followers or following
    if (isset($_GET['action']) && $_GET['action'] == 'followers')
    {
        echo '<div class="page">';
        echo '<h1>Followers</h1>';
        echo '<br>';
        echo '<br>';

        // Retrieve the users that follow the session user
        $followerResult = $followingCollection->find(['user_id' => $_GET['id']]);

        // Count the followers
        $followerCheck = $followerResult->toArray();
        if (count($followerCheck) < 1)
        {
            echo '<h2>You have no followers!</h2>';
        }
        else
        {
            foreach ($followerCheck as $followers)
            {
                // Get the follower details from the user collection
                $userResult = $userCollection->find(['user_id' => $followers->follower_id]);

                foreach ($userResult as $user)
                {
                    echo '<form action="profile.php" method="GET">';
                    echo '<button class="button-large">';
                    echo '<h3>' . $user->username . '</h3>';
                    echo '<input type="hidden" name="id" value="' . $user->user_id . '">';
                    echo '</button>';
                    echo '</form>';
                }
            }
        }

        echo '</div>';
    }
    else if (isset($_GET['action']) && $_GET['action'] == 'following')
    {
        echo '<div class="page">';
        echo '<h1>Following</h1>';
        echo '<br>';
        echo '<br>';

        // Retrieve the users that the user is following
        $followingResult = $followingCollection->find(['follower_id' => $_GET['id']]);

        // Count the number of users they follow
        $followingCheck = $followingResult->toArray();
        if (count($followingCheck) < 1)
        {
            echo '<h2>You dont follow anyone!</h2>';
        }
        else
        {
            foreach ($followingCheck as $following)
            {
                // Get the following details from the user collection
                $userResult = $userCollection->find(['user_id' => $following->user_id]);

                foreach ($userResult as $user)
                {
                    echo '<form action="profile.php" method="GET">';
                    echo '<button class="button-large">';
                    echo '<h3>' . $user->username . '</h3>';
                    echo '<input type="hidden" name="id" value="' . $user->user_id . '">';
                    echo '</button>';
                    echo '</form>';
                }
            }
        }
    }
    ?>
</body>
</html>