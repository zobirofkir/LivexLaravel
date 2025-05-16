<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LiveController extends Controller
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
