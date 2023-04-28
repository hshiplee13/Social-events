$(document).ready(function() {
    // Check if a user types in the search bar
    $('#searchInput').keyup(function() {
        var searchTerm = $(this).val();

        // Check if the search term is greater than 2 characters
        if (searchTerm.length > 2)
        {
            search(searchTerm);
        }
        else
        {
            // If it isn't then keep the results empty
            $('#searchResults').empty();
        }


        function search(searchTerm)
        {
            $.ajax({
                url: 'search.php',
                type: 'POST',
                data: { searchTerm: searchTerm },
                dataType: 'json',
                success: function(data)
                {
                    $('#searchResults').empty();

                    for (var i = 0; i < data.length; i++)
                    {
                        var searchResult = data[i];
                        
                        // Create the html of the search result
                        var result = '<div>';

                        // Create a user link if the result is of type user
                        if (searchResult.type == 'user')
                        {
                            result += '<a href="profile.php?id=' + searchResult.user_id + '">' + searchResult.username + '</a>';
                        }

                        // Create a event link if the result is of type event
                        if (searchResult.type == 'event')
                        {
                            result += '<a href="eventPage.php?id=' + searchResult.event_id + '">' + searchResult.title + '</a>';
                        }

                        // Create a location link if the result is of type location
                        if (searchResult.type == 'location')
                        {
                            result += '<a href="allEvents.php?location=' + searchResult.location + '&date=All&category=All">' + searchResult.location + '</a>';
                        }

                        result += '</div>';

                        $('#searchResults').append(result);
                    }
                }
            });
        }
    });
});