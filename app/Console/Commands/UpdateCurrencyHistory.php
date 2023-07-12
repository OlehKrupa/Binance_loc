<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\CurrencyService;
use App\Services\CurrencyHistoryService;
use Illuminate\Support\Facades\App;

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
        $currencies = $this->currencyService->getAllCurrencies();

        foreach ($currencies as $currency) {
            $currencyCode = $currency->name;

            $buyUrl = env('COINBASE_API_URL') . "{$currencyCode}-USD/buy";
            $sellUrl = env('COINBASE_API_URL') . "{$currencyCode}-USD/sell";

            //$buyUrl = "https://api.coinbase.com/v2/prices/{$currencyCode}-USD/buy";
            //$sellUrl = "https://api.coinbase.com/v2/prices/{$currencyCode}-USD/sell";

            $buyPrice = $this->fetchPrice($buyUrl);
            $sellPrice = $this->fetchPrice($sellUrl);
            $currencyId = $currency->id;

            $currencyHistory = $this->currencyHistoryService->createCurrencyHistory($currencyId, $sellPrice, $buyPrice);
        }

        $this->info('Currency history updated successfully!');
    }

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
