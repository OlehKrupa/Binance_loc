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

    /**
     * Get the subscribed users.
     *
     * @return \Illuminate\Support\Collection
     */
    public function subscribedUsers()
    {
        return User::whereNotNull('subscribed_at')->get();
    }

    /**
     * Get users with Telegram IDs.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getUsersTelegramId()
    {
        return User::where('telegram_Id', '!=', 0)->get();
    }

    /**
     * Update the "subscribed_at" field for the user.
     *
     * @param \App\Models\User $user
     * @param \DateTime|null $subscribedAt
     * @return void
     */
    public function updateSubscribedAt(User $user)
    {
        if ($user->subscribed_at !== null) {
            $user->subscribed_at = null;
        } else {
            $user->subscribed_at = now();
        }
        $user->save();
    }

    /**
     * Get the currencies associated with the user.
     *
     * @param \App\Models\User $user
     * @return \Illuminate\Support\Collection
     */
    public function getUserCurrencies(User $user)
    {
        return $user->currencies()->pluck('currency_id');
    }

    /**
     * Detach all currencies associated with the user.
     *
     * @param \App\Models\User $user
     * @return void
     */
    public function detachCurrencies(User $user)
    {
        $user->currencies()->detach();
    }

    /**
     * Attach a currency to the user.
     *
     * @param \App\Models\User $user
     * @param int $currencyId
     * @return void
     */
    public function attachCurrency(User $user, int $currencyId)
    {
        $user->currencies()->attach($currencyId);
    }
}