<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Currency;
use App\Services\CurrencyService;
use Illuminate\Support\Facades\DB;

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
     * The CurrencyService instance.
     *
     * @var CurrencyService
     */
    private $currencyService;

    /**
     * Create a new command instance.
     *
     * @param CurrencyService $currencyService
     */
    public function __construct(
        CurrencyService $currencyService,
    ) {
        parent::__construct();

        $this->currencyService = $currencyService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Start the transaction
        DB::beginTransaction();

        try {
            // Get the list of supported cryptocurrencies from the configuration file
            $support = config('services.cryptocurrencies');

            // Convert the supported cryptocurrencies to lowercase for case-insensitive comparison
            $lowercaseSupport = array_map('strtolower', $support);

            // Send a GET request to the CoinGecko API to get cryptocurrency data
            $response = Http::get(env('ALL_COINS_DATA_URL'));

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
                // Commit the transaction if everything is successful
                DB::commit();

                $this->info('Currency database filled successfully.');
            } else {
                // Display an error message if the request was not successful
                $this->error('Error executing the request: ' . $response->status() . ' ' . $response->body());
            }

        } catch (\Exception $e) {
            // Rollback the transaction if an exception occurs
            DB::rollback();

            $this->error('An error occurred while updating currency images: ' . $e->getMessage());
        }
    }
}