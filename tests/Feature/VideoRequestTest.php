<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Video;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
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

    /**
     * Test Get Vodeos
     */
    public function testGetVideos()
    {
        $this->authenticate();

        $videos = $this->get('api/auth/user/reels');
        $videos->assertStatus(200);
    }

    public function testUpdateVideo()
    {
        $this->authenticate();
    
        $video = Video::create([
            'title' => 'test title',
            'video_url' => 'https://zobirofkir.com',
            'user_id' => Auth::id(), 
        ]);
    
        $updateVideo = [
            "title" => "first reel",
            "video_url" => "https://zobirofkir.com",
        ];
    
        $response = $this->postJson("api/auth/user/reels/{$video->id}", $updateVideo);
        
        $response->assertStatus(200);
    }
}
