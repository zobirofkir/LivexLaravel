<?php

namespace App\Services;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Auth;
use Kreait\Firebase\Exception\Auth\FailedToVerifyToken;
use Kreait\Firebase\Exception\Auth\UserNotFound;
use App\Models\User;
use Illuminate\Support\Str;

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
        try {
            // Check if user exists in Firebase, if not create them
            try {
                $firebaseUser = $this->auth->getUserByEmail($email);
            } catch (\Kreait\Firebase\Exception\Auth\UserNotFound $e) {
                // Create user in Firebase if they don't exist
                $userProperties = [
                    'email' => $email,
                    'emailVerified' => false,
                    'disabled' => false,
                ];
                $firebaseUser = $this->auth->createUser($userProperties);
            }

            // Generate and send sign-in link
            $actionCodeSettings = [
                'url' => config('app.url') . '/verify-email',
                'handleCodeInApp' => true,
            ];

            // Send sign-in link
            $link = $this->auth->sendEmailVerificationLink($email, $actionCodeSettings);
            
            return [
                'message' => 'Sign-in link sent to your email',
                'email' => $email,
                'link' => $link
            ];
        } catch (\Exception $e) {
            throw new \Exception('Failed to send sign-in link: ' . $e->getMessage());
        }
    }

    public function verifySignInLink($email, $oobCode)
    {
        try {
            // Verify the sign-in link
            $signInResult = $this->auth->signInWithEmailLink($email, $oobCode);
            
            // Get the Firebase user
            $firebaseUser = $this->auth->getUser($signInResult->firebaseUserId());

            // Check if user exists in our database
            $user = User::where('email', $email)->first();
            if (!$user) {
                $user = User::create([
                    'email' => $email,
                    'password' => bcrypt(uniqid()),
                ]);
            }

            // Create Passport token
            $token = $user->createToken('Firebase Token')->accessToken;

            return [
                'token' => $token,
                'user' => [
                    'id' => $user->id,
                    'email' => $user->email,
                    'emailVerified' => $firebaseUser->emailVerified,
                ]
            ];
        } catch (FailedToVerifyToken $e) {
            throw new \Exception('Invalid verification code');
        } catch (\Exception $e) {
            throw new \Exception('Failed to verify sign-in link: ' . $e->getMessage());
        }
    }

    public function getUser($uid)
    {
        try {
            $firebaseUser = $this->auth->getUser($uid);
            $user = User::where('email', $firebaseUser->email)->first();
            
            if (!$user) {
                throw new \Exception('User not found in database');
            }

            return $user;
        } catch (UserNotFound $e) {
            throw new \Exception('User not found');
        }
    }

    public function verifyEmail($oobCode)
    {
        try {
            return $this->auth->verifyPasswordResetCode($oobCode);
        } catch (FailedToVerifyToken $e) {
            throw new \Exception('Invalid verification code');
        }
    }

    public function sendPasswordResetEmail($email)
    {
        try {
            $this->auth->sendPasswordResetLink($email);
            return true;
        } catch (\Exception $e) {
            throw new \Exception('Failed to send password reset email');
        }
    }

    public function sendOTP($email)
    {
        try {
            // Generate a 6-digit OTP
            $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
            
            // Store OTP in cache for 5 minutes
            \Cache::put('otp_' . $email, $otp, now()->addMinutes(5));
            
            // Send OTP via email
            \Mail::send('emails.otp', ['otp' => $otp], function($message) use ($email) {
                $message->to($email)
                        ->subject('Your OTP Code');
            });
            
            return [
                'message' => 'OTP sent to your email',
                'email' => $email
            ];
        } catch (\Exception $e) {
            throw new \Exception('Failed to send OTP: ' . $e->getMessage());
        }
    }

    public function verifyOTP($email, $otp)
    {
        try {
            $storedOTP = \Cache::get('otp_' . $email);
            
            if (!$storedOTP) {
                throw new \Exception('OTP expired or not found');
            }
            
            if ($storedOTP !== $otp) {
                throw new \Exception('Invalid OTP');
            }
            
            // Clear the OTP from cache
            \Cache::forget('otp_' . $email);

            // Check if user exists in our database
            $user = User::where('email', $email)->first();
            if (!$user) {
                // Generate a random password
                $generatedPassword = Str::random(10);
                
                $user = User::create([
                    'email' => $email,
                    'password' => bcrypt($generatedPassword),
                ]);

                // Log before sending email
                \Log::info('Attempting to send welcome email to: ' . $email);

                try {
                    // Send welcome email with password
                    \Mail::send('emails.welcome', [
                        'email' => $email,
                        'password' => $generatedPassword
                    ], function($message) use ($email) {
                        $message->to($email)
                                ->subject('Welcome to Our Platform - Your Login Details');
                    });

                    // Log successful email sending
                    \Log::info('Welcome email sent successfully to: ' . $email);
                } catch (\Exception $e) {
                    // Log email sending error
                    \Log::error('Failed to send welcome email: ' . $e->getMessage());
                    throw new \Exception('Failed to send welcome email: ' . $e->getMessage());
                }
            }
            
            return [
                'message' => 'OTP verified successfully. Welcome email sent.',
                'email' => $email
            ];
        } catch (\Exception $e) {
            \Log::error('OTP verification failed: ' . $e->getMessage());
            throw new \Exception('Failed to verify OTP: ' . $e->getMessage());
        }
    }

    public function login($email, $password)
    {
        try {
            // Verify credentials with Firebase
            $signInResult = $this->auth->signInWithEmailAndPassword($email, $password);
            
            // Get the Firebase user
            $firebaseUser = $this->auth->getUser($signInResult->firebaseUserId());
            
            // Check if user exists in our database
            $user = User::where('email', $email)->first();
            if (!$user) {
                throw new \Exception('User not found. Please verify your OTP first.');
            }
            
            // Create Passport token
            $token = $user->createToken('Firebase Token')->accessToken;
            
            return [
                'token' => $token,
                'user' => [
                    'id' => $user->id,
                    'email' => $user->email,
                    'emailVerified' => $firebaseUser->emailVerified,
                ]
            ];
        } catch (\Exception $e) {
            throw new \Exception('Failed to login: ' . $e->getMessage());
        }
    }
} 