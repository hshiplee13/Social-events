<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Social Events | Booking</title>
</head>
<body>
    <?php
    include "templates/header.php";
    include "templates/database.php";

    $eventID = $_GET['id'];

    // Check if the user is logged in
    if ($_SESSION['logged_in'] == false)
    {
        header('Location: signIn.php');
    }
    ?>
    <br>

    <div class="page">
        <h1>Payment Information</h1>
        <br>

        <div class="payment-details">
            <?php
            $eventCollection = $db->eventsData;

            // Get the event details from the events collection
            $eventResult = $eventCollection->find(['event_id' => $eventID]);

            foreach ($eventResult as $event)
            {
                // Save the price in a variable
                $price = $event->price;

                // Turn the date into a DateTime object
                $eventDate = new DateTime($event->date);

                // Formatting the date into Day Month Year
                $formattedDate = $eventDate->format('js F Y');

                echo '<h3>' . $event->title . '</h3>';
                echo '<p><b>' . $event->start_time . ' - ' . $event->end_time . ' on ' . $formattedDate . '</b></p>';
                echo '<p><b>Address: ' . $event->address . ', ' . $event->location . ', ' . $event->postcode . '</b></p>';
                echo '<p><b>Price: £' . $price . '</b></p>';
            }
            ?>
        </div>
    </div>
    <br>
    <hr>

    <div class="page">
            <!-- Check if the event is free -->
            <?php if ($price == 0): ?>
                <br>
                <form action="booked.php" method="POST">
                    <button class="button">Purchase Ticket</button>
                    <?php echo '<input type="hidden" name="free-id" value="' . $eventID . '">'; ?>
                </form>
                <br>
                <br>
                <br>
                <br>
                <br>
                <br>
                <br>
            <?php else: ?>
                <?php
                $paymentCollection = $db->userPurchaseData;

                // Retrieve any saved payment methods
                $paymentResult = $paymentCollection->find(['user_id' => $_SESSION['id']]);

                foreach ($paymentResult as $payment)
                {
                    // Decode the card number and the variables to decrypt it
                    $decodedCard = base64_decode($payment->card_number);
                    $nonce = base64_decode($payment->number_code);
                    $secretKey = base64_decode($payment->number_code2);

                    // Decrypt the card number
                    $decryptedCard = sodium_crypto_secretbox_open($decodedCard, $nonce, $secretKey);

                    // Get the last four digits of the card
                    $cardNumber = substr($decryptedCard, -4);

                    echo '<form action="booked.php" method="POST">';
                    echo '<button class="button-large">';
                    echo '<h3>' . $payment->card_name . '</h3>';
                    echo '<p>**** **** **** ' . $cardNumber . '</p>';
                    echo '<p>' . $payment->address . ', ' . $payment->city . ', ' . $payment->post_code . '</p>';
                    echo '<input type="hidden" name="saved" value="' . $eventID . '">';
                    echo '</button>';
                    echo '</form>';
                    echo '<br>';
                }
                ?>

                <form action="booked.php" class="form" method="POST">
                    <h3><b>Please fill in your payment and billing information</b></h3>
                    <br>

                    <p><b>Billing Information</b></p>
                    <br>

                    <div class="row">
                        <div class="column">
                            <label for="firstname"><b>First Name</b></label>
                            <input type="text" name="firstname" placeholder="Enter your first name..." required>
                        </div>

                        <div class="column">
                            <label for="lastname"><b>Last Name</b></label>
                            <input type="text" name="lastname" placeholder="Enter your last name..." required>
                        </div>
                    </div>
                    <br>

                    <div class="row">
                        <div class="column">
                            <label for="address"><b>Address</b></label>
                            <input type="text" name="address" placeholder="Enter your address..." required>
                        </div>

                        <div class="column">
                            <label for="city"><b>City</b></label>
                            <input type="text" name="city" placeholder="Enter your city..." required>
                        </div>

                        <div class="column">
                            <label for="postcode"><b>Post Code</b></label>
                            <input type="text" name="postcode" placeholder="Enter your postcode..." required>
                        </div>
                    </div>
                    <br>

                    <p><b>Payment Information</b></p>
                    <br>

                    <label for="cardname"><b>Card Name</b></label>
                    <input type="text" name="cardname" placeholder="Enter your card name..." required>
                    <br>
                    <br>

                    <div class="row">
                        <div class="column">
                            <label for="cardnumber"><b>Card Number</b></label>
                            <input type="number" name="cardnumber" placeholder="Enter card number..." required>
                        </div>

                        <div class="column">
                            <label for="expirydate"><b>Expiry Date</b></label>
                            <input type="date" name="expirydate" required>
                        </div>

                        <div class="column">
                            <label for="cvv">CVV</label>
                            <input type="number" name="cvv" placeholder="CVV" required>
                        </div>
                    </div>
                    <br>

                    <?php
                    // Error catching 
                    // Error message for trying to save card details that are already saved
                    if (isset($_GET['error']) && $_GET['error'] == 'card')
                    {
                        echo '<p class="error">Card details already saved</p>';
                    }

                    // Error message for out of date card
                    if (isset($_GET['error']) && $_GET['error'] == 'date')
                    {
                        echo '<p class="error">Card out of date</p>';
                    }

                    // Error message for invalid security numbr length
                    if (isset($_GET['error']) && $_GET['error'] == 'cvv')
                    {
                        echo '<p class="error">Invalid CVV</p>';
                    }

                    // Error message for invalid card number length
                    if (isset($_GET['error']) && $_GET['error'] == 'length')
                    {
                        echo '<p class="error">Invalid Card Number</p>';
                    }

                    echo '<button class="button" name="id" value="' . $eventID . '">Pay & Save Details</button>';

                    echo '<button class="button" name="payed" value="' . $eventID . '">Pay</button>';
                    ?>
                </form>
            <?php endif; ?>
    </div>
</body>
</html>