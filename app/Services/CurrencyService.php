<?php
namespace App\Services;

use App\Repositories\CurrencyRepository;

class CurrencyService
{
    protected $currencyRepository;

    public function __construct(CurrencyRepository $currencyRepository)
    {
        $this->currencyRepository = $currencyRepository;
    }

    public function getCurrencyById($id)
    {
        return $this->currencyRepository->getById($id);
    }

    public function getAllCurrencies()
    {
        return $this->currencyRepository->getAllCurrencies();
    }

    public function getAllCurrenciesId()
    {
        return $this->currencyRepository->getAllCurrenciesId();
    }

    public function createCurrency($data)
    {
        return $this->currencyRepository->create($data);
    }

    public function updateCurrency($id, $data)
    {
        return $this->currencyRepository->update($id, $data);
    }

    public function deleteCurrency($id)
    {
        return $this->currencyRepository->delete($id);
    }
}
