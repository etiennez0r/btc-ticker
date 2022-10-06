<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Ticker;

class HomePageTest extends TestCase
{
    // use RefreshDatabase;
    
    /**
     * Test home page response
     *
     * @return void
     */
    public function test_home_page_returns_a_successful_response()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
