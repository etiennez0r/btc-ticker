<?php

namespace App\Daemons;
use Illuminate\Support\Carbon;
use App\Models\Ticker;

use Exception;

define('D_TITLE', 'tickerd');

class DaemonTicker extends Daemon
{
    protected $dname = D_TITLE;
    protected $period = 1; // run every 1 secs;
    protected $logchannel = D_TITLE;
    protected $connections = [];
    protected $symbol = 'BTC/USDT';

    public function run()
    {
        $second = Carbon::now()->format("s");
        
        if ($second % 10 == 0)
            $this->saveTicker();
        
        return true;
    }

    public function __construct()
    {
        \cli_set_process_title($this->dname);

        $this->connections['binance'] = new \App\Broker('binance');
    }

    protected function saveTicker()
    {
        try {
            list($price, $datetime) = $this->connections['binance']->fetchTicker($this->symbol);
            

            $this->saveLastTicker($price, $datetime);
        } catch(Exception $e) {
            $this->log()->error("error fetching binance ticker $this->symbol: " . $e->getMessage());
            $this->reportError("error fetching binance ticker $this->symbol: " . $e->getMessage());
        }
    }

    protected function saveLastTicker($price, $datetime)
    {
        if ($price && $datetime) {
            $ret = Ticker::updateOrSave(
                                [
                                    'symbol' => $this->symbol,
                                    'time' => $datetime
                                ],
                                [
                                    'price' => $price
                                ]
            );

            if ($ret)
                $c = 0;
        }
    }
}
