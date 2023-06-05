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
    public function index()
    {
        //get current user
        $user = auth()->user();

        //get current day
        $startDate = Carbon::now(); 

        //get selected user currencies 
        $selectedCurrencies = $user->currencies()->pluck('currency_id');

        // Get last currencies
        $currenciesHistory = CurrencyHistory::getLastCurrencies($selectedCurrencies);
        
        return view('home')->with('currenciesHistory', $currenciesHistory);
    }
}
