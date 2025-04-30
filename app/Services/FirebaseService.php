<?php

namespace App\Services;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Auth;
use Kreait\Firebase\Exception\Auth\FailedToVerifyToken;

class FirebaseService
{
    protected $auth;

    public function __construct()
    {
        $factory = (new Factory)
            ->withServiceAccount(storage_path('app/firebase-credentials.json'));

        $this->auth = $factory->createAuth();
    }

    public function verifyIdToken($idToken)
    {
        try {
            return $this->auth->verifyIdToken($idToken);
        } catch (FailedToVerifyToken $e) {
            return null;
        }
    }

    public function getUserByEmail($email)
    {
        try {
            return $this->auth->getUserByEmail($email);
        } catch (\Exception $e) {
            return null;
        }
    }
} 