$(document).ready(function() {
    // Get the location element from the page
    var locationFilter = document.getElementById('location-filter');
    var location = locationFilter.elements['location'].value;

    // Check if the location filter has recieved an input
    locationFilter.addEventListener('input', function() {
        // Set the location variable to the value of the input
        location = locationFilter.elements['location'].value;

        // Call the function
        searchFilter();
    });

    // Get the date element from the page
    var dateFilter = document.getElementById('date-filter');
    var date = dateFilter.elements['date'].value;
    var clear = dateFilter.elements['clear'];

    // Check if the user is clearing the date filter
    clear.addEventListener('click', function(event) {
        // Prevent the page from reloading
        event.preventDefault();
        date = 'All';
        searchFilter();
    });

    // Check if the date filter has recieved an input
    dateFilter.addEventListener('input', function() {
        date = dateFilter.elements['date'].value;
        searchFilter();
    });

    // Get the category element from the page
    var categoryFilter = document.getElementById('category-filter');
    var category = categoryFilter.elements['category'].value;
    
    // Check if the category filter has recieved an input
    categoryFilter.addEventListener('input', function() {
        category = categoryFilter.elements['category'].value;
        searchFilter();
    });

    function searchFilter()
    {
        $.ajax({
            url: 'allEvents.php',
            method: 'GET',
            data: { location: location, date: date, category: category },
            success: function(response) {
                // Create the new page URL
                var url = "allEvents.php?location=" + location + "&date=" + date + "&category=" + category;

                // Replace the URL
                window.history.replaceState(null, null, url);

                // Update only the events display half of the page, and not update the filters
                var events = $(response).find('#events-display').html();
                $('#events-content').html(events);
            }
        })
    }
});