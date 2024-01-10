<?php

namespace App\Services\AlphaVantage;

use GuzzleHttp\Client;

class ApiClient
{
    private $httpClient;
    private $baseApiUrl;

    public function __construct()
    {
        $this->httpClient = new Client();

        $this->baseApiUrl = 'https://www.alphavantage.co/query';
    }

    public function fetchTickerPrice(string $ticker)
    {
        $queryParams = [
            'function' => 'TIME_SERIES_INTRADAY',
            'symbol' => $ticker,
            'interval' => '1min',
            'apikey' => config('alpha_vantage.api_key'),
        ];

        $url = $this->baseApiUrl.'?'.http_build_query($queryParams);

        try {
            $response = $this->httpClient->get($url);
        } catch (\Exception $exception) {
            return 0;
        }

        $latestValue = array_pop(json_decode($response->getBody(), true)['Time Series (1min)']);

        return floatval($latestValue['4. close']);
    }
}
