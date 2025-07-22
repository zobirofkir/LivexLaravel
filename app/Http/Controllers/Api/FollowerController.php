<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Follower\FollowRequest;
use App\Http\Requests\Follower\UnfollowRequest;
use App\Models\User;
use App\Services\Facades\FollowerFacade;
use Illuminate\Support\Facades\Auth;

class FollowerController extends Controller
{
    public function follow(FollowRequest $request, User $user)
    {
        $follower = Auth::user();
        FollowerFacade::follow($follower, $user);
        return response()->json(['message' => 'Successfully followed user']);
    }

    public function unfollow(UnfollowRequest $request, User $user)
    {
        $follower = Auth::user();
        FollowerFacade::unfollow($follower, $user);
        return response()->json(['message' => 'Successfully unfollowed user']);
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