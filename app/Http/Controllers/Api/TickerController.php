<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ticker;
use Illuminate\Http\Request;

class TickerController extends Controller
{
    /**
     * Display the latest ticker price
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function ticker(Request $request)
    {
        $response = ['msg' => '', 'success' => false];
        $symbol = $request->get('symbol');

        if ($symbol) {
            $ticker = Ticker::where('symbol', '=', $symbol)
                                ->orderBy('time', 'desc')
                                ->first();

            $response['success'] = true;
            $response['data'] = $ticker;
        } else
            $response['msg'] = 'Symbol is required';

        return $response;
    }

    /**
     * Display the ticker history
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function historical(Request $request)
    {
        $response = ['msg' => '', 'success' => false];
        $symbol = $request->get('symbol');

        if ($symbol) {
            $tickers = Ticker::where('symbol', '=', $symbol)
                                ->orderBy('id', 'desc')
                                ->take(100)
                                ->get();

            $response['success'] = true;
            $response['data'] = $tickers;
        } else
            $response['msg'] = 'Symbol is required';

        return $response;
    }
}
