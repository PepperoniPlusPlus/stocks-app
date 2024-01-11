<?php

namespace App\Http\Controllers;

use App\Enums\CacheKeys;
use App\Models\Stock;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

class StocksContoller extends Controller
{
    public function index()
    {
        $stocksWithPriceChanges = Cache::get(CacheKeys::StockPriceChanges->value);

        if (! $stocksWithPriceChanges) {
            // this fetches each stock with a list of prices, we add a 3-minute condition to avoid fetching a big list of prices
            $stockWithLatestPrices = Stock::query()
                ->with('prices', function ($query) {
                    $query
                        ->whereDate('created_at', '>=', now()->subMinutes(3))
                        ->orderBy('created_at', 'DESC');
                })
                ->get()
                ->toArray();

            $stocksWithPriceChanges = [];

            foreach ($stockWithLatestPrices as $stockWithLatestPrice) {
                $latestPrices = $stockWithLatestPrice['prices'];
                // with array_shift we fetch the first 2 elements of the list which are going to be the latest (current) and previous price
                $currentPrice = array_shift($latestPrices)['price'];
                $previousPrice = array_shift($latestPrices)['price'];
                $stocksWithPriceChanges[] = [
                    'ticker' => $stockWithLatestPrice['ticker'],
                    'price_evolution' => (($currentPrice - $previousPrice) / ($previousPrice)) / 100.
                ];
            }

            // cache for 1 minute, it expires next time we update
            Cache::put(CacheKeys::StockPriceChanges->value, $stocksWithPriceChanges, 60 * 60);
        }


        return response()->json(['data' => $stocksWithPriceChanges], Response::HTTP_OK);
    }
}
