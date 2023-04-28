$(document).ready(function() {
    // Get the message form element once the message has been submitted
    document.getElementById('message-form').addEventListener('submit', function(event) {
       // Prevent the window from reloading
        event.preventDefault();

        // Get the id and message values
        var form = document.getElementById('message-form');
        var recipient = form.elements['id'].value;
        var message = form.elements['message'].value;

        // Send the data using AJAX
        $.ajax({
            url: 'messageSend.php',
            type: 'POST',
            data: { recipient: recipient, message: message },
            dataType: 'text',
            success: function(data)
            {
                // Reset the message input when sent successfully
                form.reset();
            }
        });
    })
});