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