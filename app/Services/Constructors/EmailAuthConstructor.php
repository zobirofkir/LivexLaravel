<?php

namespace App\Services\Constructors;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\SendLoginLinkRequest;
use App\Http\Requests\Auth\VerifyOTPRequest;
use App\Http\Resources\Auth\GetCurrentAuthUserResource;
use App\Http\Resources\Auth\LoginResource;
use App\Http\Resources\Auth\SendOtpResource;
use App\Http\Resources\Auth\VerifyOtpResource;

interface EmailAuthConstructor
{
    /**
     * Get the authenticated user.
     * @return GetCurrentAuthUserResource
     */
    public function getUser(): GetCurrentAuthUserResource;

    /**
     * Send OTP to the user.
     * @param SendLoginLinkRequest $request
     * @return SendOtpResource
     */
    public function sendOTP(SendLoginLinkRequest $request): SendOtpResource;

    /**
     * Verify the OTP sent to the user.
     * @param VerifyOTPRequest $request
     * @return VerifyOtpResource
     */
    public function verifyOTP(VerifyOTPRequest $request): VerifyOtpResource;

    /**
     * Login the user using the provided credentials.
     * @param LoginRequest $request
     * @return LoginResource
     */
    public function login(LoginRequest $request): LoginResource;
}