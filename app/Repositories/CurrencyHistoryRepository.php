<?php

namespace App\Repositories;

use App\Repositories\BaseRepository;
use App\Models\CurrencyHistory;
use App\Models\Currency;
use Carbon\Carbon;

class CurrencyHistoryRepository extends BaseRepository
{
    public function __construct(CurrencyHistory $model)
    {
        $this->model = $model;
    }

    public function getUniqueCurrenciesId(): array
    {
        return CurrencyHistory::pluck('currency_id')->unique()->toArray();
    }

    public static function getLastCurrencies($selectedCurrencies)
    {
        return CurrencyHistory::select('currency.id', 'currency.name', 'currency_history.sell', 'currency_history.buy', 'currency_history.created_at')
            ->join('currency', 'currency_history.currency_id', '=', 'currency.id')
            ->join('user_currency', 'currency.id', '=', 'user_currency.currency_id')
            ->join('users', 'user_currency.user_id', '=', 'users.id')
            ->whereIn('currency.id', $selectedCurrencies)
            ->latest('currency_history.created_at')
            ->distinct()
            ->take(count($selectedCurrencies))
            ->get();
    }

    public static function getHourCurrencies($selectedCurrencies, $hours)
    {
        $startDateTime = Carbon::now()->subHours($hours);
        $endDateTime = Carbon::now();

        return CurrencyHistory::select('currency.id', 'currency.name', 'currency_history.sell', 'currency_history.buy', 'currency_history.updated_at')
            ->join('currency', 'currency_history.currency_id', '=', 'currency.id')
            ->join('user_currency', 'currency.id', '=', 'user_currency.currency_id')
            ->join('users', 'user_currency.user_id', '=', 'users.id')
            ->whereIn('currency.id', $selectedCurrencies)
            ->whereBetween('currency_history.created_at', [$startDateTime, $endDateTime])
            ->distinct()
            ->get();
    }


    public static function analyzeCurrencyTrend($selectedCurrencies)
    {
        $startDate = Carbon::now()->startOfDay();
        $endDate = Carbon::now()->endOfDay();

        $trend = [];

        foreach ($selectedCurrencies as $currencyId) {
            $firstSell = CurrencyHistory::select('currency_history.sell')
                ->where('currency_id', $currencyId)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->orderBy('created_at')
                ->value('sell');

            $lastSell = CurrencyHistory::select('currency_history.sell')
                ->where('currency_id', $currencyId)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->orderByDesc('created_at')
                ->value('sell');

            if ($firstSell !== null && $lastSell !== null) {
                $change = ($lastSell - $firstSell) / $firstSell * 100;

                $trend[$currencyId] = [
                    'name' => Currency::find($currencyId)->name,
                    'trend' => $change,
                ];
            }
        }
        return $trend;
    }
}
