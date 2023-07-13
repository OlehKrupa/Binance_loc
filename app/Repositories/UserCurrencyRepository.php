<?php
namespace App\Repositories;

use App\Repositories\BaseRepository;
use App\Models\UserCurrency;

class UserCurrencyRepository extends BaseRepository
{
    public function __construct(UserCurrency $model)
    {
        $this->model = $model;
    }
}
