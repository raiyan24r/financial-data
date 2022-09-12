<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BasicTest extends TestCase
{
    /**
     * Application Home Page test
     *
     * @return void
     */
    public function test_the_application_returns_a_successful_response()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    /**
     * Test the application returns a 404 response
     *
     * @return void
     */
    public function test_the_application_returns_a_404_response()
    {
        $response = $this->get('/non-existing-route');

        $response->assertStatus(404);
    }


}
