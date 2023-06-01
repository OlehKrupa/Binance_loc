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
        // Получаем выбранные пользователем криптовалюты из запроса
        $selectedCurrencies = $request->input('currencies');

        // Валидация выбранных криптовалют
        $validatedData = $request->validate([
            'currencies' => 'required|array|max:5'
        ]);

        // Очищаем предыдущие выбранные криптовалюты пользователя
        $user = auth()->user();
        $user->currencies()->detach();

        // Добавляем новые выбранные криптовалюты
        foreach ($selectedCurrencies as $currencyId) {
            $user->currencies()->attach($currencyId);
        }

        return redirect()->back()->with('status', 'Preferences updated successfully.');
    }
}
