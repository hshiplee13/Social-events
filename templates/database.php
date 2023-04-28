<?php
// Connecting to my MongoDB database through the MongoDB PHP Driver
require 'vendor/autoload.php';
$conn = new MongoDB\Client('mongodb+srv://hshiplee13:shipleeHenry02@socialevents.qne8ndp.mongodb.net/test');
$db = $conn->socialEvents;

// Setting the default timezone
date_default_timezone_set('Europe/London');
?>