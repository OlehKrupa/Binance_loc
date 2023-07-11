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
        return $this->userRepository->getSelectedCurrencies($user);
    }

    /*
    //полиморфизм вышел из чата
    public function getUserCurrencies(int $id)
    {
        return $this->userRepository->getSelectedCurrencies($id);
    }
    */

    public function getUserById($id)
    {
        return $this->userRepository->getById($id);
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
