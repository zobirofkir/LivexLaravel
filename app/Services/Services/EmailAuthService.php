<?php

namespace App\Services\Services;

use App\Services\Auth\AuthEmailService;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\SendLoginLinkRequest;
use App\Http\Requests\Auth\VerifyLoginLinkRequest;
use App\Http\Requests\Auth\VerifyOTPRequest;
use App\Http\Resources\Auth\LoginResource;
use App\Http\Resources\Auth\PassportAuthResource;
use App\Http\Resources\Auth\SendLoginLinkResource;
use App\Http\Resources\Auth\SendOtpResource;
use App\Http\Resources\Auth\VerifyLoginResource;
use App\Http\Resources\Auth\VerifyOtpResource;
use App\Http\Resources\Auth\GetCurrentAuthUserResource;
use App\Services\Constructors\EmailAuthConstructor;
use Illuminate\Support\Facades\Auth;

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
     * Get the current authenticated user.
     * @return GetCurrentAuthUserResource
     */
    public function getUser(): GetCurrentAuthUserResource
    {
        $user = Auth::user();
        return GetCurrentAuthUserResource::make($user);
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
     * Register a new user.
     * @param RegisterRequest $request
     * @return PassportAuthResource
     */
    public function login(LoginRequest $request): LoginResource
    {
        $result = $this->authService->login($request);
        return LoginResource::make($result);
    }
}