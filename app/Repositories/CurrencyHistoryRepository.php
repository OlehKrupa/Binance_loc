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

    /**
     * Get unique currency IDs from the CurrencyHistory model.
     *
     * @return array
     */
    public function getUniqueCurrenciesId(): array
    {
        return CurrencyHistory::pluck('currency_id')->unique()->toArray();
    }

    /**
     * Get the last recorded currencies for the selected currencies.
     *
     * @param array $selectedCurrencies
     * @return \Illuminate\Support\Collection
     */
    public static function getLastCurrencies($selectedCurrencies)
    {
        return CurrencyHistory::select('currency.id', 'currency.name', 'currency_history.sell', 'currency_history.buy', 'currency_history.created_at')
            ->join('currency', 'currency_history.currency_id', '=', 'currency.id')
            ->whereIn('currency.id', $selectedCurrencies)
            ->latest('currency_history.created_at')
            ->distinct()
            ->take(count($selectedCurrencies))
            ->get();
    }

    /**
     * Get the currencies recorded within the specified hours for the selected currencies.
     *
     * @param array $selectedCurrencies
     * @param int $hours
     * @return \Illuminate\Support\Collection
     */
    public static function getHourCurrencies($selectedCurrencies, $hours)
    {
        $startDateTime = Carbon::now()->subHours($hours);
        $endDateTime = Carbon::now();

        return CurrencyHistory::select('currency.id', 'currency.name', 'currency.full_name', 'currency.image_url', 'currency_history.sell', 'currency_history.buy', 'currency_history.updated_at')
            ->join('currency', 'currency_history.currency_id', '=', 'currency.id')
            ->whereIn('currency.id', $selectedCurrencies)
            ->whereBetween('currency_history.created_at', [$startDateTime, $endDateTime])
            ->distinct()
            ->get();
    }

    public static function getAllSelectedCurrencies($selectedCurrencies)
    {
        return CurrencyHistory::select('currency.id', 'currency.name', 'currency.full_name', 'currency.image_url', 'currency_history.sell', 'currency_history.buy', 'currency_history.updated_at')
            ->join('currency', 'currency_history.currency_id', '=', 'currency.id')
            ->whereIn('currency.id', $selectedCurrencies)
            ->distinct()
            ->get();
    }

    /**
     * Analyze the currency trend for the selected currencies within the current day.
     *
     * @param array $selectedCurrencies
     * @return array
     */
    public static function analyzeCurrencyTrend($selectedCurrencies)
    {
        $startDate = Carbon::now()->startOfDay();
        $endDate = Carbon::now()->endOfDay();

        $trend = [];

        foreach ($selectedCurrencies as $currencyId) {
            $currency = Currency::find($currencyId);
            if ($currency) {
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
                        'id' => $currencyId,
                        'name' => $currency->name,
                        'trend' => $change,
                    ];
                }
            }
        }
        return $trend;
    }

}