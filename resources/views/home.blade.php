@extends('layouts.app')
@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Your Currencies') }}</div>
                <div class="card-body">
                    @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                    @endif
                    <!-- Add Chart.js graph -->
                    <div>
                        <canvas id="myChart"></canvas>
                    </div>
                    <!-- Add DataTable -->
                    <table id="currencyTable" class="table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Buy</th>
                                <th>Sell</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($currenciesHistory as $currency)
                            <tr onclick="getID({{ $currency->id }})">
                                <td>{{ $currency->name }}</td>
                                <td>${{ number_format($currency->buy, 2) }}</td>
                                <td>${{ number_format($currency->sell, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.css" />
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.js"></script>
<script>
// Chart.js
    $(document).ready(function() {
        var selectedCurrencyId = {!! $currenciesHistory->first()->id !!};

        window.getID = function(id) {
            selectedCurrencyId = id;
            updateChart(selectedCurrencyId);
        }

        function updateChart(selectedCurrencyId) {
            console.log(selectedCurrencyId);

            var labels = {!! $dayCurrencies->where('id', 171)->pluck('updated_at') !!};
            var data = {!! $dayCurrencies->where('id', 171)->pluck('sell') !!};

            var ctx = document.getElementById('myChart').getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels.map(function(label) {
                        var date = new Date(label);
                        return formatDate(date);
                    }),
                    datasets: [{
                        label: 'Currency Price',
                        data: data,
                        backgroundColor: 'rgba(0, 123, 255, 0.5)',
                        borderColor: 'rgba(0, 123, 255, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                          boxWidth: 80,
                          fontColor: 'black'
                      }
                  }
              }
          });
        }

    // DataTable
        $('#currencyTable').DataTable({
            paging: false,
            searching: false,
            language: {
              info: "Select a cryptocurrency to display the chart"
          }
      });


    // Initial chart update
        updateChart(selectedCurrencyId);
    });

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
</script>