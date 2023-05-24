@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{ __('You are logged in!') }}

                    <!-- Add Chart.js graph -->
                    <canvas id="myChart"></canvas>

                    <!-- Add DataTable -->
                    <table id="myTable" class="table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Buy</th>
                                <th>Sell</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Bitcoin</td>
                                <td>$45,000</td>
                                <td>$46,000</td>
                            </tr>
                            <tr>
                                <td>Ethereum</td>
                                <td>$3,000</td>
                                <td>$3,100</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
<script>
    // Chart.js
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
    $(document).ready(function() {
        $('#myTable').DataTable();
    });
</script>
@endsection
