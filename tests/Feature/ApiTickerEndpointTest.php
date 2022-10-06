<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Ticker;

class ApiTickerEndpointTest extends TestCase
{
    use RefreshDatabase;
    
    /**
     * Test ticker api endpoint
     *
     * @return void
     */
    public function test_ticker_returns_a_successful_response()
    {
        $response = $this->get('/api/v1/ticker?symbol=btcusd');

        $response->assertStatus(200);
        $response->assertJsonStructure(['msg', 'success']);
    }

    /**
     * test invalid query parameter for ticker endpoint
     * 
     * @return void
     */

    public function test_ticker_invalid_query_parameter()
    {
        $response = $this->get('/api/v1/ticker?symbol=');

        $response->assertStatus(200);
        $response->assertJsonStructure(['msg', 'success']);
        $response->assertJson(['success' => false, 'msg' => 'Symbol is required']);
    }

    /**
     * test valid query parameter for ticker endopoint
     * 
     * @return void
     */

    public function test_ticker_valid_query_parameter()
    {
        $response = $this->get('/api/v1/ticker?symbol=SYMBOLONOEXISTE');

        $response->assertStatus(200);
        $response->assertJsonStructure(['msg', 'success', 'data']);
        $response->assertJson(['success' => true, 'msg' => '', 'data' => []]);


        Ticker::createOrUpdate(
            [
                'symbol' => 'BTC/USDT',
                'time' => '2022-10-05 23:03:30',
            ],
            [
                'price' => 20100,
            ]);
        Ticker::createOrUpdate(
            [
                'symbol' => 'BTC/USDT',
                'time' => '2022-10-05 23:03:40',
            ],
            [
                'price' => 20120,   // this is the one that should be shown
            ]);
        
        Ticker::createOrUpdate(
            [
                'symbol' => 'BTC/USDT',
                'time' => '2022-10-05 23:02:40',    // THE NEWEST REGISTER HAVING AN OLDER DATE SHOULD NOT BE RETURNED BY THE ENDPOINT
            ],
            [
                'price' => 20111,
            ]);

        $response = $this->get('/api/v1/ticker?symbol=BTC/USDT');

        $response->assertJsonStructure(['msg', 'success', 'data']);
        $response->assertJson(['success' => true, 'msg' => '', 'data' => ['price' => 20120]]);
    }
}
