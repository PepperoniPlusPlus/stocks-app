<?php

namespace Tests\Feature;

use App\Models\Stock;
use App\Models\StockPrice;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class StocksTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_fetch_stocks_with_latest_prices()
    {
        $stock = Stock::factory(1)->create()->first();

        StockPrice::factory(3)->create([
            'stock_id' => $stock->id,
        ]);

        $latestPrices = StockPrice::query()->orderBy('created_at', 'DESC')->limit(2)->pluck('price')->toArray();
        $priceEvolution = (($latestPrices[0] - $latestPrices[1]) / ($latestPrices[1])) / 100;

        Cache::spy();

        Cache::shouldReceive('put')->once();

        $response = $this->getJson('api/stocks');

        $response->assertOk();
        $response->assertJson([
            'data' => [
                [
                    'ticker' => $stock->ticker,
                    'price_evolution' => $priceEvolution,
                ]
            ]
        ]);

    }
}
