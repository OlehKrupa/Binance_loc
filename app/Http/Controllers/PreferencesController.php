<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CurrencyService;
use App\Services\UserService;
use App\Http\Requests\PreferencesUpdateRequest;

class PreferencesController extends Controller
{
    private $currencyService;
    private $userService;

    public function __construct(CurrencyService $currencyService, UserService $userService)
    {
        $this->currencyService = $currencyService;
        $this->userService = $userService;
    }

    public function index()
    {
        $currencies = $this->currencyService->all();

        // Get the current user
        $user = auth()->user();

        // Get the currencies selected by the user
        $selectedCurrencies = $this->userService->getUserCurrencies($user);

        return view('preferences', compact('currencies', 'selectedCurrencies'));
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