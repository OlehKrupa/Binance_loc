<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CurrencyResource;
use App\Models\Currency; //!! сервис!
use App\Services\CurrencyService;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CurrencyController extends Controller
{
    private $currencyService;

    public function __construct(CurrencyService $currencyService)
    {
        // Apply 'auth' middleware to this controller, ensuring the user is authenticated
        $this->middleware('auth');
        $this->currencyService = $currencyService;
    }

    /**
     * Returns a list of all currencies.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        try {
            // Return a collection of all currencies as a JSON response using CurrencyResource
            return response()->json($this->currencyService->all()->toArray(), 200);
        } catch (\Exception $e) {
            // Handle other unexpected errors and return a JSON 500 response
            return response()->json(['error' => 'Internal server error'], 500);
        }
    }

    /**
     * Creates a new currency.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \App\Models\Currency
     */
    public function store(Request $request)
    {
        try {
            // Create a new currency using the request data and return it as a JSON response
            return response()->json($this->currencyService->create($request->all())->toArray(), 200);
        } catch (\Exception $e) {
            // Handle other unexpected errors and return a JSON 500 response
            return response()->json(['error' => 'Internal server error'], 500);
        }
    }

    /**
     * Returns information about a specific currency.
     *
     * @param  int  $id
     * @return \App\Http\Resources\CurrencyResource
     */
    public function show($id)
    {
        try {
            // Find the currency by ID
            $currency = $this->currencyService->findById($id)->toArray();

            // Return information about the specific currency as a JSON response using CurrencyResource
            return response()->json($currency, 200);
        } catch (ModelNotFoundException $e) {
            // Handle the "Currency not found" scenario and return a JSON 404 response
            return response()->json(['error' => 'Currency not found'], 404);
        } catch (\Exception $e) {
            // Handle other unexpected errors and return a JSON 500 response
            return response()->json(['error' => 'Internal server error'], 500);
        }
    }

    /**
     * Updates information about a currency.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \App\Http\Resources\CurrencyResource
     */
    public function update(Request $request, $id)
    {
        try {
            // Find the currency by ID and update
            $currency = $this->currencyService->findById($id)->update($request->all());

            // Return the updated currency as a JSON response using CurrencyResource
            return response()->json($this->currencyService->findById($id), 200);
        } catch (ModelNotFoundException $e) {
            // Handle the "Currency not found" scenario and return a JSON 404 response
            return response()->json(['error' => 'Currency not found'], 404);
        } catch (\Exception $e) {
            // Handle other unexpected errors and return a JSON 500 response
            return response()->json(['error' => 'Internal server error'], 500);
        }
    }

    /**
     * Deletes a currency.
     *
     * @param  int  $id
     * @return array
     */
    public function destroy($id)
    {
        try {
            // Find the currency by ID
            $currency = $this->currencyService->findById($id);

            // Delete the currency
            $currency->delete();

            // Return a success message as a JSON response
            return ['message' => 'Currency deleted'];
        } catch (ModelNotFoundException $e) {
            // Handle the "Currency not found" scenario and return a JSON 404 response
            return response()->json(['error' => 'Currency not found'], 404);
        } catch (\Exception $e) {
            // Handle other unexpected errors and return a JSON 500 response
            return response()->json(['error' => 'Internal server error'], 500);
        }
    }
}