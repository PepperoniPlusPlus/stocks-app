<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $tickers = [
            'AAPl',
            'MSFT',
            'GOOG',
            'IBM',
            'AMZN',
            'DIS',
            'NFLX',
            'ABNB',
            'TSLA',
            'F',
        ];

        foreach ($tickers as $ticker) {
            \App\Models\Stock::factory()->create([
                'ticker' => $ticker,
            ]);
        }
    }
}
