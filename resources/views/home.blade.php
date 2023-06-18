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

                    <!-- Add choose days -->
                    <form id="updateChartForm" method="POST" action="{{ route('home') }}">
                        @csrf
                        <label for="dateRangeSelect">Select Date Range:</label>
                        <select id="dateRangeSelect" name="dateRange">
                            <option value="0" {{ $startDate == 0 ? 'selected' : '' }}>1 day</option>
                            <option value="1" {{ $startDate == 1 ? 'selected' : '' }}>2 days</option>
                            <option value="2" {{ $startDate == 2 ? 'selected' : '' }}>3 days</option>
                            <option value="6" {{ $startDate == 6 ? 'selected' : '' }}>7 days</option>
                            <option value="13" {{ $startDate == 13 ? 'selected' : '' }}>14 days</option>
                            <option value="20" {{ $startDate == 20 ? 'selected' : '' }}>21 days</option>
                            <option value="29" {{ $startDate == 29 ? 'selected' : '' }}>30 days</option>
                        </select>
                        <button type="submit" class="btn btn-primary">Apply</button>
                    </form>

                    @php
                    $lastCurrencies = $dayCurrencies->reverse()->unique('name');
                    @endphp

                    <!-- Add DataTable -->
                    <form id="updateChartCurrency" method="POST" action="{{ route('home') }}">
                        @csrf
                        <table id="currencyTable" class="table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Buy</th>
                                    <th>Sell</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($lastCurrencies as $currency)
                                <tr onclick="submit({{$currency->id}})">
                                    <td>{{ $currency->name }}</td>
                                    <td>${{ number_format($currency->buy, 2) }}</td>
                                    <td>${{ number_format($currency->sell, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <input type="hidden" name="currencyId" id="currencyIdInput">
                    </form>
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

        // DataTable
        $('#currencyTable').DataTable({
            paging: false,
            searching: false,
            language: {
                info: "Select a cryptocurrency to display the chart"
            }
        });

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
</script>