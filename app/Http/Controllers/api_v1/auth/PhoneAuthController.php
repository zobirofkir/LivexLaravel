<?php

namespace App\Http\Controllers\api_v1\auth;

use App\Http\Controllers\Controller;

use App\Http\Requests\Auth\PhoneAuthLoginRequest;
use App\Http\Requests\Auth\PhoneAuthRequest;
use App\Http\Requests\Auth\VerifyPhoneOtpRequest;
use App\Http\Resources\Auth\PhoneAuthLoginResource;
use App\Http\Resources\Auth\PhoneAuthResource;
use App\Http\Resources\Auth\VerifyPhoneOtpResource;
use App\Services\Facades\PhoneAuthFacade;

class PhoneAuthController extends Controller
{
        /**
     * Send OTP to the user's phone number.
     *
     * @param PhoneAuthRequest $request
     * @return PhoneAuthResource
     */
    public function sendOTP(PhoneAuthRequest $request): PhoneAuthResource
    {
        return PhoneAuthFacade::sendOTP($request);
    }

    /**
     * Verify the OTP sent to the user's phone number.
     *
     * @param VerifyPhoneOtpRequest $request
     * @return VerifyPhoneOtpResource
     */
    public function verifyOTP(VerifyPhoneOtpRequest $request): VerifyPhoneOtpResource
    {
        return PhoneAuthFacade::verifyOTP($request);
    }

    /**
     * Log in the user using their phone number and OTP.
     *
     * @param PhoneAuthLoginRequest $request
     * @return PhoneAuthLoginResource
     */
    public function login(PhoneAuthLoginRequest $request): PhoneAuthLoginResource
    {
        return PhoneAuthFacade::login($request);
    }

}
