<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CurrencyService;
use App\Services\CurrencyHistoryService;
use App\Services\UserService;
use App\Http\Requests\PreferencesUpdateRequest;

class PreferencesController extends Controller
{
    /**
     * The currency service instance.
     *
     * @var CurrencyService
     */
    private $currencyService;

    /**
     * The currency history service instance.
     *
     * @var CurrencyHistoryService
     */
    private $currencyHistoryService;

    /**
     * The user service instance.
     *
     * @var UserService
     */
    private $userService;

    /**
     * Create a new PreferencesController instance.
     *
     * @param CurrencyService $currencyService
     * @param CurrencyHistoryService $currencyHistoryService
     * @param UserService $userService
     */
    public function __construct(
        CurrencyService $currencyService,
        CurrencyHistoryService $currencyHistoryService,
        UserService $userService
    ) {
        $this->currencyService = $currencyService;
        $this->currencyHistoryService = $currencyHistoryService;
        $this->userService = $userService;
    }

    /**
     * Display the preferences page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Get all currencies
        $currencies = $this->currencyService->all();

        // Get IDs of all currencies
        $currenciesId = $currencies->pluck('id');

        // Get the authenticated user
        $user = auth()->user();

        // Get hourly currency prices for the last 24 hours
        $prices = $this->currencyHistoryService->getHourCurrencies($currenciesId, 24)->unique();

        // Analyze currency trends
        $trends = $this->currencyHistoryService->analyzeCurrencyTrend($currenciesId);

        // Get the user's selected currencies
        $selectedCurrencies = $this->userService->getUserCurrencies($user);

        //КостЫль кодим
        $isEmail = $this->userService->findById($user->id);

        // Prepare the data to be returned as JSON response
        $responseData = [
            'prices' => $prices,
            'trends' => $trends,
            'selectedCurrencies' => $selectedCurrencies,
            'isEmail' => $isEmail['subscribed_at'],
        ];

        // Return the data as a JSON response
        return response()->json($responseData);
    }

    /**
     * Update the user's preferences.
     *
     * @param PreferencesUpdateRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
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

    public function subscribeEmail()
    {
        // Get the authenticated user
        $user = auth()->user();

        $this->userService->updateSubscribedAt($user);
    }
}