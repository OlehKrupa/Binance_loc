<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CurrencyService;
use App\Services\CurrencyHistoryService;
use App\Services\UserService;
use App\Http\Requests\PreferencesUpdateRequest;

class PreferencesController extends Controller
{
    private $currencyService;
    private $currencyHistoryService;
    private $userService;

    public function __construct(CurrencyService $currencyService, CurrencyHistoryService $currencyHistoryService, UserService $userService)
    {
        $this->currencyService = $currencyService;
        $this->currencyHistoryService = $currencyHistoryService;
        $this->userService = $userService;
    }

    public function index()
    {
        $currencies = $this->currencyService->all();

        $currenciesId = $currencies->pluck('id');

        $user = auth()->user();
        
        $prices = $this->currencyHistoryService->getHourCurrencies($currenciesId, 24)->unique();
        
        $trends = $this->currencyHistoryService->analyzeCurrencyTrend($currenciesId);

        $selectedCurrencies = $this->userService->getUserCurrencies($user);

        return view('preferences')
        ->with('prices', $prices)
        ->with('trends', $trends)
        ->with('selectedCurrencies' , $selectedCurrencies);
    }

    public function update(PreferencesUpdateRequest $request)
    {
        // Get the selected cryptocurrencies from the request
        $selectedCurrencies = $request->input('selectedCurrencies');

        // Clear previously selected cryptocurrencies for the user
        $user = auth()->user();
        $this->userService->detachCurrencies($user);

        // Add the newly selected cryptocurrencies
        foreach ($selectedCurrencies as $currencyId) {
            $this->userService->attachCurrency($user, $currencyId);
        }

        // Redirect to the "/home" page with a success message
        return redirect('/home')->with('success', 'Preferences updated successfully.');
    }
}