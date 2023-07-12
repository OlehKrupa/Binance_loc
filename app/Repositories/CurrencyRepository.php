<?php
namespace App\Repositories;

use App\Models\Currency;
use App\Models\User;

class CurrencyRepository
{
    public function getById($id)
    {
        return Currency::find($id);
    }

    public function getAllCurrencies()
    {
        return Currency::all();
    }

    public function getAllCurrenciesId()
    {
        return Currency::all()->pluck('id')->toArray();
    }

    public function create($data)
    {
        return Currency::create($data);
    }

    public function update($id, $data)
    {
        $currency = Currency::find($id);
        if ($currency) {
            $currency->update($data);
            return $currency;
        }
        return null;
    }

    public function delete($id)
    {
        return Currency::destroy($id);
    }
}