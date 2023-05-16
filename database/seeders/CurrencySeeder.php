<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Currency::create([
            'name' => 'BTC',
        ]);

        Currency::create([
            'name' => 'ETH',
        ]);

        Currency::create([
            'name' => 'XRM',
        ]);

        Currency::create([
            'name' => 'USDT',
        ]);

        Currency::create([
            'name' => 'LTC',
        ]);
    }
}
