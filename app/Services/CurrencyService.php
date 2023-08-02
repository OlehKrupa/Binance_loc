<?php

namespace App\Services;

use App\Repositories\CurrencyRepository;
use App\Services\BaseService;

class CurrencyService extends BaseService
{
    public function __construct(CurrencyRepository $repo)
    {
        $this->repo = $repo;
    }

    /**
     * Get all currency IDs.
     *
     * @return array
     */
    public function getAllCurrenciesId()
    {
        return $this->repo->getAllCurrenciesId();
    }

    public function getAllCurrenciesTrendPrice()
    {
        return $this->repo->getAllCurrenciesTrendPrice();
    }
}