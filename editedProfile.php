<?php
session_start();
include "templates/database.php";

$userCollection = $db->userData;
$eventCollection = $db->eventsData;
$messagesCollection = $db->messages;

// Check if the user is deleting their profile
if(isset($_POST['deleteProfile']) && $_POST['deleteProfile'] == 'delete')
{
    $paymentCollection = $db->userPurchaseData;
    $followingCollection = $db->following;
    $purchaseCollection = $db->purchasedEvents;
    $postsCollection = $db->posts;
    $repliesCollection = $db->replies;

    // Delete the users replies
    $repliesDelete = $repliesCollection->deleteMany(['poster' => $_SESSION['id']]);

    // Delete the users posts
    $postsDelete = $postsCollection->deleteMany(['poster' => $_SESSION['id']]);

    // Delete the users messages
    $messageDelete = $messageCollection->deleteMany(['sender' => $_SESSION['username']]);

    // Delete the users events
    $eventsDelete = $eventCollection->deleteMany(['created_by' => $_SESSION['username']]);

    // Delete the users payment and billing information
    $paymentDelete = $paymentCollection->deleteMany(['user_id' => $_SESSION['id']]);

    // Delete the users following
    $followDelete = $followingCollection->deleteMany([
        '$or' => [
            ['user_id' => $_SESSION['id']],
            ['follower_id' => $_SESSION['id']]
        ]
    ]);

    // Delete the users tickets
    $purchaseDelete = $purchaseCollection->deleteMany(['user_id' => $_SESSION['id']]);

    // Delete the user details
    $userDelete = $userCollection->deleteOne(['user_id' => $_SESSION['id']]);

    // Delete the session
    session_unset();
    session_destroy();
    
    header('Location: index.php');
    exit;
}
else
{
    // Get the users current details
    $userResult = $userCollection->find(['user_id' => $_SESSION['id']]);

    foreach ($userResult as $user)
    {
        $username = $user->username;
        $email = $user->email;
        $dob = $user->dob;
        $phone = $user->phone;
        $password = $user->password;
    }

    // If the user has changed their username
    if(isset($_POST['username']) && $_POST['username'] != null)
    {
        // Check if the username is already taken
        $usernameCheck = $userCollection->find(['username' => $_POST['username']]);

        // Return the error code
        if (count($usernameCheck->toArray()) > 0) 
        {
            header('Location: editProfile.php?error=username');
            exit;
        }
        else
        {
            $username = $_POST['username'];

            // Update the username in the messages collection
            $messagesCollection->updateMany(
                ['sender' => $_SESSION['username']],
                ['$set' => [
                    'sender' => $username
                ]]
            );

            // Update the username in the events collection
            $eventCollection->updateMany(
                ['created_by' => $_SESSION['username']],
                ['$set' => [
                    'created_by' => $username
                ]]
            );
        }
    }

    // Check if the email has been updated
    if (isset($_POST['email']) && $_POST['email'] != null)
    {
        // Check if the email is already taken
        $emailCheck = $userCollection->find(['email' => $_POST['email']]);

        // Return the error code
        if (count($emailCheck->toArray()) > 0) 
        {
            header('Location: editProfile.php?error=email');
            exit;
        }

        // If the email isnt valid return the error code
        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) 
        {
            header('Location: editProfile.php?error=email_verification');
            exit;
        }
        else
        {
            $email = $_POST['email'];
        }
    }

    // Check if the phone number has been updated
    if (isset($_POST['phone']) && $_POST['phone'] != null) 
    {
        $phone = $_POST['phone'];
    }

    // Check if the password has been updated
    if (isset($_POST['password']) && $_POST['password'] != null) 
    {
        $hashedPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $password = $hashedPassword;
    }

    // Update the user details
    $userCollection->updateOne(
        ['user_id' => $_SESSION['id']],
        ['$set' => [
            'username' => $username,
            'email' => $email,
            'dob' => $dob,
            'phone' => $phone,
            'password' => $password
        ]]
    );

    // Update the session username
    $_SESSION['username'] = $username;

    header('Location: profile.php');
}
?>