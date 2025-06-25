<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class LiveKitService
{
    protected $baseUrl;
    protected $apiKey;
    protected $apiSecret;

    public function __construct()
    {
        $this->baseUrl = config('services.livekit.url', 'http://localhost:7880');
        $this->apiKey = config('services.livekit.api_key');
        $this->apiSecret = config('services.livekit.api_secret');
    }

    public function createRoom($name)
    {
        $response = Http::withBasicAuth($this->apiKey, $this->apiSecret)
            ->post("{$this->baseUrl}/rooms", [
                'name' => $name,
            ]);
        return $response->json();
    }

    public function listRooms()
    {
        $response = Http::withBasicAuth($this->apiKey, $this->apiSecret)
            ->get("{$this->baseUrl}/rooms");
        return $response->json();
    }
}
