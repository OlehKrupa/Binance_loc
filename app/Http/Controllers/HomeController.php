<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\CurrencyHistory;
use Carbon\Carbon;
use Illuminate\Session\Store;
use App\Http\Requests\HomeFilterRequest;

class HomeController extends Controller
{
    /**
     * The session store instance.
     *
     * @var \Illuminate\Session\Store
     */
    protected $session;

    /**
     * Create a new controller instance.
     *
     * @param  \Illuminate\Session\Store  $session
     * @return void
     */
    public function __construct(Store $session)
    {
        $this->middleware('auth');
        $this->session = $session;
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

        // Get start day from session or use default value (1)
        $startDate = $this->session->get('startDate', 1);

        // Get selected user currencies 
        $selectedCurrencies = $user->currencies()->pluck('currency_id');

        // Get chosen currency from session or use default value (first currency)
        $choosenID = $this->session->get('choosenID', $selectedCurrencies->first());
        

        // Get day currencies
        $dayCurrencies = CurrencyHistory::getDayCurrencies($selectedCurrencies, $startDate);

        return view('home')
        ->with('dayCurrencies', $dayCurrencies) 
        ->with('startDate', $startDate)
        ->with('choosenID', $choosenID);
    }

    public function filtered(HomeFilterRequest $request)
    {
        // Get current user
        $user = auth()->user();

        // Update start day if provided in the request
        if ($request->has('dateRange')) {
            $startDate = $request->input('dateRange');
            // Save the updated start day to session
            $this->session->put('startDate', $startDate);
        }

        // Update chosen currency if provided in the request
        if ($request->has('currencyId')) {
            $choosenID = $request->input('currencyId');
            // Save the updated chosen currency to session
            $this->session->put('choosenID', $choosenID);
        }

        return $this->index();
    }
}