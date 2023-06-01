<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Currency;

class PreferencesController extends Controller
{
    public function index()
    {
        $currencies = Currency::all();

        //get current user
        $user = auth()->user();

        //get selected user currencies 
        $selectedCurrencies = $user->currencies()->pluck('currency_id');

        return view('preferences', compact('currencies', 'selectedCurrencies'));
    }

    public function update(Request $request)
    {
        // Get the selected cryptocurrencies chosen by the user from the request
        $selectedCurrencies = $request->input('selectedCurrencies');

        // Validate the selected cryptocurrencies
        $validatedData = $request->validate([
            'selectedCurrencies' => 'required|array|min:1|max:5'
        ]);

        // Check if the number of selected cryptocurrencies is within the allowed range
    if (count($selectedCurrencies) > 5) {
        return redirect()->back()->withErrors('You can select a maximum of 5 cryptocurrencies.');
    } elseif (count($selectedCurrencies) === 0) {
        return redirect()->back()->withErrors('Please select at least one cryptocurrency.');
    }

        // Clear the user's previously selected cryptocurrencies
        $user = auth()->user();
        $user->currencies()->detach();

        // Add the newly selected cryptocurrencies
        foreach ($selectedCurrencies as $currencyId) {
            $user->currencies()->attach($currencyId);
        }

        // Redirect back with a success message
        return redirect()->back()->with('status', 'Preferences updated successfully.');
    }

}
