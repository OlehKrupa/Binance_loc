<?php

namespace App\Repositories;

use App\Repositories\BaseRepository;
use App\Models\Currency;

class CurrencyRepository extends BaseRepository
{
    public function __construct(Currency $model)
    {
        $this->model = $model;
    }

    /**
     * Get all currency IDs.
     *
     * @return array
     */
    public function getAllCurrenciesId()
    {
        return Currency::all()->pluck('id')->toArray();
    }
}