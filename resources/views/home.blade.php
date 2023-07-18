@extends('layouts.app')
@section('content')
    <style>
        table.dataTable tbody tr:hover {
            background-color: #D8F2FF !important;
        }

        .dataTables_wrapper .dataTables_scrollBody table.dataTable tbody tr {
            border-bottom: 1px solid #ccc;
        }
    </style>
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

                        @if ($errors->any())
                            <div class="alert alert-danger" role="alert">
                                @foreach ($errors->all() as $error)
                                    {{ $error }}
                                @endforeach
                            </div>
                        @endif

                        <!-- Add Chart.js graph -->
                        <div>
                            <canvas id="myChart"></canvas>
                        </div>

                        <!-- Add choose days -->
                        <label for="dateRangeSelect">Select Date Range:</label>
                        <select id="dateRangeSelect" name="dateRange" onchange="sendDateRange(value);">
                            <option value="6" {{ $startDate == 6 ? 'selected' : '' }}>6 hours</option>
                            <option value="12" {{ $startDate == 12 ? 'selected' : '' }}>12 hours</option>
                            <option value="24" {{ $startDate == 24 ? 'selected' : '' }}>1 day</option>
                            <option value="48" {{ $startDate == 48 ? 'selected' : '' }}>2 days</option>
                            <option value="168" {{ $startDate == 168 ? 'selected' : '' }}>7 days</option>
                            <option value="720" {{ $startDate == 720 ? 'selected' : '' }}>30 days</option>
                        </select>

                        @php
                            $lastCurrencies = $dayCurrencies->reverse()->unique('name');
                        @endphp

                        <div id="phpChartVariables" style="display: none;">
                            <a id="labels">{!! $labels !!}</a>
                            <a id="name">{!! $name !!}</a>
                            <a id="data">{!! $data !!}</a>
                        </div>

                        <!-- Add DataTable -->
                        <form id="updateChartCurrency" action="{{ route('home.filtered') }}" method="POST">
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
                                        <tr data-currencyid="{{ $currency->id }}"
                                            onclick="sendCurrency({{ $currency->id }})">
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

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css">
<script src="{{ mix('js/dashboard.js') }}" defer></script>

<script>
    var myChart;

    $(document).ready(function() {
        $('#currencyTable').DataTable({
            paging: false,
            searching: false,
            language: {
                info: "Select a cryptocurrency to display the chart"
            }
        });

        highlightRow("{{ $choosenID }}");

        //Костиль? сесія працює для айдішника, не праюцє для дати
        var selectedDateRange = localStorage.getItem('selectedDateRange');
        if (selectedDateRange) {
            $('#dateRangeSelect').val(selectedDateRange);
        }

        var labelsSpan = document.getElementById('labels');
        var nameSpan = document.getElementById('name');
        var dataSpan = document.getElementById('data');
        var ctx = document.getElementById('myChart').getContext('2d');

        var labels = JSON.parse(labelsSpan.textContent);
        var name = JSON.parse(nameSpan.textContent);
        var data = JSON.parse(dataSpan.textContent);

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

        myChart = new Chart(ctx, config);
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

                $('#labels').text(JSON.stringify(response.serverVariable.labels));
                $('#name').text(JSON.stringify(response.serverVariable.name));
                $('#data').text(JSON.stringify(response.serverVariable.data));
                var ctx = $('myChart');
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
                highlightRow(currencyId);
                $('#labels').text(JSON.stringify(response.serverVariable.labels));
                $('#name').text(JSON.stringify(response.serverVariable.name));
                $('#data').text(JSON.stringify(response.serverVariable.data));
                var ctx = $('myChart');

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

    function submitForm(currencyId) {
        document.getElementById('currencyIdInput').value = currencyId;
        document.getElementById('updateChartCurrency').submit();
    }

    function highlightRow(currencyId) {
        $('#currencyTable tbody tr').removeClass('table-primary'); // Удаляем класс highlight у всех строк таблицы
        $('#currencyTable tbody tr[data-currencyid="' + currencyId + '"]').addClass(
            'table-primary'); // Добавляем класс highlight выбранной строке
    }
</script>
