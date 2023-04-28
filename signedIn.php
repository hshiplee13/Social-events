<?php
include "templates/database.php";

// Creating a variable of the userData collection
$userCollection = $db->userData;

// Checking the username input in the database
$username_check = $userCollection->find(['username' => $_POST['username']]);
if (count($username_check->toArray()) < 1)
{
    header('Location: signIn.php?error=username');
    exit;
}

// Retrieve the user data from the database
$userResult = $userCollection->find(['username' => $_POST['username']]);

foreach ($userResult as $user)
{
    $encrypted_password = $user->password;
    
    // Verify that the password matches
    if (password_verify($_POST['password'], $encrypted_password))
    {
        // Creating the user session
        session_start();
        $_SESSION['username'] = $user->username;
        $_SESSION['id'] = $user->user_id;
        $_SESSION['user_type'] = $user->user_type;
        $_SESSION['logged_in'] = true;
        header('Location: index.php');
    }
    else
    {
        header('Location: signIn.php?error=password');
        exit;
    }
}
?>