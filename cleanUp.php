<?php
include "templates/database.php";

$eventCollection = $db->eventsData;
$purchaseCollection = $db->purchasedEvents;

// Find all events in the database
$eventResult = $eventCollection->find([]);

foreach ($eventResult as $event)
{
    // Compare DateTime objects to see if the event is out of date
    $eventDate = new DateTime($event->date);
    $currentDate = new DateTime();
    if ($eventDate < $currentDate)
    {
        // Delete out of date events
        $eventCollection->deleteOne(['event_id' => $event->event_id]);

        // Find all tickets in the database
        $purchaseResult = $purchaseCollection->find([]);

        foreach ($purchaseResult as $purchase)
        {
            // Find tickets for events that don't exist anymore and delete
            $purchasedEventResult = $eventCollection->find(['event_id' => $purchase->event_id]);

            if (count($purchasedEventResult->toArray()) < 1)
            {
                $purchaseCollection->deleteOne(['event_id' => $purchase->event_id]);
            }
        }
    }
}
?>