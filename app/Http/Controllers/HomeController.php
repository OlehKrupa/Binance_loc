<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Session\Store;
use App\Http\Requests\HomeFilterRequest;
use App\Services\UserService;
use App\Services\CurrencyHistoryService;
use Illuminate\Support\Facades\Http;

class HomeController extends Controller
{
    /**
     * The session store instance.
     *
     * @var \Illuminate\Session\Store
     */
    protected $session;

    /**
     * The UserService instance.
     *
     * @var UserService
     */
    private $userService;

    /**
     * The CurrencyHistoryService instance.
     *
     * @var CurrencyHistoryService
     */
    private $currencyHistoryService;

    /**
     * Create a new controller instance.
     *
     * @param  \Illuminate\Session\Store  $session
     * @param  \App\Services\UserService  $userService
     * @param  \App\Services\CurrencyHistoryService  $currencyHistoryService
     * @return void
     */
    public function __construct(
        Store $session,
        UserService $userService,
        CurrencyHistoryService $currencyHistoryService
    ) {
        $this->middleware('auth');
        $this->session = $session;
        $this->userService = $userService;
        $this->currencyHistoryService = $currencyHistoryService;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // Get current user
        $user = auth()->user();

        // Get start day from session or use default value (48)
        $startDate = $this->session->get('startDate', 48);

        // Get selected user currencies
        $selectedCurrencies = $this->userService->getUserCurrencies($user);

        // Get chosen currency from session or use default value (first currency)
        $choosenID = $this->session->get('choosenID', $selectedCurrencies->first());

        // Get day currencies
        $dayCurrencies = $this->currencyHistoryService->getHourCurrencies($selectedCurrencies, $startDate);

        // Extract labels, data, and name for the chosen currency
        $labels = $dayCurrencies->where('id', $choosenID)->pluck('updated_at');
        $data = $dayCurrencies->where('id', $choosenID)->pluck('sell');
        $name = $dayCurrencies->where('id', $choosenID)->unique('name')->pluck('name');

        // Pass data to the 'home' view
        return view('home')
            ->with('dayCurrencies', $dayCurrencies)
            ->with('startDate', $startDate)
            ->with('choosenID', $choosenID)
            ->with('labels', $labels)
            ->with('data', $data)
            ->with('name', $name);
    }

    /**
     * Filter the currencies based on the user's selection.
     *
     * @param  \App\Http\Requests\HomeFilterRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function filtered(HomeFilterRequest $request)
    {
        // Get current user
        $user = auth()->user();

        // Update start date in session if newDateRange is present in the request
        if ($request->has('newDateRange')) {
            $startDate = $request->input('newDateRange');
            $this->session->put('startDate', $startDate);
        }

        // Update chosen currency ID in session if newCurrencyId is present in the request
        if ($request->has('newCurrencyId')) {
            $choosenID = $request->input('newCurrencyId');
            $this->session->put('choosenID', $choosenID);
        }

        // Retrieve selected currencies, start date, and chosen currency ID from the session
        $selectedCurrencies = $this->userService->getUserCurrencies($user);
        $startDate = $this->session->get('startDate', 48);
        $choosenID = $this->session->get('choosenID', $selectedCurrencies->first());

        // Get day currencies based on the selected currencies and start date
        $dayCurrencies = $this->currencyHistoryService->getHourCurrencies($selectedCurrencies, $startDate);

        // Extract labels, data, and name for the chosen currency
        $labels = $dayCurrencies->where('id', $choosenID)->pluck('updated_at');
        $data = $dayCurrencies->where('id', $choosenID)->pluck('sell');
        $name = $dayCurrencies->where('id', $choosenID)->unique('name')->pluck('name');

        // Prepare the server variable to be returned as JSON response
        $serverVariable = [
            'labels' => $labels,
            'name' => $name,
            'data' => $data,
        ];

        // Return the server variable as JSON response
        return response()->json(['serverVariable' => $serverVariable]);
    }
}