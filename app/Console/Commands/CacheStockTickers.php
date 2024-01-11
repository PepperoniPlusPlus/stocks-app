<?php

namespace App\Console\Commands;

use App\Models\Stock;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class CacheStockTickers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:cache-stock-tickers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Caches list of stock tickers in DB';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $stocks = Stock::query()->get()->pluck('id', 'ticker')->toArray();
        Cache::put('stock-tickers', $stocks);
    }
}
