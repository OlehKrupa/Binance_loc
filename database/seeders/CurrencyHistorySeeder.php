<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CurrencyHistorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CurrencyHistory::create([
            'currency_id' => 1,
            'sell' => 1.2,
            'buy' => 1.1,
        ]);

        CurrencyHistory::create([
            'currency_id' => 2,
            'sell' => 0.9,
            'buy' => 0.8,
        ]);

        CurrencyHistory::create([
            'currency_id' => 3,
            'sell' => 1.3,
            'buy' => 1.4,
        ]);

        CurrencyHistory::create([
            'currency_id' => 4,
            'sell' => 1,
            'buy' => 1.1,
        ]);

        CurrencyHistory::create([
            'currency_id' => 5,
            'sell' => 1.5,
            'buy' => 1.6,
        ]);

        CurrencyHistory::create([
            'currency_id' => 6,
            'sell' => 1.9,
            'buy' => 1.8,
        ]);
    }
}
