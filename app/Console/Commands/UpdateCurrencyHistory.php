<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Currency;
use App\Models\CurrencyHistory;

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
     * Execute the console command.
     */
    public function handle()
    {
        $currencies = Currency::all();

        foreach ($currencies as $currency) {
            $currencyCode = $currency->name;
            $buyUrl = "https://api.coinbase.com/v2/prices/{$currencyCode}-USD/buy";
            $sellUrl = "https://api.coinbase.com/v2/prices/{$currencyCode}-USD/sell";

            $buyPrice = $this->fetchPrice($buyUrl);
            $sellPrice = $this->fetchPrice($sellUrl);

            $currencyHistory = new CurrencyHistory();
            $currencyHistory->currency_id = $currency->id;
            $currencyHistory->sell = $sellPrice;
            $currencyHistory->buy = $buyPrice;
            $currencyHistory->save();
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
