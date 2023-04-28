<?php
session_start();
include "templates/database.php";

$ticketCollection = $db->purchasedEvents;
$eventCollection = $db->eventsData;

// Creating a unique reference number
$referenceNumber = time() . rand(1, 9999);

// Check if the session user wants to save their details
if (isset($_POST['id']))
{
    $eventID = $_POST['id'];

    $paymentCollection = $db->userPurchaseData;

    // Creating the error messages
    // Searching the database to see if the card details are already saved
    $detailsCheck = $paymentCollection->find(['card_name' => $_POST['cardname']]);
    if (count($detailsCheck->toArray()) > 0)
    {
        header('Location: booking.php?id=' . $eventID . '&error=card');
        exit;
    }

    // Creating a DateTime object of the expiry date
    $expiryTime = new DateTime($_POST['expirydate']);

    // Creating a DateTime object for the current time
    $currentTime = new DateTime();

    // Comparing the DateTime objects to see if the expiry date is in the past
    if ($expiryTime < $currentTime)
    {
        header('Location: booking.php?id=' . $eventID . '&error=date');
        exit;
    }

    // Checking if the CVV length is wrong
    if (strlen($_POST['cvv']) != 3)
    {
        header('Location: booking.php?id=' . $eventID . '&error=cvv');
        exit;
    }

    // Checking if the card number length is wrong
    if (strlen($_POST['cardnumber']) != 16)
    {
        header('Location: booking.php?id=' . $eventID . '&error=length');
        exit;
    }

    // Creating the ticket document
    $ticketDocument = array(
        "event_id" => $eventID,
        "user_id" => $_SESSION['id'],
        "reference_number" => $referenceNumber
    );

    // Inserting the ticket into the database
    $ticketCollection->insertOne($ticketDocument);

    $cardNumber = $_POST['cardnumber'];
    $cvv = $_POST['cvv'];

    // Creating a secret key and number once key to encrypt the CVV and card number
    $secretKey = sodium_crypto_secretbox_keygen();
    $nonce = random_bytes(SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);

    // Encrypting the card number and the CVV number
    $encryptedCard = sodium_crypto_secretbox($cardNumber, $nonce, $secretKey);
    $encryptedCVV = sodium_crypto_secretbox($cvv, $nonce, $secretKey);

    // Encoding the card number, CVV, secret key and the number once key to be inserted into the database
    $encodedCard = base64_encode($encryptedCard);
    $encodedCVV = base64_encode($encryptedCVV);
    $encodedNonce = base64_encode($nonce);
    $encodedKey = base64_encode($secretKey);

    // Creating the payment and billing information document
    $paymentDocument = array(
        "user_id" => $_SESSION['id'],
        "first_name" => $_POST['firstname'],
        "last_name" => $_POST['lastname'],
        "address" => $_POST['address'],
        "city" => $_POST['city'],
        "post_code" => $_POST['postcode'],
        "card_name" => $_POST['cardname'],
        "card_number" => $encodedCard,
        "expiry_date" => $_POST['expirydate'],
        "cvv" => $encodedCVV,
        "number_code" => $encodedNonce,
        "number_code2" => $encodedKey
    );

    // Inserting the payment and billing information into the database
    $paymentCollection->insertOne($paymentCollection);

    // Lowering the capacity as a ticket has been sold
    $eventCollection->updateOne(['event_id' => $eventID], ['$inc' => ['capacity' => -1]]);

    header("Location: profile.php");
}
else
{
    $eventID = $_POST['saved'] ?? $_POST['free-id'] ?? $_POST['payed'];

    if (isset($_POST['cardnumber']) && isset($_POST['expirydate']) && isset($_POST['cardname']) && isset($_POST['cvv']))
    {
        $paymentCollection = $db->userPurchaseData;

        // Creating the error messages
        // Searching the database to see if the card details are already saved
        $detailsCheck = $paymentCollection->find(['card_name' => $_POST['cardname']]);
        if (count($detailsCheck->toArray()) > 0)
        {
            header('Location: booking.php?id=' . $eventID . '&error=card');
            exit;
        }
    
        // Creating a DateTime object of the expiry date
        $expiryTime = new DateTime($_POST['expirydate']);
    
        // Creating a DateTime object for the current time
        $currentTime = new DateTime();
        
        // Comparing the DateTime objects to see if the expiry date is in the past
        if ($expiryTime < $currentTime)
        {
            header('Location: booking.php?id=' . $eventID . '&error=date');
            exit;
        }

        // Checking if the CVV length is wrong
        if (strlen($_POST['cvv']) != 3)
        {
            header('Location: booking.php?id=' . $eventID . '&error=cvv');
            exit;
        }

        // Checking if the card number length is wrong
        if (strlen($_POST['cardnumber']) != 16)
        {
            header('Location: booking.php?id=' . $eventID . '&error=length');
            exit;
        }
    }

    // Creating the ticket document
    $ticketDocument = array(
        "event_id" => $eventID,
        "user_id" => $_SESSION['id'],
        "reference_number" => $referenceNumber
    );

    $ticketCollection->insertOne($ticketDocument);

    // Lowering the capacity as a ticket has been sold
    $eventCollection->updateOne(['event_id' => $eventID], ['$inc' => ['capacity' => -1]]);

    header("Location: profile.php");
}
?>