$(document).ready(function() {
    // Get the date input
    const date = document.getElementById('date');

    // Get the clear date button
    const clear = document.getElementById('clear');

    // When the user clicks the clear date button, set the date value to empty
    clear.addEventListener('click', function (event) {
        event.preventDefault();
        date.value = '';
    });
});