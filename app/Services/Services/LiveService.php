<?php

namespace App\Services\Services;

use App\Services\Constructors\LiveConstructor;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class LiveService implements LiveConstructor
{
    public function goLive()
    {
        $user = Auth::user();
        $user->is_live = true;
        $user->save();

        return response()->json(['message' => 'User is now live']);
    }

    public function stopLive()
    {
        $user = Auth::user();
        $user->is_live = false;
        $user->save();

        return response()->json(['message' => 'User stopped live']);
    }

    public function getLiveUsers()
    {
        $users = User::where('is_live', true)->get();
        return response()->json($users);
    }
}