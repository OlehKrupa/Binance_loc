<!DOCTYPE html>
<html>
<head>
    <title>Daily Crypto Report</title>
</head>
<body>
    <h1>Daily Crypto Report</h1>
    <p>Dear {{ $user->name }},</p>
    
    <h2>Currencies:</h2>
    <ul>
        @foreach ($currencies as $currency)
            <li>{{ $currency->name }}</li>
        @endforeach
    </ul>
    
    <h2>Currencies Data:</h2>
    <table>
        <thead>
            <tr>
                <th>Currency</th>
                <th>Sell</th>
                <th>Buy</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($currenciesData as $data)
                <tr>
                    <td>{{ $data->name }}</td>
                    <td>{{ $data->sell }}</td>
                    <td>{{ $data->buy }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
