//main.js
$(document).ready(function() {
    const labelsSpan = document.getElementById('labels');
    const nameSpan = document.getElementById('name');
    const dataSpan = document.getElementById('data');

    const labels = JSON.parse(labelsSpan.textContent);
    const name = JSON.parse(nameSpan.textContent);
    const data = JSON.parse(dataSpan.textContent);

    updateChart(labels, name, data);
});

// chart.js
const ctx = document.getElementById('myChart').getContext('2d');

function updateChart(labels, name, data) {
    var config = {
        type: 'line',
        data: {
            labels: labels.map(function (label) {
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

    new Chart(ctx, config);
}

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