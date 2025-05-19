<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Video;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
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
        Storage::fake('videos');
        
        $data = [
            "title" => "first reel",
            "video_url" => UploadedFile::fake()->create('video.mp4', 1000, 'video/mp4'),
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
        Storage::fake('videos');
        
        // Create a video with a proper file path that would be stored in the database
        $videoFile = UploadedFile::fake()->create('original.mp4', 1000, 'video/mp4');
        $videoPath = $videoFile->store('videos');
        
        $video = Video::create([
            'title' => 'test title',
            'video_url' => $videoPath,
            'user_id' => Auth::id(), 
        ]);
    
        $updateVideo = [
            "title" => "updated reel",
            "video_url" => UploadedFile::fake()->create('updated.mp4', 1000, 'video/mp4'),
        ];
    
        $response = $this->postJson("api/auth/user/reels/{$video->id}", $updateVideo);
        
        $response->assertStatus(200);
    }

    /**
     * test Delete Video
     */
    public function testDeleteVideo()
    {
        $this->authenticate();
        Storage::fake('videos');
        
        $videoFile = UploadedFile::fake()->create('delete_test.mp4', 1000, 'video/mp4');
        $videoPath = $videoFile->store('videos');
        
        $video = Video::create([
            'title' => 'test title',
            'video_url' => $videoPath,
            'user_id' => Auth::id(), 
        ]);

        $response = $this->delete("api/auth/user/reels/{$video->id}");

        $response->assertStatus(200);
    }
}
