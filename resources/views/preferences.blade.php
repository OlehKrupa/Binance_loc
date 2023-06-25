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
                                    <th>★</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($currencies as $key => $currency)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $currency->name }}</td>
                                    <td><input type="checkbox" name="selectedCurrencies[]" value="{{ $currency->id }}" {{ in_array($currency->id, $selectedCurrencies->toArray()) ? 'checked' : '' }}></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Save Preferences</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.css" />
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.js"></script>

<script src="{{ mix('js/preferences.js') }}" defer></script>