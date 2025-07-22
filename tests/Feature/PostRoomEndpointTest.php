<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\RateLimiter;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;
use Tests\Traits\RefreshMongoDatabase;

class PostRoomEndpointTest extends TestCase
{
    use RefreshMongoDatabase;

    const ENDPOINT = '/';

    public function test_create_room_endpoint_creates_room_and_redirects(): void
    {
        $resp = $this->withoutMiddleware(['throttle'])
            ->followingRedirects()
            ->post('/');

        $resp->assertSessionHas('roomId');

        $resp->assertInertia(
            fn (Assert $page) => $page
                ->whereNull('playerId')
        );
    }

    public function test_endpoint_is_rate_limited_by_ip(): void
    {
        RateLimiter::clear('room:create|127.0.0.1');

        $rateLimit = 10;

        for ($i = 0; $i < $rateLimit; $i++) {
            $this->post(self::ENDPOINT)
                ->assertRedirect();
        }

        $this->post(self::ENDPOINT)
            ->assertTooManyRequests();

        // Test with different IP, should not be limited.
        $this->post(self::ENDPOINT, [], ['REMOTE_ADDR' => '123.45.67.89'])
            ->assertRedirect();
    }
}
