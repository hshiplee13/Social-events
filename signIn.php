<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Social Events | Sign In</title>
</head>
<body>
    <?php include "templates/header.php"; ?>

    <form action="signedIn.php" class="form" method="POST">
        <h1>Sign In</h1>
        <p>Please enter your account details</p>
        <br>

        <?php
        // Error messages
        if (isset($_GET['error']) && $_GET['error'] == 'username')
        {
            echo '<p class="error">Username not recognised</p>';
        }

        if (isset($_GET['error']) && $_GET['error'] == 'password')
        {
            echo '<p class="error">Password not recognised</p>';
        }
        ?>

        <label for="username"><b>Username</b></label>
        <input type="text" name="username" placeholder="Enter your username..." required>
        <br>
        <br>

        <label for="password"><b>Password</b></label>
        <input type="password" name="password" placeholder="Enter your password...">
        <br>
        <br>

        <a href="register.php" class="link">Not registered yet?</a>
        <br>
        <br>

        <button type="submit" class="button">Sign In</button>
    </form>
</body>
</html>