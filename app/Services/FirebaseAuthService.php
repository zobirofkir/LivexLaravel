<?php

namespace App\Services;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Auth;
use Kreait\Firebase\Exception\Auth\FailedToVerifyToken;
use Kreait\Firebase\Exception\Auth\UserNotFound;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class FirebaseAuthService
{
    protected $auth;

    public function __construct()
    {
        $factory = (new Factory)
            ->withServiceAccount(config('firebase.credentials.file'));
        
        $this->auth = $factory->createAuth();
    }

    public function sendSignInLink($email)
    {
            $firebaseUser = $this->auth->getUserByEmail($email);

            $actionCodeSettings = [
                'url' => config('app.url') . '/verify-email',
                'handleCodeInApp' => true,
            ];

            $link = $this->auth->sendEmailVerificationLink($email, $actionCodeSettings);
            
            return [
                'message' => 'Sign-in link sent to your email',
                'email' => $email,
                'link' => $link
            ];
    }

    public function verifySignInLink($email, $oobCode)
    {
        $signInResult = $this->auth->signInWithEmailLink($email, $oobCode);
        
        $firebaseUser = $this->auth->getUser($signInResult->firebaseUserId());

        $user = User::where('email', $email)->first();
        if (!$user) {
            $user = User::create([
                'email' => $email,
                'password' => bcrypt(uniqid()),
            ]);
        }

        $token = $user->createToken('Firebase Token')->accessToken;

        return [
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'email' => $user->email,
                'emailVerified' => $firebaseUser->emailVerified,
            ]
        ];
    }

    public function getUser($uid)
    {
        $firebaseUser = $this->auth->getUser($uid);
        $user = User::where('email', $firebaseUser->email)->first();
        
        if (!$user) {
            throw new \Exception('User not found in database');
        }

        return $user;
    }

    public function verifyEmail($oobCode)
    {
        return $this->auth->verifyPasswordResetCode($oobCode);
    }

    public function sendPasswordResetEmail($email)
    {
        $this->auth->sendPasswordResetLink($email);
        return true;
    }

    public function sendOTP(string $email): array
    {
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        Cache::put('otp_' . $email, $otp, now()->addMinutes(5));
        
        Mail::send('emails.otp', ['otp' => $otp], function($message) use ($email) {
            $message->to($email)->subject('Your OTP Code');
        });
        
        return [
            'email' => $email
        ];
    }

    public function verifyOTP(string $email, string $otp): array
    {
        $storedOTP = Cache::get('otp_' . $email);
        
        if (!$storedOTP) {
            throw new \Exception('OTP expired or not found');
        }
        
        if ($storedOTP !== $otp) {
            throw new \Exception('Invalid OTP');
        }
        
        Cache::forget('otp_' . $email);
        $user = $this->getOrCreateUser($email);
        
        return [
            'user' => $user
        ];
    }

    public function login(string $email, string $password): array
    {
        $signInResult = $this->auth->signInWithEmailAndPassword($email, $password);
        $firebaseUser = $this->auth->getUser($signInResult->firebaseUserId());
        
        $user = User::where('email', $email)->first();
        if (!$user) {
            throw new \Exception('User not found. Please verify your OTP first.');
        }
        
        $token = $user->createToken('Firebase Token')->accessToken;
        
        return [
            'token' => $token,
            'user' => $user
        ];
    }

    protected function getOrCreateUser(string $email): User
    {
        $user = User::where('email', $email)->first();
        
        if (!$user) {
            $generatedPassword = Str::random(10);
            $user = User::create([
                'email' => $email,
                'password' => bcrypt($generatedPassword),
            ]);

            $this->sendWelcomeEmail($email, $generatedPassword);
        }
        
        return $user;
    }

    protected function sendWelcomeEmail(string $email, string $password): void
    {
        Mail::send('emails.welcome', [
            'email' => $email,
            'password' => $password
        ], function($message) use ($email) {
            $message->to($email)->subject('Welcome to Our Platform - Your Login Details');
        });
    }
} 