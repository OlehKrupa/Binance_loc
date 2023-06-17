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
        //сделать запоминание выбранного периода
        $startDate = $request->input('dateRange', 1);

        // Get selected user currencies 
        $selectedCurrencies = $user->currencies()->pluck('currency_id');

        // Get choosen currency
        //сделать запоминание выбранной крипты
        $choosenID = $request->input('currencyId', $selectedCurrencies->first());

        // Get day currencies
        $dayCurrencies = CurrencyHistory::getDayCurrencies($selectedCurrencies, $startDate);

        return view('home')
        ->with('dayCurrencies', $dayCurrencies) 
        ->with('startDate',$startDate)
        ->with('choosenID',$choosenID);
    }

}
