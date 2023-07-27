$(document).ready(function () {
    // Event handler for clicking on the checkmark icon within a table row
    $('#currencyTable').on('click', '.checkmark', function (e) {
        e.stopPropagation(); // Prevents event propagation to parent elements
        const checkbox = $(this).siblings('input[type="checkbox"]');
        checkbox.prop('checked', !checkbox.prop('checked')); // Toggles the checked property of the checkbox
    });

    // Event handler for clicking on a table row
    $('#currencyTable').on('click', 'tr', function (e) {
        const checkbox = $(this).find('input[type="checkbox"]');
        checkbox.prop('checked', !checkbox.prop('checked')); // Toggles the checked property of the checkbox
    });

    // Initialize the DataTable plugin for the currencyTable
    $('#currencyTable').DataTable({
        scrollY: '600px', // Enable vertical scrolling with a fixed height of 600px
        scrollCollapse: true, // Collapse the table when there is not enough data to fill the height
        paging: false, // Disable pagination
        dom: 'lfrt' // Define the layout of the DataTable components (l - length changing input, f - filtering input, r - processing display, t - table, t - table)
    });

    // Event handler for the click on the button
    $('#emailSubscribeButton').on('click', function (e) {
        e.preventDefault(); // Prevent the default action of the button (form submission)

        // Execute an AJAX request to the specified route
        $.ajax({
            url: '/preferences/email',
            type: 'GET',
            dataType: 'json',
        });
    });
});