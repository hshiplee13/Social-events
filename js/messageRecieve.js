$(document).ready(function() {
    // Create an array to store old messages so there are no duplicate messages
    var oldMessages = [];

    function recieveMessages()
    {
        // Get the user IDs
        var form = document.getElementById('message-form');
        var id = form.elements['id'].value;

        $.ajax({
            url: 'messageRecieve.php',
            data: { userID: id },
            dataType: 'json',
            success: function(data)
            {
                for (var i = 0; i < data.length; i++)
                {
                    var message = data[i];
                    
                    // Create a key to store in the oldMessages object
                    var key = message.sender + message.message + message.timestamp;

                    // create the HTML for the message
                    var html = '<div>';
                    html += '<p>' + message.sender + '</p>';
                    html += '<p>' + message.message + '</p>';
                    html += '<p>' + message.timestamp + '</p>';
                    html += '</div>';
                    html += '<br>';

                    // Check if the message is an old message
                    if (!oldMessages.includes(key))
                    {
                        // Append to the feed and array if it isn't
                        $('#message-feed').append(html);
                        oldMessages.push(key);
                    }
                }
            }
        });
    }

    setInterval(recieveMessages, 1000);
});