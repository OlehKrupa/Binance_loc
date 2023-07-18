var myChart;

$(document).ready(function() {
    // Initialize DataTable for currencyTable
    $('#currencyTable').DataTable({
        paging: false,
        searching: false,
        language: {
            info: "Select a cryptocurrency to display the chart"
        }
    });

    // Highlight the selected row on page load
    highlightRow("{{ $choosenID }}");

    // Store the selected date range in local storage
    var selectedDateRange = localStorage.getItem('selectedDateRange');
    if (selectedDateRange) {
        $('#dateRangeSelect').val(selectedDateRange);
    }

    // Get chart data from HTML spans
    var labelsSpan = document.getElementById('labels');
    var nameSpan = document.getElementById('name');
    var dataSpan = document.getElementById('data');
    var ctx = document.getElementById('myChart').getContext('2d');

    // Parse the chart data from the spans
    var labels = JSON.parse(labelsSpan.textContent);
    var name = JSON.parse(nameSpan.textContent);
    var data = JSON.parse(dataSpan.textContent);

    // Configure the chart
    var config = {
        type: 'line',
        data: {
            labels: labels.map(function(label) {
                var date = new Date(label);
                return formatDate(date);
            }),
            datasets: [{
                label: name,
                data: data,
            }],
        },
        options: {
            legend: {
                display: true
            }
        }
    };

    // Create the chart
    myChart = new Chart(ctx, config);

    // Event handler for date range select change
    $('#dateRangeSelect').on('change', function() {
        var dateRange = $(this).val();
        sendDateRange(dateRange);
    });

    // Event handler for clicking on a table row
    $('#currencyTable tbody').on('click', 'tr', function() {
        var currencyId = $(this).data('currencyid');
        sendCurrency(currencyId);
    });
});

// Format a date to a custom string format
function formatDate(date) {
    var options = {
        year: '2-digit',
        month: '2-digit',
        day: '2-digit',
        hour: '2-digit',
        minute: '2-digit'
    };
    return date.toLocaleDateString('en-GB', options).replace(',', '');
}

// Send the selected date range to the server
function sendDateRange(dateRange) {
    localStorage.setItem('selectedDateRange', dateRange);
    var dataToSend = {
        newDateRange: dateRange
    };

    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: "/home/sendDateRange",
        type: "POST",
        data: dataToSend,
        success: function(response) {
            // Update the chart data
            $('#labels').text(JSON.stringify(response.serverVariable.labels));
            $('#name').text(JSON.stringify(response.serverVariable.name));
            $('#data').text(JSON.stringify(response.serverVariable.data));
            myChart.data.labels = response.serverVariable.labels.map(function(label) {
                var date = new Date(label);
                return formatDate(date);
            });
            myChart.data.datasets[0].label = response.serverVariable.name;
            myChart.data.datasets[0].data = response.serverVariable.data;
            myChart.update();
        },
        error: function(xhr, status, error) {
            console.error("Error:", error);
        }
    });
}

// Send the selected currency to the server
function sendCurrency(currencyId) {
    var dataToSend = {
        newCurrencyId: currencyId
    };

    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: "/home/sendCurrency",
        type: "POST",
        data: dataToSend,
        success: function(response) {
            // Highlight the selected row and update the chart data
            highlightRow(currencyId);
            $('#labels').text(JSON.stringify(response.serverVariable.labels));
            $('#name').text(JSON.stringify(response.serverVariable.name));
            $('#data').text(JSON.stringify(response.serverVariable.data));
            myChart.data.labels = response.serverVariable.labels.map(function(label) {
                var date = new Date(label);
                return formatDate(date);
            });
            myChart.data.datasets[0].label = response.serverVariable.name;
            myChart.data.datasets[0].data = response.serverVariable.data;
            myChart.update();
        },
        error: function(xhr, status, error) {
            console.error("Error:", error);
        }
    });
}

// Function to highlight the selected row in the DataTable
export function highlightRow(currencyId) {
    $('#currencyTable tbody tr').removeClass('table-primary'); // Remove highlight class from all table rows
    $('#currencyTable tbody tr[data-currencyid="' + currencyId + '"]').addClass('table-primary'); // Add highlight class to the selected row
}