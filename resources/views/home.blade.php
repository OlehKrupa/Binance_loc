@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Yours currencies') }}</div>
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
                            <tr>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
<script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
<script>
    // Chart.js
    $(document).ready(function() {
        var ctx = document.getElementById('myChart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'line', // Line chart to display Bitcoin price
            data: {
                labels: ['12:00 AM', '3:00 AM', '6:00 AM', '9:00 AM', '12:00 PM', '3:00 PM', '6:00 PM', '9:00 PM'], // Time intervals
                datasets: [{
                    label: 'Bitcoin Price',
                    data: [45000, 45200, 45500, 45300, 45800, 46000, 45800, 45500], // Bitcoin price data for the day
                    backgroundColor: 'rgba(0, 123, 255, 0.5)',
                    borderColor: 'rgba(0, 123, 255, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value, index, values) {
                                return '$' + value; // Display dollar sign with y-axis values
                            }
                        }
                    }
                }
            }
        });

    // DataTable
        $('#currencyTable').DataTable({

        });
    });
</script>
