<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiTest extends TestCase
{
    /**
     * Test ticker api endpoint
     *
     * @return void
     */
    public function test_the_application_returns_a_successful_response()
    {
        $response = $this->get('/api/v1/ticker?symbol=btcusd');

        $response->assertStatus(200);
        $response->assertJsonStructure(['price', 'time']);
    }

    /**
     * Test historic api endpoint
     *
     * @return void
     */
    public function test_historic_prices_successful_response()
    {
        $response = $this->get('/api/v1/historic?symbol=btcusd');

        $response->assertStatus(200);
        $response->assertJsonStructure([['price', 'time']]);
    }
}
