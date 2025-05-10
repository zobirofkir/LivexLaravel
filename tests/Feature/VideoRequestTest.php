<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Passport\Passport;
use Tests\TestCase;

class VideoRequestTest extends TestCase
{
    use RefreshDatabase;

    public function authenticate()
    {
        $user = User::factory()->create();
        Passport::actingAs($user);
    }

    /**
     * Test Create Reel
     */
    public function testCreateReel()
    {
        $this->authenticate();
        $data = [
            "title" => "first reel",
            "video_url" => "https://zobirofkir.com",
        ];
        $response = $this->post('api/auth/user/reels', $data);

        $response->assertStatus(201);
    }
}
