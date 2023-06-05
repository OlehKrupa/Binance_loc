<!DOCTYPE html>
<html>
<head>
    <title>Daily Crypto Report</title>
</head>
<body>
    <h1>Daily Crypto Report</h1>
    <p>Dear {{ $user->first_name }} {{$user->last_name}},</p>
    
    <h2>Currencies daily trend:</h2>
    <table>
        <thead>
            <tr>
                <th>Currency</th>
                <th>Trend</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($selectedCurrencies as $currencyId)
                <tr>
                    <td>
                        @if (is_array($currenciesData[$currencyId]))
                            {{ $currenciesData[$currencyId]['name'] }}
                        @else
                            N/A
                        @endif
                    </td>
                    <td>
                        @if (is_array($currenciesData[$currencyId]))
                            @php
                                $trend = $currenciesData[$currencyId]['trend'];
                                $arrow = $trend > 0 ? '↑' : ($trend < 0 ? '↓' : '');
                            @endphp
                            {{ number_format($trend, 2) }}% {{ $arrow }}
                        @else
                            N/A
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
