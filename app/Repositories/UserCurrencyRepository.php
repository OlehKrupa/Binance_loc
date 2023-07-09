<?php
namespace App\Repositories;

use App\Models\UserCurrency;

class UserCurrencyRepository
{
    public function create($data)
    {
        return UserCurrency::create($data);
    }

    public function delete($userId, $currencyId)
    {
        return UserCurrency::where('user_id', $userId)
            ->where('currency_id', $currencyId)
            ->delete();
    }
}
