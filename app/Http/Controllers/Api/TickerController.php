<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ticker;
use Illuminate\Http\Request;

class TickerController extends Controller
{
    /**
     * Display the latest trade price of a symbol
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function ticker(Request $request)
    {
        return ['price' => 0, 'time' => ''];
    }

    /**
     * Display the price history
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function historic(Request $request)
    {
        return [['price' => 0, 'time' => '']];
    }
}
