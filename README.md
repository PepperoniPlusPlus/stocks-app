# Stocks App

This document will explain the components, decisions and mechanism for the Laravel app.

### Design Decisions & Components

The project runs on Laravel Sail which provides a native way to Dockerize the application.

The database is made up of a `stocks` table that holds the tickers we want to check throughout our app and a `stock_prices` table that holds the price points at different times of the day. I opted for this design to have an easy way to check both historical and current prices of any stock. Another way would be to have 1 table holding both ticker and price but would be prone to issues with heavy writes and requiring extra logic to keep record of changes.

There are two Kernel jobs:

 - `CacheStockTickers` which caches the stock and their symbols every hour
 - `FetchLatestStockValues` which fetches the latest stock prices every 1 minute

Stock price evolution is fetched through a controller `StocksController`.

The cache layer uses redis and is used to save both the list of stocks and the response of the controller which is cached for 1 minute since stocks are updated every minute.

A service under `app/Services/AlphaVantage` was created to act as a client to fetch stock prices.

Laravel Breeze was used to scaffold user authentication utilizing Sanctum.

### UI

The UI was built using Vue.js, the only UI component is the User profile dashboard (in Dashboard.vue) which shows a table with tickers, their prices, and their price change trend.

### Running the code

First step is installing the dependencies:
```
composer install
```
Then we build the project using Sail and npm (at the project root)
```
./vendor/bin/sail up
npm install
npm run build
```
Tests can be run using:
```
./vendor/bin/sail test
```

