<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\HistoryResource;
use App\Services\CurrencyHistoryService;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    private $currencyHistoryService;

    public function __construct(CurrencyHistoryService $currencyHistoryService)
    {
        // Apply 'auth' middleware to this controller, ensuring the user is authenticated
        $this->middleware('auth');
        $this->currencyHistoryService = $currencyHistoryService;
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

    /**
     * Creates a new currency exchange rate history record.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function store(Request $request)
    {
        try {
            // Create a new currency exchange rate history record using the request data and return it as a JSON response
            return response()->json($this->currencyHistoryService->create($request->all()), 200);
        } catch (\Exception $e) {
            // Handle other unexpected errors and return a JSON 500 response
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
     * Updates information about a currency exchange rate history record.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     */
    public function update(Request $request, $id)
    {
        try {
            // Find the currency exchange rate history record by ID
            $history = $this->currencyHistoryService->findById($id);

            // If the currency exchange rate history record does not exist, return a JSON response with a 404 error
            if (!$history) {
                return response()->json(['error' => 'Currency history not found'], 404);
            }

            // Update the currency exchange rate history record with the new request data
            $history->update($request->all());

            // Return the updated currency exchange rate history record as a JSON response
            return response()->json($history, 200);
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