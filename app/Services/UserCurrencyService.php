<?php
namespace App\Services;

use App\Repositories\UserCurrencyRepository;

class UserCurrencyService
{
    protected $userCurrencyRepository;

    public function __construct(UserCurrencyRepository $userCurrencyRepository)
    {
        $this->userCurrencyRepository = $userCurrencyRepository;
    }

    public function createUserCurrency($data)
    {
        return $this->userCurrencyRepository->create($data);
    }

    public function deleteUserCurrency($userId, $currencyId)
    {
        return $this->userCurrencyRepository->delete($userId, $currencyId);
    }
}
