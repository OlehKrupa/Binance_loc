<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\HistoryResource;
use App\Services\CurrencyHistoryService;
use App\Services\CurrencyService;
use App\Models\Currency;
use App\Models\CurrencyHistory;
use Illuminate\Support\Facades\DB;

class HistoryController extends Controller
{
    private $currencyService;
    private $currencyHistoryService;

    public function __construct(CurrencyHistoryService $currencyHistoryService, CurrencyService $currencyService)
    {
        // Apply 'auth' middleware to this controller, ensuring the user is authenticated
        $this->middleware('auth');
        $this->currencyHistoryService = $currencyHistoryService;
        $this->currencyService = $currencyService;
    }

    /**
     * Returns a list of all currency exchange rate history records.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        try {
            // Return a collection of all currency exchange rate history records as a JSON response using HistoryResource
            return response()->json(HistoryResource::collection($this->currencyHistoryService->all()), 200);
        } catch (\Exception $e) {
            // Handle other unexpected errors and return a JSON 500 response
            return response()->json(['error' => 'Internal server error'], 500);
        }
    }

    public function chartData()
    {

    }

    public function preferencesData()
    {
        try {
            $allCurrency = $this->currencyService->all();
            $allCurrencyIds = $allCurrency->pluck('id')->toArray();

            $lastCurrencies = $this->currencyHistoryService->getLastCurrencies($allCurrencyIds);
            $trend = $this->currencyHistoryService->analyzeCurrencyTrend($allCurrencyIds);

            $result = [];

            foreach ($allCurrency as $currency) {
                $currencyId = $currency->id;

                $lastCurrency = $lastCurrencies->where('id', $currencyId)->first();

                $currencyTrend = $trend[$currencyId] ?? null;

                $currencyData = [
                    'currency' => $currency,
                    'last_price' => $lastCurrency ? (float) $lastCurrency->sell : null,
                    'trend' => $currencyTrend ? $currencyTrend['trend'] : null,
                ];

                $result[] = $currencyData;
            }

            return response()->json($result, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Internal server error'], 500);
        }
    }

    public function preferencesDataEloquent()
    {
        try {
            $today = now()->format('Y-m-d');
            $daysForPriceChange = 7; // Измените это значение на нужное количество дней

            $subquery_latest_history = CurrencyHistory::select('currency_id', 'sell', 'created_at')
                ->whereIn(DB::raw('(currency_id, created_at)'), function ($query) use ($today) {
                    $query->select(DB::raw('currency_id, max(created_at)'))
                        ->from('currency_history')
                        ->whereDate('created_at', $today)
                        ->groupBy('currency_id');
                });

            $subquery_previous_history = CurrencyHistory::select('currency_id', 'sell', 'created_at')
                ->whereIn(DB::raw('(currency_id, created_at)'), function ($query) use ($today) {
                    $query->select(DB::raw('currency_id, max(created_at)'))
                        ->from('currency_history')
                        ->where('created_at', '<', $today)
                        ->groupBy('currency_id');
                });


            $currencies = Currency::select(
                'currency.id as currency_id',
                'currency.name as currency_name',
                'currency.full_name',
                'currency.image_url',
                'latest_history.sell as last_sell_price',
                DB::raw('IFNULL(ROUND(((latest_history.sell - previous_history.sell) / previous_history.sell) * 100, 2), 0) as price_change_percent')
            )
                ->leftJoinSub($subquery_latest_history, 'latest_history', function ($join) {
                    $join->on('currency.id', '=', 'latest_history.currency_id');
                })
                ->leftJoinSub($subquery_previous_history, 'previous_history', function ($join) {
                    $join->on('currency.id', '=', 'previous_history.currency_id');
                })
                ->get();

            return response()->json($currencies, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Analyzes the currency trend for the selected currencies within the current day.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function analyzeCurrencyTrend()
    {
        try {
            $selectedCurrencies = $this->currencyHistoryService->getUniqueCurrenciesId();
            $trend = $this->currencyHistoryService->analyzeCurrencyTrend($selectedCurrencies);
            return response()->json($trend, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Internal server error'], 500);
        }
    }

    /**
     * Returns the last recorded currencies for the selected currencies.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLastCurrencies()
    {
        try {
            $selectedCurrencies = $this->currencyHistoryService->getUniqueCurrenciesId();
            $lastCurrencies = $this->currencyHistoryService->getLastCurrencies($selectedCurrencies);

            return response()->json($lastCurrencies, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Internal server error'], 500);
        }
    }

    /**
     * Returns all currency exchange rate history records for a specific currency.
     *
     * @param  int  $currency_id
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function show($currency_id)
    {
        try {
            // Find all currency exchange rate history records for the specific currency ID
            $currencyHistories = $this->currencyHistoryService->all()->where('currency_id', $currency_id)->toArray();

            // Return the currency exchange rate history records as a JSON response using HistoryResource
            return response()->json($currencyHistories, 200);
        } catch (\Exception $e) {
            // Handle other unexpected errors and return a JSON 500 response
            return response()->json(['error' => 'Internal server error'], 500);
        }
    }

    /**
     * Deletes a currency exchange rate history record.
     *
     * @param  int  $id
     * @return array
     */
    public function destroy($id)
    {
        try {
            // Find the currency exchange rate history record by ID
            $history = $this->currencyHistoryService->findById($id);

            // If the currency exchange rate history record does not exist, return a JSON response with a 404 error
            if (!$history) {
                return response()->json(['error' => 'Currency history not found'], 404);
            }

            // Delete the currency exchange rate history record
            $history->delete();

            // Return a success message as a JSON response
            return ['message' => 'Currency history deleted'];
        } catch (\Exception $e) {
            // Handle other unexpected errors and return a JSON 500 response
            return response()->json(['error' => 'Internal server error'], 500);
        }
    }
}