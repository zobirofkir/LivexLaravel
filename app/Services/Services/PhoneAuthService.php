<?php

namespace App\Services\Services;

use App\Services\Auth\AuthPhoneService;
use App\Services\Constructors\PhoneAuthConstructor;
use App\Http\Requests\Auth\PhoneAuthLoginRequest;
use App\Http\Requests\Auth\PhoneAuthRequest;
use App\Http\Requests\Auth\VerifyPhoneOtpRequest;
use App\Http\Resources\Auth\PhoneAuthLoginResource;
use App\Http\Resources\Auth\PhoneAuthResource;
use App\Http\Resources\Auth\VerifyPhoneOtpResource;

class PhoneAuthService implements PhoneAuthConstructor
{
    /**
     * @var AuthPhoneService
     */
    protected $authService;

    /**
     * Constructor for PhoneAuthService.
     * @param AuthPhoneService $authService
     */
    public function __construct(AuthPhoneService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Send OTP to the user's phone number.
     *
     * @param PhoneAuthRequest $request
     * @return PhoneAuthResource
     */
    public function sendOTP(PhoneAuthRequest $request): PhoneAuthResource
    {
        $result = $this->authService->sendOTP($request);
        return PhoneAuthResource::make($result);
    }

    /**
     * Verify the OTP sent to the user's phone number.
     *
     * @param VerifyPhoneOtpRequest $request
     * @return VerifyPhoneOtpResource
     */
    public function verifyOTP(VerifyPhoneOtpRequest $request): VerifyPhoneOtpResource
    {
        $result = $this->authService->verifyOTP($request);
        return VerifyPhoneOtpResource::make($result);
    }

    /**
     * Log in the user using their phone number and OTP.
     *
     * @param PhoneAuthLoginRequest $request
     * @return PhoneAuthLoginResource
     */
    public function login(PhoneAuthLoginRequest $request): PhoneAuthLoginResource
    {
        $result = $this->authService->login($request);
        return PhoneAuthLoginResource::make($result);
    }
}