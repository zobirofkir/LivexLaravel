<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Exception\Auth\FailedToVerifyToken;

class VerifyFirebaseToken
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json(['error' => 'No token provided'], 401);
        }

        try {
            $factory = (new Factory)
                ->withServiceAccount(config('firebase.credentials.file'));
            
            $auth = $factory->createAuth();
            $verifiedToken = $auth->verifyIdToken($token);
            
            $request->merge(['user' => $verifiedToken->claims()->get('sub')]);
            
            return $next($request);
        } catch (FailedToVerifyToken $e) {
            return response()->json(['error' => 'Invalid token'], 401);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Authentication error'], 401);
        }
    }
} 