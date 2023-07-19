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

    /**
     * Create a new currency history record.
     *
     * @param int $currencyId
     * @param float $sellPrice
     * @param float $buyPrice
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function createCurrencyHistory($currencyId, $sellPrice, $buyPrice)
    {
        $data = [
            'currency_id' => $currencyId,
            'sell' => $sellPrice,
            'buy' => $buyPrice,
        ];

        return $this->repo->create($data);
    }

    /**
     * Get the unique currency IDs from the CurrencyHistory repository.
     *
     * @return array
     */
    public function getUniqueCurrenciesId(): array
    {
        return $this->repo->getUniqueCurrenciesId();
    }

    /**
     * Get the last recorded currencies for the selected currencies.
     *
     * @param array $selectedCurrencies
     * @return \Illuminate\Support\Collection
     */
    public function getLastCurrencies($selectedCurrencies)
    {
        return $this->repo->getLastCurrencies($selectedCurrencies);
    }

    /**
     * Get the currencies recorded within the specified hours for the selected currencies.
     *
     * @param array $selectedCurrencies
     * @param int $hours
     * @return \Illuminate\Support\Collection
     */
    public function getHourCurrencies($selectedCurrencies, $hours)
    {
        return $this->repo->getHourCurrencies($selectedCurrencies, $hours);
    }

    /**
     * Analyze the currency trend for the selected currencies within the current day.
     *
     * @param array $selectedCurrencies
     * @return array
     */
    public function analyzeCurrencyTrend($selectedCurrencies)
    {
        return $this->repo->analyzeCurrencyTrend($selectedCurrencies);
    }
}