<?php

namespace App\Services\Services;

use App\Services\Auth\AuthEmailService;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\SendLoginLinkRequest;
use App\Http\Requests\Auth\VerifyOTPRequest;
use App\Http\Resources\Auth\LoginResource;
use App\Http\Resources\Auth\SendOtpResource;
use App\Http\Resources\Auth\VerifyOtpResource;
use App\Services\Constructors\EmailAuthConstructor;

class EmailAuthService implements EmailAuthConstructor
{
    /**
     * @var AuthEmailService
     */
    protected $authService;

    /**
     * Constructor for EmailAuthService.
     * @param AuthEmailService $authService
     */
    public function __construct(AuthEmailService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Send OTP to the user.
     * @param SendLoginLinkRequest $request
     * @return SendLoginLinkResource
     */
    public function sendOTP(SendLoginLinkRequest $request): SendOtpResource
    {
        $result = $this->authService->sendOTP($request);
        return SendOtpResource::make($result);
    }

    /**
     * Verify the OTP sent to the user.
     * @param VerifyLoginLinkRequest $request
     * @return VerifyLoginResource
     */
    public function verifyOTP(VerifyOTPRequest $request): VerifyOtpResource
    {
        $result = $this->authService->verifyOTP($request);
        return VerifyOtpResource::make($result);
    }

    /**
     * Login the user using the provided credentials.
     * @param LoginRequest $request
     * @return LoginResource
     */
    public function login(LoginRequest $request): LoginResource
    {
        $result = $this->authService->login($request);
        return LoginResource::make($result);
    }
}