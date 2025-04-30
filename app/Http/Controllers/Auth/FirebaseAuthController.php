<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\FirebaseAuthService;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\SendOTPRequest;
use App\Http\Requests\Auth\VerifyOTPRequest;
use App\Http\Resources\Auth\FirebaseAuthResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use App\Http\Resources\SendLoginLinkResource;
use App\Http\Resources\SendOtpResource;
use App\Http\Resources\VerifyLoginResource;
use App\Http\Resources\VerifyOtpResource;
use App\Http\Resources\GetCurrentAuthUserResource;

class FirebaseAuthController extends Controller
{
    protected $firebaseAuth;

    public function __construct(FirebaseAuthService $firebaseAuth)
    {
        $this->firebaseAuth = $firebaseAuth;
    }

    public function register(RegisterRequest $request): FirebaseAuthResource
    {
        $user = $this->firebaseAuth->createUser(
            $request->email,
            $request->password,
            $request->name
        );

        return FirebaseAuthResource::make($user);
    }

    public function sendLoginLink(Request $request): SendLoginLinkResource
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $result = $this->firebaseAuth->sendSignInLink($request->email);
        return SendLoginLinkResource::make($result);
    }

    public function verifyLogin(Request $request): VerifyLoginResource
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'oobCode' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $result = $this->firebaseAuth->verifySignInLink($request->email, $request->oobCode);
        return VerifyLoginResource::make($result);
    }

    public function getUser(Request $request): GetCurrentAuthUserResource
    {
        $user = $request->user();
        return GetCurrentAuthUserResource::make($user);
    }

    public function sendOTP(SendOTPRequest $request): SendOtpResource
    {
        $result = $this->firebaseAuth->sendOTP($request->email);
        return SendOtpResource::make($result);
    }

    public function verifyOTP(VerifyOTPRequest $request): VerifyOtpResource
    {
        $result = $this->firebaseAuth->verifyOTP($request->email, $request->otp);
        return VerifyOtpResource::make($result);
    }

    public function login(LoginRequest $request): LoginResource
    {
        $result = $this->firebaseAuth->login($request->email, $request->password);
        return LoginResource::make($result);
    }
} 