<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\PhoneAuthRequest;
use App\Http\Requests\Auth\VerifyPhoneOtpRequest;
use App\Http\Resources\Auth\PhoneAuthResource;
use App\Http\Resources\Auth\VerifyPhoneOtpResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Str;


class PhoneAuthController extends Controller
{
    public function sendOTP(PhoneAuthRequest $request)
    {
        $request->validated();

        $phone = $request->phone_number;
        $otp = rand(100000, 999999);
        
        Cache::put('otp_' . $phone, $otp, now()->addMinutes(5));

        return PhoneAuthResource::make($otp);
    }

    public function verifyOTP(VerifyPhoneOtpRequest $request)
    {
        $request->validated();

        $phone = $request->phone_number;
        $otp = $request->otp;
        $storedOTP = Cache::get('otp_' . $phone);

        if (!$storedOTP || $storedOTP != $otp) {
            return response()->json([
                'message' => 'Invalid OTP'
            ], 422);
        }

        Cache::forget('otp_' . $phone);

        $password = 'user' . rand(10000, 99999);

        $user = User::updateOrCreate(
            ['phone_number' => $phone],
            [
                'password' => Hash::make($password),
                'name' => 'User_' . Str::random(5),
                'email' => $phone . '@phone.com'
            ]
        );

        return VerifyPhoneOtpResource::make($user);
    }

    public function login(Request $request)
    {
        $request->validate([
            'phone_number' => 'required|string|min:10|max:15',
            'password' => 'required|string'
        ]);

        $user = User::where('phone_number', $request->phone_number)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user
        ]);
    }
}
