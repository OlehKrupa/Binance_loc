<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\CurrencyHistory;

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

        //get selected user currencies 
        $selectedCurrencies = $user->currencies()->select('currency_id')->get();

        $selectedCurrencies = ["62","71","72"];

        $currenciesHistory = CurrencyHistory::select('currency.id', 'currency.name', 'currency_history.sell', 'currency_history.buy')
        ->join('currency', 'currency_history.currency_id', '=', 'currency.id')
        ->join('user_currency', 'currency.id', '=', 'user_currency.currency_id')
        ->join('users', 'user_currency.user_id', '=', 'users.id')
        ->whereIn('currency.id', $selectedCurrencies)
        ->latest('currency_history.created_at')
        ->take(count($selectedCurrencies))
        ->get();

        return view('home')->with('currenciesHistory', $currenciesHistory);
    }
}
