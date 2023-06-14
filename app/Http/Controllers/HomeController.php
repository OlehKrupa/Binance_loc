<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\CurrencyHistory;
use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        // Get current user
        $user = auth()->user();

        // Get start day
        $startDate = $request->input('dateRange', 1);

        // Get selected user currencies 
        $selectedCurrencies = $user->currencies()->pluck('currency_id');

        // Get last currencies
        $currenciesHistory = CurrencyHistory::getLastCurrencies($selectedCurrencies);

        // Get day currencies
        $dayCurrencies = CurrencyHistory::getDayCurrencies($selectedCurrencies, $startDate);

        return view('home')
        ->with('currenciesHistory', $currenciesHistory)
        ->with('dayCurrencies', $dayCurrencies) 
        ->with('startDate',$startDate);
    }

}
