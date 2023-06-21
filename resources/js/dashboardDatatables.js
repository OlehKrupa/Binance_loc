// datatable.js
$(document).ready(function() {
    $('#currencyTable').DataTable({
        paging: false,
        searching: false,
        language: {
            info: "Select a cryptocurrency to display the chart"
        }
    });
});
