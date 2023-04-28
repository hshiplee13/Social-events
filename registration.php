<?php
include "templates/database.php";

// Creating a variable of the userData collection
$userCollection = $db->userData;

// Creating a unique ID for each user
$userID = uniqid('user_');

// Encrypting the password
$password = $_POST['password'];
$password_encrypt = password_hash($password, PASSWORD_DEFAULT);

// Checking the username input in the database
$username_check = $userCollection->find(['username' => $_POST['username']]);
if (count($username_check->toArray()) > 0)
{
    header('Location: register.php?error=username');
    exit;
}

// Checking the email input in the database
$email_check = $userCollection->find(['email' => $_POST['email']]);
if (count($email_check->toArray()) > 0)
{
    header('Location: register.php?error=email');
    exit;
}

// Validate the email
if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
{
    header('Location: register.php?error=email_verification');
    exit;
}

// Checking the user is at least 18 years old
$dob_check = new DateTime($_POST['dob']);
$current_date = new DateTime();
$user_age = $current_date->diff($dob_check)->y; // Comparing current date with user date and returning in years
if ($user_age < 18)
{
    header('Location: register.php?error=dob');
    exit;
}

// Creating the user document to be inserted
$userDocument = array(
    "user_id" => $userID,
    "username" => $_POST['username'],
    "email" => $_POST['email'],
    "dob" => $_POST['dob'],
    "phone" => $_POST['phone'],
    "password" => $password_encrypt,
    "user_type" => "standard"
);

// Inserting the document into the userData collection
$userCollection->insertOne($userDocument);

// Creating the user session
session_start();
$_SESSION['username'] = $_POST['username'];
$_SESSION['id'] = $userID;
$userCheck = $userCollection->find(['username' => $_POST['username']]);
foreach ($userCheck as $user)
{
    $_SESSION['user_type'] = $user->user_type;
}
$_SESSION['logged_in'] = true;

header('Location: index.php')
?>