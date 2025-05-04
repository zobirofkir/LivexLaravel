<?php

namespace App\Services\Auth;

use App\Http\Requests\Auth\PhoneAuthLoginRequest;
use App\Http\Requests\Auth\PhoneAuthRequest;
use App\Http\Requests\Auth\VerifyPhoneOtpRequest;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Str;
use Kreait\Firebase\Factory;
use Twilio\Rest\Client;

class AuthPhoneService
{
    /**
     * Send OTP to the user's phone number.
     *
     * @param PhoneAuthRequest $request
     * @return array
     */
    public function sendOTP(PhoneAuthRequest $request): array
    {
        $phone = $request->phone_number;
    
        $factory = (new Factory)->withServiceAccount(storage_path('app/firebase-service-account.json'));
        $auth = $factory->createAuth();
    
        try {
            $user = $auth->getUserByPhoneNumber($phone);
        } catch (\Kreait\Firebase\Exception\Auth\UserNotFound $e) {
            $user = $auth->createUser([
                'phoneNumber' => $phone,
                'displayName' => 'User_' . Str::random(5),
            ]);
        }
    
        $auth->createCustomToken($user->uid);
    
        $otp = rand(100000, 999999);
        Cache::put('otp_' . $phone, $otp, now()->addMinutes(5));
    
        $twilio = new Client(env('TWILIO_SID'), env('TWILIO_TOKEN'));
        $twilio->messages->create($phone, [
            'from' => env('TWILIO_FROM'),
            'body' => "Your verification code is: {$otp}"
        ]);
    
        return ['otp' => $otp];
    }

    /**
     * Verify the OTP sent to the user's phone number.
     *
     * @param VerifyPhoneOtpRequest $request
     * @return array
     */
    public function verifyOTP(VerifyPhoneOtpRequest $request): array
    {
        $phone = $request->phone_number;
        $otp = $request->otp;
        $storedOTP = Cache::get('otp_' . $phone);

        if (!$storedOTP || $storedOTP != $otp) {
            return [
                'message' => 'Invalid OTP',
                'status' => 422
            ];
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

        return ['password' => $password];
    }

    /**
     * Log in the user using their phone number and OTP.
     *
     * @param PhoneAuthLoginRequest $request
     * @return array
     */
    public function login(PhoneAuthLoginRequest $request): array
    {
        $user = User::where('phone_number', $request->phone_number)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return [
                'message' => 'Invalid credentials',
                'status' => 401
            ];
        }

        return ['user' => $user];
    }
} 