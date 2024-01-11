<?php

namespace App\Enums;

enum CacheKeys: string
{
    case Stocks = 'stock-tickers';
    case StockPriceChanges = 'stock-price-changes';
}
