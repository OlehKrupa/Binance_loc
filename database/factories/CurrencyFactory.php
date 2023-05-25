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
        $cryptocurrencies = [
            'BTC', 'ETH', 'BNB', 'XRP', 'DOGE', 'ADA', 'LTC', 'DOT', 'BCH', 'LINK',
            'XLM', 'USDT', 'VET', 'ETC', 'XMR', 'EOS', 'TRX', 'THETA', 'FIL', 'AAVE',
            'XTZ', 'MIOTA', 'ATOM', 'CRO', 'MKR', 'ALGO', 'SOL', 'NEO', 'SNX', 'AVAX',
            'UNI', 'COMP', 'SUSHI', 'YFI', 'CRV', 'REN', 'UMA', 'BAL', 'KSM', 'ICX',
            'ZEC', 'DASH', 'OMG', 'GRT', 'SC', 'SRM', 'ENJ', 'QTUM', 'MANA', 'HOT',
        ];

        $randomCurrency = $this->faker->unique()->randomElement($cryptocurrencies);

        return [
            'name' => $randomCurrency,
        ];
    }
}
