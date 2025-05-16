<?php

namespace App\Services\Services;

use App\Services\Constructors\LiveConstructor;

use App\Http\Resources\UserResource;
use App\Models\Live;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;


class LiveService implements LiveConstructor
{
    /**
     * Start User Live
     */
    public function goLive(): bool
    {
        $user = Auth::user();
    
        if ($user->is_live) {
            return true;
        }
    
        $user->is_live = true;
        $user->save();
    
        /**
         *  generate RTMP/LiveKit key
         */
        Live::create([
            'user_id' => $user->id,
            'stream_key' => Str::uuid(), 
            'title' => $user->name . "'s Live Stream",
            'started_at' => now(),
        ]);
    
        return true;
    }
    /**
     * Stop User Live
     */
    public function stopLive(): bool
    {
        $user = Auth::user();
        $user->is_live = false;
        $user->save();

        return true;
    }

    /**
     * Get Live Users
     */
    public function getLiveUsers(): AnonymousResourceCollection
    {
        $users = User::where('is_live', true)->get();
        return UserResource::collection($users);
    }

}