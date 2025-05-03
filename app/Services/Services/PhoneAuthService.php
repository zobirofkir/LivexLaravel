<?php

namespace App\Services\Services;

use App\Services\Constructors\PhoneAuthConstructor;
use App\Http\Requests\Auth\PhoneAuthLoginRequest;
use App\Http\Requests\Auth\PhoneAuthRequest;
use App\Http\Requests\Auth\VerifyPhoneOtpRequest;
use App\Http\Resources\Auth\PhoneAuthLoginResource;
use App\Http\Resources\Auth\PhoneAuthResource;
use App\Http\Resources\Auth\VerifyPhoneOtpResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Str;
use Kreait\Firebase\Factory;

class PhoneAuthService implements PhoneAuthConstructor
{
    /**
     * Send OTP to the user's phone number.
     *
     * @param PhoneAuthRequest $request
     * @return PhoneAuthResource
     */
    public function sendOTP(PhoneAuthRequest $request): PhoneAuthResource
    {
        $request->validated();

        $phone = $request->phone_number;

        $factory = (new Factory)->withServiceAccount(storage_path('app/firebase-service-account.json'));
        $auth = $factory->createAuth();

        $user = $auth->getUserByPhoneNumber($phone);

        $customToken = $auth->createCustomToken($user->uid);

        $otp = rand(100000, 999999);
        Cache::put('otp_' . $phone, $otp, now()->addMinutes(5));

        return PhoneAuthResource::make(['otp' => $otp]);
    }

    /**
     * Verify the OTP sent to the user's phone number.
     *
     * @param VerifyPhoneOtpRequest $request
     * @return VerifyPhoneOtpResource
     */
    public function verifyOTP(VerifyPhoneOtpRequest $request): VerifyPhoneOtpResource
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

        return VerifyPhoneOtpResource::make($password);
    }

    /**
     * Log in the user using their phone number and OTP.
     *
     * @param PhoneAuthLoginRequest $request
     * @return PhoneAuthLoginResource
     */
    public function login(PhoneAuthLoginRequest $request): PhoneAuthLoginResource
    {
        $request->validated();

        $user = User::where('phone_number', $request->phone_number)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            abort(401);
        }

        return PhoneAuthLoginResource::make($user);
    }

}