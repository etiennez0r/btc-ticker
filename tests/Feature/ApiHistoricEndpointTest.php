<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Ticker;

class ApiHistoricEndpointTest extends TestCase
{
    use RefreshDatabase;
    
    /**
     * Test historic api endpoint
     *
     * @return void
     */
    public function test_historic_prices_api_endpoint_successful_response()
    {
        $response = $this->get('/api/v1/historic?symbol=btcusd');

        $response->assertStatus(200);
        $response->assertJsonStructure(['msg', 'success']);
    }

    /**
     * test invalid query parameter for historic endpoint
     * 
     * @return void
     */

    public function test_historic_invalid_query_parameter()
    {
        $response = $this->get('/api/v1/historic?symbol=');

        $response->assertStatus(200);
        $response->assertJsonStructure(['msg', 'success']);
        $response->assertJson(['success' => false, 'msg' => 'Symbol is required']);
    }

    /**
     * test valid query parameter for historic endopoint
     * 
     * @return void
     */

    public function test_historic_valid_query_parameter()
    {
        $response = $this->get('/api/v1/historic?symbol=SYMBOLONOEXISTE');

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
                'price' => 20110,
            ]);
        
        Ticker::createOrUpdate(
            [
                'symbol' => 'BTC/USDT',
                'time' => '2022-10-05 23:03:40',    // SAME EXACT SECOND SHOULD UPDATE INSTEAD OF CREATE
            ],
            [
                'price' => 20111,
            ]);

        $response = $this->get('/api/v1/historic?symbol=BTC/USDT');

        $response->assertJsonStructure(['msg', 'success', 'data']);
        $response->assertJson(['success' => true, 'msg' => '']);
        $response->assertJsonCount(2, 'data');
    }
}
