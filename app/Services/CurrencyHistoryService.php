<?php
namespace App\Services;

use App\Repositories\CurrencyHistoryRepository;
use App\Services\BaseService;

class CurrencyHistoryService extends BaseService
{
    public function __construct(CurrencyHistoryRepository $repo)
    {
        $this->repo = $repo;
    }

    public function createCurrencyHistory($currencyId, $sellPrice, $buyPrice)
    {
        $data = [
            'currency_id' => $currencyId,
            'sell' => $sellPrice,
            'buy' => $buyPrice,
        ];

        return $this->repo->create($data);
    }

    public function getUniqueCurrenciesId(): array
    {
        return $this->repo->getUniqueCurrenciesId();
    }

    public function getLastCurrencies($selectedCurrencies)
    {
        return $this->repo->getLastCurrencies($selectedCurrencies);
    }

    public function getDayCurrencies($selectedCurrencies, $days)
    {
        return $this->repo->getDayCurrencies($selectedCurrencies, $days);
    }

    public function analyzeCurrencyTrend($selectedCurrencies)
    {
        return $this->repo->analyzeCurrencyTrend($selectedCurrencies);
    }
}
