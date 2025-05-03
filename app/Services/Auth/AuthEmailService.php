<?php

namespace App\Services\Auth;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\Token;
use Laravel\Passport\PersonalAccessTokenResult;
use App\Http\Requests\Auth\SendLoginLinkRequest;
use App\Http\Requests\Auth\VerifyLoginLinkRequest;
use App\Http\Requests\Auth\VerifyOTPRequest;
use App\Http\Requests\Auth\LoginRequest;

class AuthEmailService
{
    public function createUser($email, $password, $name)
    {
        $hashedPassword = Hash::make(trim($password));
        
        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => $hashedPassword,
        ]);

        Log::info('User created', [
            'email' => $email,
            'name' => $name,
            'raw_password' => $password,
            'hashed_password' => $hashedPassword
        ]);

        return $user;
    }

    public function sendSignInLink(SendLoginLinkRequest $request)
    {
        $email = $request->email;
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        Cache::put('signin_' . $email, $otp, now()->addMinutes(5));
        
        Mail::send('emails.signin', ['otp' => $otp], function($message) use ($email) {
            $message->to($email)->subject('Your Sign-in Link');
        });
        
        return [
            'message' => 'Sign-in link sent to your email',
            'email' => $email
        ];
    }

    public function verifySignInLink(VerifyLoginLinkRequest $request)
    {
        $email = $request->email;
        $oobCode = $request->oobCode;
        
        $storedOTP = Cache::get('signin_' . $email);
        
        if (!$storedOTP) {
            throw new \Exception('Sign-in link expired or not found');
        }
        
        if ($storedOTP !== $oobCode) {
            throw new \Exception('Invalid sign-in code');
        }
        
        Cache::forget('signin_' . $email);
        $user = $this->getOrCreateUser($email);
        
        $token = $user->createToken('Passport Token')->accessToken;
        
        return [
            'token' => $token,
            'user' => $user
        ];
    }

    public function sendOTP(SendLoginLinkRequest $request)
    {
        $email = $request->email;
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        Cache::put('otp_' . $email, $otp, now()->addMinutes(5));
        
        Mail::send('emails.otp', ['otp' => $otp], function($message) use ($email) {
            $message->to($email)->subject('Your OTP Code');
        });
        
        return [
            'email' => $email
        ];
    }

    public function verifyOTP(VerifyOTPRequest $request)
    {
        $email = $request->email;
        $otp = $request->otp;
        $password = $request->password;
        
        $storedOTP = Cache::get('otp_' . $email);
        
        if (!$storedOTP) {
            throw new \Exception('OTP expired or not found');
        }
        
        if ($storedOTP !== $otp) {
            throw new \Exception('Invalid OTP');
        }
        
        Cache::forget('otp_' . $email);
        $user = $this->getOrCreateUser($email, $password);
        
        $this->sendPasswordEmail($email, $password);
        
        return [
            'user' => $user
        ];
    }

    public function login(LoginRequest $request)
    {
        $validatedData = $request->validated();
        $user = User::where('email', $validatedData['email'])->first();

        if (!$user || !Hash::check($validatedData['password'], $user->password)) {
            abort(401);
        }

        return [
            'user' => $user
        ];
    }


    protected function getOrCreateUser($email, $password = null)
    {
        $user = User::where('email', $email)->first();
        
        if (!$user) {
            $password = $password ? trim($password) : Str::random(10);
            $hashedPassword = Hash::make($password);
            
            $user = User::create([
                'email' => $email,
                'password' => $hashedPassword,
            ]);
        }
        
        return $user;
    }

    protected function sendWelcomeEmail($email, $password)
    {
        Mail::send('emails.welcome', [
            'email' => $email,
            'password' => $password
        ], function($message) use ($email) {
            $message->to($email)->subject('Welcome to Our Platform - Your Login Details');
        });
    }

    protected function sendPasswordEmail($email, $password)
    {
        Mail::send('emails.password', [
            'email' => $email,
            'password' => $password
        ], function($message) use ($email) {
            $message->to($email)->subject('Your Account Password');
        });
    }
} 