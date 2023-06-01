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
                                    <td><input type="checkbox" {{ in_array($currency->id, $selectedCurrencies->toArray()) ? 'checked' : '' }}></td>
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


@section('scripts')
<script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>

<script>
    // DataTable
    $(document).ready(function() {
        $('#currencyTable').DataTable({

        });
    });
</script>
@endsection