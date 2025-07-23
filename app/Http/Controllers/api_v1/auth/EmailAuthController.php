<?php

namespace App\Http\Controllers\api_v1\auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\SendLoginLinkRequest;
use App\Http\Requests\Auth\VerifyOTPRequest;
use App\Http\Resources\Auth\LoginResource;
use App\Http\Resources\Auth\SendOtpResource;
use App\Http\Resources\Auth\VerifyOtpResource;
use App\Services\Facades\EmailAuthFacade;

class EmailAuthController extends Controller
{
    /**
     * Send OTP to the user.
     * @param SendLoginLinkRequest $request
     * @return SendOtpResource
     */
    public function sendOTP(SendLoginLinkRequest $request): SendOtpResource
    {
        return EmailAuthFacade::sendOTP($request);
    }

    /**
     * Verify the OTP sent to the user.
     * @param VerifyOTPRequest $request
     * @return VerifyOtpResource
     */
    public function verifyOTP(VerifyOTPRequest $request): VerifyOtpResource
    {
        return EmailAuthFacade::verifyOTP($request);
    }

    /**
     * Login the user using the provided credentials.
     * @param LoginRequest $request
     * @return LoginResource
     */
    public function login(LoginRequest $request): LoginResource
    {
        return EmailAuthFacade::login($request);
    }
}  