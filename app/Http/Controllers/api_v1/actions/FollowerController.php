<?php

namespace App\Http\Controllers\api_v1\actions;

use App\Http\Controllers\Controller;
use App\Http\Resources\FollowerResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\Facades\FollowerFacade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FollowerController extends Controller
{
    public function follow(Request $request, User $user)
    {
        $currentUser = $request->user();

        if ($currentUser->isFollowing($user)) {
            return response()->json([], 400);
        }

        $follower = FollowerFacade::follow($currentUser, $user);

        return new FollowerResource($follower);
    }

    public function unfollow(Request $request, User $user)
    {
        $currentUser = $request->user();
        
        $result = FollowerFacade::unfollow($currentUser, $user);

        return response()->json(['result' => $result]);
    }

    public function followers(User $user)
    {
        $followers = FollowerFacade::getFollowers($user);
        return UserResource::collection($followers);
    }

    public function following(User $user)
    {
        $following = FollowerFacade::getFollowing($user);
        return UserResource::collection($following);
    }

    public function isFollowing(Request $request, User $user)
    {
        $currentUser = $request->user();
        $isFollowing = FollowerFacade::isFollowing($currentUser, $user);
        return response()->json(['is_following' => $isFollowing]);
    }
}