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
                        <select id="dateRangeSelect" name="dateRange">
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
                            <a id="choosenID">{!! $choosenID !!}</a>
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
                                        <tr data-currencyid="{{ $currency->id }}">
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
<link href="{{ mix('css/dashboard.css') }}" rel="stylesheet">