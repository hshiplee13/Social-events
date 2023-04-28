<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Social Events | Registration</title>
</head>
<body>
    <?php include "templates/header.php"; ?>

    <form action="registration.php" class="form" method="POST">
        <h1>Register</h1>
        <p>Please fill in the form to create an account</p>
        <br>

        <?php
        // Error messages & verification
        if (isset($_GET['error']) && $_GET['error'] == 'username')
        {
            echo '<p class="error">Username already in use</p>';
        }

        if (isset($_GET['error']) && $_GET['error'] == 'email')
        {
            echo '<p class="error">Email already in use</p>';
        }

        if (isset($_GET['error']) && $_GET['error'] == 'email_verification')
        {
            echo '<p class="error">Please use a valid email</p>';
        }

        if (isset($_GET['error']) && $_GET['error'] == 'dob')
        {
            echo '<p class="error">You need to be at least 18 years old to register</p>';
        }
        ?>

        <label for="username"><b>Username</b></label>
        <input type="text" name="username" placeholder="Enter username..." required>
        <br>
        <br>

        <label for="email"><b>Email</b></label>
        <input type="email" name="email" placeholder="Enter email..." required>
        <br>
        <br>

        <label for="dob"><b>Date of Birth</b></label>
        <input type="date" name="dob" required>
        <br>
        <br>

        <label for="phone"><b>Phone Number</b></label>
        <input type="tel" name="phone" placeholder="Enter phone number..." required>
        <br>
        <br>

        <label for="password"><b>Password</b></label>
        <input type="password" name="password" placeholder="Enter password..." required>
        <br>
        <br>

        <a href="signIn.php" class="link">Already got an account?</a>
        <br>
        <br>

        <button type="submit" class="button">Register</button>
    </form>
</body>
</html>