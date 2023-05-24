<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Currency;
use App\Models\TableName;
use Faker\Generator as Faker;

class UserCurrencyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::all()->random()->id,
            'currency_id'=>Currency::all()->random()->id,
            'created_at'=>now(),
            'updated_at'=>now(),
        ];
    }
}
