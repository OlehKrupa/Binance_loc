<?php

namespace Database\Factories;

use App\Models\Currency;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\currency>
 */
class CurrencyFactory extends Factory
{
    protected $model = Currency::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $cryptocurrencies = array(
            "BTC", "ETH", "BNB", "ADA", "XRP",
            "DOGE", "DOT", "BCH", "LTC", "LINK",
            "XLM", "MATIC", "ETC", "THETA", "VET",
            "FIL", "TRX", "XMR", "EOS", "NEO",
            "KLAY", "ATOM", "IOTA", "WBTC", "LUNA",
            "BSV", "AAVE", "XTZ", "FTT", "CRO",
            "ALGO", "MKR", "COMP", "AVAX", "TFUEL",
            "CAKE", "HT", "DAI", "BTT", "SUSHI",
            "ZEC", "EGLD", "YFI", "UMA"
        );

        $randomCurrency = $this->faker->unique()->randomElement($cryptocurrencies);

        return [
            'name' => $randomCurrency,
        ];
    }
}
