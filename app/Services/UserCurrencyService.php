<?php
namespace App\Services;

use App\Repositories\UserCurrencyRepository;
use App\Services\BaseService;

class UserCurrencyService extends BaseService
{
    public function __construct(UserCurrencyRepository $repo)
    {
        $this->repo = $repo;
    }

    public function createUserCurrency($data)
    {
        return $this->repo->create($data);
    }

    public function deleteUserCurrency($userId, $currencyId)
    {
        return $this->repo->delete($userId, $currencyId);
    }
}
