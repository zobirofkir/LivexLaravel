<?php

namespace App\Http\Controllers;

use App\Services\FirebaseService;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\Token;

class AuthController extends Controller
{
    protected $firebaseService;

    public function __construct(FirebaseService $firebaseService)
    {
        $this->firebaseService = $firebaseService;
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $firebaseUser = $this->firebaseService->getUserByEmail($request->email);
        
        if (!$firebaseUser) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $user = User::firstOrCreate(
            ['email' => $request->email],
            [
                'name' => $firebaseUser->displayName ?? $request->email,
                'password' => bcrypt($request->password)
            ]
        );

        $token = $user->createToken('auth-token')->accessToken;

        return response()->json([
            'user' => $user,
            'token' => $token
        ]);
    }

    public function user(Request $request)
    {
        return response()->json($request->user());
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json(['message' => 'Successfully logged out']);
    }

    public function refresh(Request $request)
    {
        $user = $request->user();
        $user->token()->revoke();
        $token = $user->createToken('auth-token')->accessToken;
        
        return response()->json([
            'token' => $token
        ]);
    }
}
