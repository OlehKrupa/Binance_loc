<?php

namespace Database\Factories;

// database/factories/CurrencyHistoryFactory.php

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Currency;
use App\Models\CurrencyHistory;

class CurrencyHistoryFactory extends Factory
{
    protected $model = CurrencyHistory::class;

    public function definition(): array
    {
        return [
            'currency_id' => Currency::factory()->create()->id,
            'sell' => $this->faker->randomFloat(2, 1, 100),
            'buy' => $this->faker->randomFloat(2, 1, 100),
        ];
    }
}