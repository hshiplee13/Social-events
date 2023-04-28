<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="js/filter.js"></script>
    <script src="js/clearDate.js"></script>
    <title>Social Events | All Events</title>
</head>
<body>
    <?php
    include "templates/header.php";
    include "templates/database.php";

    $eventCollection = $db->eventsData;

    // Retrieve the filter options from the database
    //$locationFilter = $eventCollection->find(['access' => 'public']);
    $locationFilter = $eventCollection->find([
        '$or' => [
            ['access' => 'public'],
            ['created_by' => $_SESSION['username']],
            ['invites' => $_SESSION['id']]
        ]
    ]);

    // Retrieve the category options from the database
    //$categoryFilter = $eventCollection->find(['access' => 'public']);
    $categoryFilter = $eventCollection->find([
        '$or' => [
            ['access' => 'public'],
            ['created_by' => $_SESSION['username']],
            ['invites' => $_SESSION['id']]
        ]
    ]);

    // Create an array for the location options
    $locationOptions = [];

    // Create an array for the category options
    $categoryOptions = [];

    echo '<div class="page2">';
    echo '<div class="title">';
    echo '<h1>All Events<h1>';
    echo '</div>';
    echo '<br>';
    echo '<br>';

    echo '<form action="#" id="location-filter" class="filter" method="POST">';
    echo '<label for="location" class="filter-label"><b>Location</b></label>';
    echo '<select id="location" class="filter-button">';

    // Check if the location filter has been set
    if (isset($_GET['location']) && $_GET['location'] != 'All')
    {
        // Insert the chosen filter into the array
        $locationOptions[] = $_GET['location'];

        echo '<option value="' . $_GET['location'] . '">' . $_GET['location'] . '</option>';
        echo '<option value="All">All</option>';
    }
    else
    {
        echo '<option value="All">All</option>';
    }

    // Create the location options
    foreach ($locationFilter as $location)
    {
        // Check if the location is already an option
        if (!in_array($location->location, $locationOptions))
        {
            // Insert the location filter into the array
            $locationOptions[] = $location->location;

            echo '<option value="' . $location->location . '">' . $location->location . '</option>';
        }
    }
    echo '</select>';
    echo '</form>';

    echo '<form action="#" id="date-filter" class="filter" method="POST">';
    echo '<label for="date" class="filter-label"><b>Date</b></label>';
    echo '<input type="date" id="date" value="' . (isset($_GET['date']) && !empty($_GET['date']) ? $_GET['date'] : '') . '" class="filter-date">';
    echo '<button id="clear" class="button">Clear Date</button>';
    echo '</form>';

    echo '<form action="#" id="category-filter" class="filter" method="POST">';
    echo '<label for="category" class="filter-label"><b>Category</b></label>';
    echo '<select id="category" class="filter-button">';

    // Check if the category filter has been set
    if (isset($_GET['category']) && $_GET['category'] != 'All')
    {
        // Insert the chosen filter into the array
        $categoryOptions[] = $_GET['category'];

        echo '<option value="' . $_GET['category'] . '">' . $_GET['category'] . '</option>';
        echo '<option value="All">All</option>';
    }
    else
    {
        echo '<option value="All">All</option>';
    }

    // Create the category options
    foreach ($categoryFilter as $category)
    {
        if (!in_array($category->category, $categoryOptions))
        {
            // Insert the category filter into the array
            $categoryOptions[] = $category->category;

            echo '<option value="' . $category->category . '">' . $category->category . '</option>';
        }
    }
    echo '</select>';
    echo '</form>';

    echo '</div>';
    echo '<br>';
    echo '<hr>';
    echo '<br>';

    echo '<div class="page">';
    echo '<div id="events-content">';
    echo '<div id="events-display">';

    if ((!isset($_GET['location']) || $_GET['location'] == 'All') && (!isset($_GET['date']) || $_GET['date'] == 'All' || $_GET['date'] == '') && (!isset($_GET['category']) || $_GET['category'] == 'All'))
    {
        // Retrieve all public events that are not sold out
        $allResult = $eventCollection->find(
            ['$or' => [
                ['access' => 'public'],
                ['created_by' => $_SESSION['username']],
                ['invites' => $_SESSION['id']]
            ], 'capacity' => ['$gt' => 0]],
            ['sort' => ['time' => 1]])->toArray();

        foreach ($allResult as $all)
        {
            // Turn the date into a DateTime object
            $allDate = new DateTime($all->date);

            // Formatting the date into Day Month Year
            $formattedDate = $allDate->format('l, F j Y');

            echo '<form action="eventPage.php" method="GET">';
            echo '<button class="button-large">';
            echo '<h3>' . $all->title . '</h3>';
            echo '<p>' . $all->category . '</p>';
            echo '<p>' . $all->start_time . '-' . $all->end_time . ', ' . $formattedDate . '</p>';
            echo '<p>' . $all->address . ', ' . $all->location . ', ' . $all->postcode . '</p>';
            echo '<p>£' . $all->price . '</p>';
            echo '<input type="hidden" name="id" value="' . $all->event_id . '">';
            echo '</button>';
            echo '</form>';
        }
    }
    else
    {
        // Create an array to hold the set filters
        $filter = [];

        // Insert the filters into the array
        if ($_GET['location'] != 'All')
        {
            $filter['location'] = $_GET['location'];
        }
        if ($_GET['date'] != 'All' && $_GET['date'] != '')
        {
            $filter['date'] = $_GET['date'];
        }
        if ($_GET['category'] != 'All')
        {
            $filter['category'] = $_GET['category'];
        }

        // Search the events collection using the filter(s)
        $filteredResult = $eventCollection->find(
            ['$and' => [
                ['$or' => [
                    ['access' => 'public'],
                    ['created_by' => $_SESSION['username']],
                    ['invites' => $_SESSION['id']]
                ]],
                ['capacity' => ['$gt' => 0]],
                $filter
            ]]);

        foreach ($filteredResult as $event)
        {
            // Turn the date into a DateTime object
            $eventDate = new DateTime($event->date);

            // Formatting the date into Day Month Year
            $formattedDate = $eventDate->format('l, F j Y');

            echo '<form action="eventPage.php" method="GET">';
            echo '<button class="button-large">';
            echo '<h3>' . $event->title . '</h3>';
            echo '<p>' . $event->category . '</p>';
            echo '<p>' . $event->start_time . '-' . $event->end_time . ', ' . $formattedDate . '</p>';
            echo '<p>' . $event->address . ', ' . $event->location . ', ' . $event->postcode . '</p>';
            echo '<p>£' . $event->price . '</p>';
            echo '<input type="hidden" name="id" value="' . $event->event_id . '">';
            echo '</button>';
            echo '</form>';
        }
    }

    echo '</div>';
    echo '</div>';
    ?>
</body>
</html>