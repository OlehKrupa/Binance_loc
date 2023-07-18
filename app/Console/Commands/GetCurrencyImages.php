<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Currency;

class GetCurrencyImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'currency:fill';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fill the currency database with full names and image URLs';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Get the list of supported cryptocurrencies from the configuration file
        $support = config('services.cryptocurrencies');

        // Convert the supported cryptocurrencies to lowercase for case-insensitive comparison
        $lowercaseSupport = array_map('strtolower', $support);

        // Send a GET request to the CoinGecko API to get cryptocurrency data
        $response = Http::get('https://api.coingecko.com/api/v3/coins/markets?vs_currency=usd&order=market_cap_desc&per_page=100&page=1&sparkline=false&locale=en');

        // Check if the request was successful
        if ($response->successful()) {
            // Convert the response to JSON
            $cryptocurrencies = $response->json();

            // Filter the cryptocurrencies to only include the supported ones
            $supportedCryptocurrencies = array_filter($cryptocurrencies, function ($cryptocurrency) use ($lowercaseSupport) {
                return in_array($cryptocurrency['symbol'], $lowercaseSupport);
            });

            // Update the database records for the supported cryptocurrencies
            foreach ($supportedCryptocurrencies as $cryptocurrency) {
                Currency::where('name', $cryptocurrency['symbol'])->update([
                    'full_name' => $cryptocurrency['name'],
                    'image_url' => $cryptocurrency['image'],
                    'updated_at' => now()
                ]);
            }

            $this->info('Currency database filled successfully.');
        } else {
            // Display an error message if the request was not successful
            $this->error('Error executing the request: ' . $response->status() . ' ' . $response->body());
        }
    }
}