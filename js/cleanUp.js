$(document).ready(function() {
    function cleanUp() {
        $.ajax({
            url: 'cleanUp.php',
            success: function()
            {
                // Call the cleanUp.php page to delete out of date events
            }
        });
    }

    // Run the function every hour
    setInterval(cleanUp, 3600000)
})