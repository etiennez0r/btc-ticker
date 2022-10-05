<?php

namespace App;

use Illuminate\Support\Facades\Log;
use Exception;

class Broker
{
    protected ?string $brokerid = null;
    protected ?string $apikey = null;
    protected ?string $apisecret = null;
    protected int $maxNetworkAttempts = 2;
    protected $exchange = null;
    protected int $requests = 0;
    protected ?string $brokerclass = null;

    public function __construct(?string $brokerid = null, ?string $apikey = null, ?string $apisecret = null)
    {
        $this->brokerid = $brokerid;
        $this->apikey = $apikey;
        $this->apisecret = $apisecret;
        
        if ($brokerid) {
            $class = self::getBroker($brokerid)['class'];
            
            if ($class) {
                $options = ['apiKey' => $apikey, 'secret' => $apisecret];
                $this->brokerclass = $class;
                $exchangeclass = "\\ccxt\\$class";

                // if ($class == 'binance')
                //     $options['options'] = ['defaultType'=>'future'];
                    
                $this->exchange = new $exchangeclass($options);
            }

            if (self::getBrokers()[$brokerid]['testnet'])
                $this->exchange->urls['api'] = $this->exchange->urls['test'];
        }
    }

    public static function getBrokers() : array
    {
        return [
            'binance'  => [
                'id' => 'binance',
                'name' => 'Binance',
                'testnet' => false,
                'class' => 'binance'
            ],
        ];
    }

    public static function getBroker(string $brokerid) : ?array
    {
        $brokers = self::getBrokers();

        return isset($brokers[$brokerid]) ? $brokers[$brokerid] : null;
    }

    function fetchTicker(string $symbol) : array
    {
        $ticker = ['last' => null, 'datetime' => null];

        if ($this->exchange) {
            try {
                $ticker = $this->exchange->fetch_ticker($symbol);
                $this->requests++;
            } catch (\ccxt\NetworkError $e) {
                $msg = $this->exchange->id . ' ticker dio error de RED: ' . $e->getMessage ();
                Log::error($msg);
                $ticker = ['error' => $msg];
            } catch (\ccxt\ExchangeError $e) {
                $msg = $this->exchange->id . ' ticker dio error de EXCHANGE: ' . $e->getMessage ();
                Log::error($msg);
                $ticker = ['error' => $msg];
            } catch (Exception $e) {
                $msg = $this->exchange->id . ' ticker dio fallo por: ' . $e->getMessage ();
                Log::error($msg);
                $ticker = ['error' => $msg];
            }
        }

        return [$ticker['last'], $ticker['datetime']];
    }

    public function requestsMade() : int
    {
        return $this->requests;
    }
}
