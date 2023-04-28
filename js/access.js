$(document).ready(function() {
    // Get the private and public radio buttons
    var private = document.getElementById('private');
    var public = document.getElementById('public');

    // Get the friend list element
    var friends = document.getElementById('friend-list');

    // Check if the private radio button has been clicked
    private.addEventListener('click', function() {
        if(private.checked)
        {
            friends.style.display = 'block';
        }
        else
        {
            friends.style.display = 'none';
        }
    });

    // Check if the public radio button has been clicked
    public.addEventListener('click', function() {
        friends.style.display = 'none';
    })
});