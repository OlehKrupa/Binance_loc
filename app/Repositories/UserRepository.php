<?php

namespace App\Repositories;

use App\Repositories\BaseRepository;
use App\Models\User;

class UserRepository extends BaseRepository
{
    public function __construct(User $model)
    {
        $this->model = $model;
    }

    public function subscribedUsers()
    {
        return User::whereNotNull('subscribed_at')->get();
    }

    public function getUsersTelegramId()
    {
        return User::where('telegram_Id', '!=', 0)->get();
    }

    public function getUserCurrencies(User $user)
    {
        return $user->currencies()->pluck('currency_id');
    }

    public function detachCurrencies(User $user)
    {
        $user->currencies()->detach();
    }

    public function attachCurrency(User $user, int $currencyId)
    {
        $user->currencies()->attach($currencyId);
    }
}
