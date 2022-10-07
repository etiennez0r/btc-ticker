## BTC Live Ticker

This app fetch the price of the ticker BTC/USDT from Binance, every 10 seconds and saves it to the database.

Tha backend is in Laravel, and the frontend in Inertia framework and React.

[LIVE DEMO](https://btc-ticker.daemontrader.com/)

## Installation

Open a terminal and execute the following commands:

- git clone https://github.com/etiennez0r/btc-ticker
- cd btc-ticker
- composer install
- cp .env.example .env
- php artisan key:generate

Now you should have all the required source files in your system

## Database Creation

Open a terminal and execute the following commands:

- sudo -i -u postgres
- createuser btcticker
- createdb btcticker
- psql
- alter user btcticker with encrypted password 'ticker123';
- exit
- logout

Now open the file .env and set the following variables to these values:

- APP_NAME="BTC live ticker"
- DB_CONNECTION=pgsql
- DB_HOST=127.0.0.1
- DB_PORT=5432
- DB_DATABASE=btcticker
- DB_USERNAME=btcticker
- DB_PASSWORD=ticker123

## Final steps

Now in the terminal execute the following commands to generate the assets and migrate the database:

- php artisan migrate
- npm i
- npm run build

Now test the application with the following command in the terminal:

- vendor/bin/phpunit

## Usage

To start fetching the ticker BTC/USDT and save every fetch to the database execute the command in the terminal:
- php artisan daemon:ticker

To open the live site execute the command in the terminal:
- php artisan serve

If you want to fetch prices automatically in the background (instead of executing php artisan daemon:ticker) execute the command:
- crontab -e

And add the following line:

\* \* \* \* \* cd /var/www/btc-ticker/ && php artisan schedule:run >> /dev/null 2>&1

## Api routes

To view all the available routes execute
- php artisan route:list

There are only two routes for the API:
- api/v1/historical which returns the last 100 prices registered.
- api/v1/ticker which returns the last registered price.


## Disclaimer

This app has been developed for the Apanio Coding Challenge.
