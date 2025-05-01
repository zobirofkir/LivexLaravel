<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\Auth\PassportAuthService;
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

class PassportAuthController extends Controller
{
    protected $authService;

    public function __construct(PassportAuthService $authService)
    {
        $this->authService = $authService;
    }

    public function register(RegisterRequest $request): PassportAuthResource
    {
        $user = $this->authService->createUser(
            $request->email,
            $request->password,
            $request->name
        );

        return PassportAuthResource::make($user);
    }

    public function sendLoginLink(SendLoginLinkRequest $request): SendLoginLinkResource
    {
        $result = $this->authService->sendSignInLink($request);
        return SendLoginLinkResource::make($result);
    }

    public function verifyLogin(VerifyLoginLinkRequest $request): VerifyLoginResource
    {
        $result = $this->authService->verifySignInLink($request);
        return VerifyLoginResource::make($result);
    }

    public function getUser(): GetCurrentAuthUserResource
    {
        $user = auth()->user();
        return GetCurrentAuthUserResource::make($user);
    }

    public function sendOTP(SendLoginLinkRequest $request): SendOtpResource
    {
        $result = $this->authService->sendOTP($request);
        return SendOtpResource::make($result);
    }

    public function verifyOTP(VerifyOTPRequest $request): VerifyOtpResource
    {
        $result = $this->authService->verifyOTP($request);
        return VerifyOtpResource::make($result);
    }

    public function login(LoginRequest $request): LoginResource
    {
        $result = $this->authService->login($request);
        return LoginResource::make($result);
    }
} 