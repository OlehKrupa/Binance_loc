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

    /**
     * Create a new user currency record.
     *
     * @param array $data
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function createUserCurrency($data)
    {
        return $this->repo->create($data);
    }

    /**
     * Delete a user currency record.
     *
     * @param int $userId
     * @param int $currencyId
     * @return bool
     */
    public function deleteUserCurrency($userId, $currencyId)
    {
        return $this->repo->delete($userId, $currencyId);
    }
}