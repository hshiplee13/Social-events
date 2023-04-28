<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Social Events | Edit Profile</title>
</head>
<body>
    <?php
    include "templates/header.php";
    include "templates/database.php";

    $userCollection = $db->userData;

    // Find the user data for the session user
    $userResult = $userCollection->find(['user_id' => $_SESSION['id']]);

    foreach ($userResult as $user)
    {
        echo '<form action="editedProfile.php" method="POST" class="form">';
        echo '<h1>Edit Profile Details</h1>';
        echo '<br>';

        // Error messages
        // Error if the username is taken
        if (isset($_GET['error']) && $_GET['error'] == 'username')
        {
            echo '<p class="error">Username already in use</p>';
        }

        // Error if email is taken
        if (isset($_GET['error']) && $_GET['error'] == 'email')
        {
            echo '<p class="error">Email already in use</p>';
        }

        // Error if the email is not valid
        if (isset($_GET['error']) && $_GET['error'] == 'email_verification')
        {
            echo '<p class="error">Please use a valid email</p>';
        }

        echo '<label for="username"><b>Username</b></label>';
        echo '<input type="text" name="username" placeholder="' . $user->username . '"';
        echo '<br>';
        echo '<br>';

        echo '<label for="email"><b>Email</b></label>';
        echo '<input type="text" name="email" placeholder="' . $user->email . '">';
        echo '<br>';
        echo '<br>';

        echo '<label for="phone"><b>Phone Number</b></label>';
        echo '<input type="tel" name="phone" class="input-number" placeholder="' . $user->phone . '">';
        echo '<br>';
        echo '<br>';

        echo '<label for="password"><b>Password</b></label>';
        echo '<input type="password" name="password">';
        echo '<br>';
        echo '<br>';

        echo '<button type="submit" class="button">Submit</button>';
        echo '<br>';
        echo '<br>';

        echo '<p>Or</p>';
        echo '<br>';

        echo '<button type="submit" name="deleteProfile" value="delete" class="button">Delete Profile</button>';
        echo '</form>';
    }
    ?>
</body>
</html>