<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('daemon:ticker {symbol=BTC/USDT : The desired symbol to fetch prices, BTC/USDT if nothing is specified.}', function ($symbol) {
    $this->info("Fetching ticker price for {$symbol}@binance!
Press ctrl+c to exit any time.");

    $daemon = new \App\Daemons\DaemonTicker($symbol);
    $daemon->loop();
})->purpose('Fetch ticker price for any symbol at binance.');
