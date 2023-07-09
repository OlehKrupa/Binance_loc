<?php
namespace App\Services;

use App\Repositories\CurrencyHistoryRepository;

class CurrencyHistoryService
{
    protected $currencyHistoryRepository;

    public function __construct(CurrencyHistoryRepository $currencyHistoryRepository)
    {
        $this->currencyHistoryRepository = $currencyHistoryRepository;
    }

    public function createCurrencyHistory($currencyId, $sellPrice, $buyPrice)
    {
        $data = [
            'currency_id' => $currencyId,
            'sell' => $sellPrice,
            'buy' => $buyPrice,
        ];

        return $this->currencyHistoryRepository->create($data);
    }

    public function getLastCurrencies($selectedCurrencies)
    {
        return $this->currencyHistoryRepository->getLastCurrencies($selectedCurrencies);
    }

    public function getDayCurrencies($selectedCurrencies, $days)
    {
        return $this->currencyHistoryRepository->getDayCurrencies($selectedCurrencies, $days);
    }

    public function analyzeCurrencyTrend($selectedCurrencies)
    {
        return $this->currencyHistoryRepository->analyzeCurrencyTrend($selectedCurrencies);
    }
}
