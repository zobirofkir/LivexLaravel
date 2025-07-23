<?php

namespace App\Http\Controllers\api_v1\live;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\LiveKitService;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class LiveKitTokenController extends Controller
{
    protected $liveKit;

    public function __construct(LiveKitService $liveKit)
    {
        $this->liveKit = $liveKit;
    }

    public function getToken(Request $request)
    {
        $request->validate([
            'room' => 'required|string',
        ]);

        $user = Auth::user();
        $roomName = $request->query('room');

        $apiKey = config('services.livekit.api_key');
        $apiSecret = config('services.livekit.api_secret');

        $now = time();
        $exp = $now + 3600;

        $payload = [
            'iss' => $apiKey,
            'sub' => (string)$user->id,
            'nbf' => $now,
            'exp' => $exp,
            'video' => [
                'roomJoin' => true,
                'room' => $roomName,
            ],
        ];

        $token = JWT::encode($payload, $apiSecret, 'HS256');

        return response()->json(['token' => $token]);
    }

    public function listRooms()
    {
        $rooms = $this->liveKit->listRooms();
        return response()->json($rooms);
    }

}
