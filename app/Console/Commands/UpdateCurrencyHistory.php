<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\CurrencyService;
use App\Services\CurrencyHistoryService;

class UpdateCurrencyHistory extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'currency:update-history';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update currency history from Coinbase API';

    /**
     * The CurrencyService instance.
     *
     * @var CurrencyService
     */
    private $currencyService;

    /**
     * The CurrencyHistoryService instance.
     *
     * @var CurrencyHistoryService
     */
    private $currencyHistoryService;

    /**
     * Create a new command instance.
     *
     * @param CurrencyService $currencyService
     * @param CurrencyHistoryService $currencyHistoryService
     */
    public function __construct(
        CurrencyService $currencyService,
        CurrencyHistoryService $currencyHistoryService
    ) {
        parent::__construct();

        $this->currencyService = $currencyService;
        $this->currencyHistoryService = $currencyHistoryService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Get all currencies from the CurrencyService
        $currencies = $this->currencyService->all();

        foreach ($currencies as $currency) {
            $currencyCode = $currency->name;

            // Construct the buy and sell URLs using the Coinbase API URL and currency code
            $buyUrl = env('COINBASE_API_URL') . "{$currencyCode}-USD/buy";
            $sellUrl = env('COINBASE_API_URL') . "{$currencyCode}-USD/sell";

            // Fetch the buy and sell prices using the fetchPrice function
            $buyPrice = $this->fetchPrice($buyUrl);
            $sellPrice = $this->fetchPrice($sellUrl);
            $currencyId = $currency->id;

            // Create the currency history entry using the CurrencyHistoryService
            $currencyHistory = $this->currencyHistoryService->createCurrencyHistory($currencyId, $sellPrice, $buyPrice);
        }

        $this->info('Currency history updated successfully!');
    }

    /**
     * Fetches the price from the given URL using cURL.
     *
     * @param string $url
     * @return float
     */
    private function fetchPrice($url)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        $data = json_decode($response, true);

        curl_close($ch);

        if (isset($data['data']['amount'])) {
            return $data['data']['amount'];
        }

        return 0;
    }
}