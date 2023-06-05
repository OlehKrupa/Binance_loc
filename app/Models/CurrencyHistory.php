<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class CurrencyHistory extends Model
{
    use HasFactory;

    protected $table = 'currency_history';

    protected $fillable = [
        'currency_id',
        'sell',
        'buy',
    ];

    protected $casts = [
        'created_at'=> 'datetime',
        'updated_at'=> 'datetime',
    ];

    public static function getLastCurrencies($selectedCurrencies)
    {
        return self::select('currency.id', 'currency.name', 'currency_history.sell', 'currency_history.buy', 'currency_history.created_at')
        ->join('currency', 'currency_history.currency_id', '=', 'currency.id')
        ->join('user_currency', 'currency.id', '=', 'user_currency.currency_id')
        ->join('users', 'user_currency.user_id', '=', 'users.id')
        ->whereIn('currency.id', $selectedCurrencies)
        ->latest('currency_history.created_at')
        ->distinct()
        ->take(count($selectedCurrencies))
        ->get();
        
    }

    public static function getDayCurrencies($selectedCurrencies)
    {
        $startDate = Carbon::now()->startOfDay();
        $endDate = Carbon::now()->endOfDay();

        return self::select('currency.id', 'currency.name', 'currency_history.sell', 'currency_history.buy', 'currency_history.updated_at')
        ->join('currency', 'currency_history.currency_id', '=', 'currency.id')
        ->join('user_currency', 'currency.id', '=', 'user_currency.currency_id')
        ->join('users', 'user_currency.user_id', '=', 'users.id')
        ->whereIn('currency.id', $selectedCurrencies)
        ->whereBetween('currency_history.created_at', [$startDate, $endDate])
        ->distinct()
        ->get();

    }

    public static function analyzeCurrencyTrend($selectedCurrencies)
{
    $startDate = Carbon::now()->startOfDay();
    $endDate = Carbon::now()->endOfDay();

    $trend = [];

    foreach ($selectedCurrencies as $currencyId) {
        $firstSell = self::select('currency_history.sell')
            ->where('currency_id', $currencyId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at')
            ->value('sell');

        $lastSell = self::select('currency_history.sell')
            ->where('currency_id', $currencyId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderByDesc('created_at')
            ->value('sell');

        if ($firstSell !== null && $lastSell !== null) {
            $change = ($lastSell - $firstSell) / $firstSell * 100;

            $trend[$currencyId] = $change;
        }
    }

    return $trend;
}



}
