function submit(currencyId) {
    var currencyIdInput = document.getElementById('currencyIdInput');
    currencyIdInput.value = currencyId;
    var form = document.getElementById('updateChartCurrency');
    form.submit();
}

// Chart.js
let myChart = null;
var selectedCurrencyId = {!! $lastCurrencies->first()->id !!};

$(document).ready(function() {
    const ctx = document.getElementById('myChart').getContext('2d');

    var labels = {!! $dayCurrencies->where('id', $choosenID)->pluck('updated_at') !!};

    var data = {!! $dayCurrencies->where('id', $choosenID)->pluck('sell') !!};

    var name = {!! $dayCurrencies->where('id', $choosenID)->unique('name')->pluck('name') !!};

    function updateChart() {
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

        myChart = new Chart(ctx, config);
    }

    // Initial chart update
    updateChart();
});

//pretty date on chart
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
