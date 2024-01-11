<?php

namespace App\Console\Commands;

use App\Models\Stock;
use App\Models\StockPrice;
use App\Services\AlphaVantage\ApiClient;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class FetchLatestStockValues extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fetch-latest-stock-values';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tickers = Cache::get('stock-tickers');

        if (! $tickers) {
            $tickers = Stock::query()->get()->pluck('id', 'ticker')->toArray();
            Cache::put('stock-tickers', $tickers);
        }

        $client = new ApiClient();

        foreach ($tickers as $ticker => $stockId) {
            $price = $client->fetchTickerPrice($ticker);
            StockPrice::query()->create([
                'stock_id' => $stockId,
                'price' => $price,
            ]);
        }
    }
}
