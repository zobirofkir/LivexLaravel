<?php

namespace App\Services\Constructors;

use App\Http\Requests\Auth\PhoneAuthLoginRequest;
use App\Http\Requests\Auth\PhoneAuthRequest;
use App\Http\Requests\Auth\VerifyPhoneOtpRequest;
use App\Http\Resources\Auth\PhoneAuthLoginResource;
use App\Http\Resources\Auth\PhoneAuthResource;
use App\Http\Resources\Auth\VerifyPhoneOtpResource;

interface PhoneAuthConstructor
{
    /**
     * Send OTP to the user's phone number.
     *
     * @param PhoneAuthRequest $request
     * @return PhoneAuthResource
     */
    public function sendOTP(PhoneAuthRequest $request): PhoneAuthResource;

    /**
     * Verify the OTP sent to the user's phone number.
     *
     * @param VerifyPhoneOtpRequest $request
     * @return VerifyPhoneOtpResource
     */
    public function verifyOTP(VerifyPhoneOtpRequest $request): VerifyPhoneOtpResource;

    /**
     * Log in the user using their phone number and OTP.
     *
     * @param PhoneAuthLoginRequest $request
     * @return PhoneAuthLoginResource
     */
    public function login(PhoneAuthLoginRequest $request): PhoneAuthLoginResource;
}