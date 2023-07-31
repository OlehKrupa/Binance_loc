<?php

namespace App\Services;

use App\Repositories\UserRepository;
use App\Models\User;
use App\Services\BaseService;

class UserService extends BaseService
{
    public function __construct(UserRepository $repo)
    {
        $this->repo = $repo;
    }

    /**
     * Get the subscribed users.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getSubscribedUsers()
    {
        return $this->repo->subscribedUsers();
    }

    /**
     * Get the currencies associated with the user.
     *
     * @param \App\Models\User $user
     * @return \Illuminate\Support\Collection
     */
    public function getUserCurrencies(User $user)
    {
        return $this->repo->getUserCurrencies($user);
    }

    /**
     * Get users with Telegram IDs.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getUsersTelegramId()
    {
        return $this->repo->getUsersTelegramId();
    }

    public function updateSubscribedAt(User $user)
    {
        return $this->repo->updateSubscribedAt($user);
    }

    /**
     * Detach all currencies associated with the user.
     *
     * @param \App\Models\User $user
     * @return void
     */
    public function detachCurrencies(User $user)
    {
        return $this->repo->detachCurrencies($user);
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
        return $this->repo->attachCurrency($user, $currencyId);
    }
}