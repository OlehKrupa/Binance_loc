@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Choose currencies') }}</div>
                    <form action="{{ route('preferences.update') }}" method="POST">
                        @csrf
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

                            <!-- Add DataTable -->
                            <table id="currencyTable" class="table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Price</th>
                                        <th>Trend</th>
                                        <th>★</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($prices as $key => $currency)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td> <img src="{{ $currency['image_url'] }}" alt="Currency Image"
                                                    style="width: 36px; height: 36px;"> | {{ $currency['full_name'] }} |
                                                {{ $currency['name'] }}</td>
                                            <td>$ {{ number_format($currency['sell'], 2) }}</td>
                                            <td>
                                                <span
                                                    class="{{ $trends[$currency->id]['trend'] > 0 ? 'positive-trend' : 'negative-trend' }}">
                                                    {{ number_format($trends[$currency->id]['trend'], 2) }} %
                                                </span>
                                            </td>
                                            <td>
                                                <label class="checkbox-container">
                                                    <input type="checkbox" name="selectedCurrencies[]"
                                                        value="{{ $currency->id }}"
                                                        {{ in_array($currency->id, $selectedCurrencies->toArray()) ? 'checked' : '' }}>
                                                    <span class="checkmark"></span>
                                                </label>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer">
                            <div style="display: flex; justify-content: space-between;">
                                <button class="btn btn-primary">Save Preferences</button>
                                <div>
                                    <form id="subscribeForm" method="GET">
                                        <!--Динамічність буде у VUE, зараз це костиль-->
                                        <button id="emailSubscribeButton" class="btn btn-primary">
                                            @if ($isEmail === null)
                                                Subscribe emailing
                                            @else
                                                Unsubscribe emailing
                                            @endif
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css">

<script src="{{ mix('js/preferences.js') }}" defer></script>
<link href="{{ mix('css/preferences.css') }}" rel="stylesheet">
