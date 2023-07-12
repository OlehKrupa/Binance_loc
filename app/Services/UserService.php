<?php

namespace App\Services;

use App\Repositories\UserRepository;
use App\Models\User;

class UserService
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getSubscribedUsers()
    {
        return $this->userRepository->subscribedUsers();
    }

    public function getUserCurrencies(User $user)
    {
        return $this->userRepository->getUserCurrencies($user);
    }
    
    public function getUsersTelegramId()
    {
        return $this->userRepository->getUsersTelegramId();
    }

    public function getUserById($id)
    {
        return $this->userRepository->getById($id);
    }

    public function clearSelectedCurrencies(User $user)
    {
        $user->currencies()->detach();
    }

    public function addSelectedCurrency(User $user, int $currencyId)
    {
        $user->currencies()->attach($currencyId);
    }

    public function createUser($data)
    {
        return $this->userRepository->create($data);
    }

    public function updateUser($id, $data)
    {
        return $this->userRepository->update($id, $data);
    }

    public function deleteUser($id)
    {
        return $this->userRepository->delete($id);
    }
}
