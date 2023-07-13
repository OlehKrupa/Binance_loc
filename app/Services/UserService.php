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

    public function getSubscribedUsers()
    {
        return $this->repo->subscribedUsers();
    }

    public function getUserCurrencies(User $user)
    {
        return $this->repo->getUserCurrencies($user);
    }
    
    public function getUsersTelegramId()
    {
        return $this->repo->getUsersTelegramId();
    }

    public function detachCurrencies(User $user)
    {
        return $this->repo->detachCurrencies($user);
    }

    public function attachCurrency(User $user, int $currencyId)
    {
        return $this->repo->attachCurrency($user, $currencyId);
    }
}
