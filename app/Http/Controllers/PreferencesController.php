<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Currency;
use App\Http\Requests\PreferencesUpdateRequest;

class PreferencesController extends Controller
{
    public function index()
    {
        $currencies = Currency::all();

        // Get the current user
        $user = auth()->user();

        // Get the currencies selected by the user
        $selectedCurrencies = $user->currencies()->pluck('currency_id');

        return view('preferences', compact('currencies', 'selectedCurrencies'));
    }

    public function update(PreferencesUpdateRequest $request)
    {
        // Get the selected cryptocurrencies from the request
        $selectedCurrencies = $request->input('selectedCurrencies');

        // Clear previously selected cryptocurrencies for the user
        $user = auth()->user();
        $user->currencies()->detach();

        // Add the newly selected cryptocurrencies
        foreach ($selectedCurrencies as $currencyId) {
            $user->currencies()->attach($currencyId);
        }

        // Redirect to the "/home" page with a success message
        return redirect('/home')->with('success', 'Preferences updated successfully.');
    }
}