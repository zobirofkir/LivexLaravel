<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Follower\FollowRequest;
use App\Http\Requests\Follower\UnfollowRequest;
use App\Models\User;
use App\Services\Facades\FollowerFacade;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class FollowerController extends Controller
{
    public function follow(Request $request, User $user)
    {
        $currentUser = $request->user();

        // Check if the user is trying to follow themselves
        if ($currentUser->id === $user->id) {
            return response()->json(['message' => 'You cannot follow yourself'], Response::HTTP_BAD_REQUEST);
        }

        // Check if the user is already following
        if ($currentUser->isFollowing($user)) {
            return response()->json(['message' => 'You are already following this user'], Response::HTTP_BAD_REQUEST);
        }

        // Perform the follow action
        $currentUser->follow($user);

        return response()->json(['message' => 'Successfully followed the user'], Response::HTTP_CREATED);
    }

    public function unfollow(Request $request, User $user)
    {
        Log::info('Unfollow method called');

        try {
            $follower = Auth::user();
            
            if (!$follower) {
                Log::error('No authenticated user found');
                return response()->json(['message' => 'Unauthenticated'], 401);
            }

            Log::info("Unfollow attempt", ['follower_id' => $follower->id, 'following_id' => $user->id]);

            $result = FollowerFacade::unfollow($follower, $user);
            
            Log::info("Unfollow result", ['result' => $result]);

            if ($result) {
                return response()->json(['message' => 'Successfully unfollowed user'], 200);
            } else {
                return response()->json(['message' => 'User was not being followed'], 400);
            }
        } catch (\Exception $e) {
            Log::error("Unfollow error", ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json(['message' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }

    public function followers(User $user)
    {
        $followers = FollowerFacade::getFollowers($user);
        return response()->json($followers);
    }

    public function following(User $user)
    {
        $following = FollowerFacade::getFollowing($user);
        return response()->json($following);
    }

    public function isFollowing(User $user)
    {
        $follower = Auth::user();
        $isFollowing = FollowerFacade::isFollowing($follower, $user);
        return response()->json(['is_following' => $isFollowing]);
    }
}