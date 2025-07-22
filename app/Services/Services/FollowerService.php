<?php

namespace App\Services\Services;

use App\Models\Follower;
use App\Models\User;
use App\Services\Constructors\FollowerConstructor;
use Illuminate\Support\Facades\Log;

class FollowerService implements FollowerConstructor
{
    public function follow(User $follower, User $following): Follower
    {
        return Follower::firstOrCreate([
            'follower_id' => $follower->id,
            'following_id' => $following->id,
        ]);
    }

    public function unfollow(User $follower, User $following): bool
    {
        Log::info("Attempting to unfollow", ['follower_id' => $follower->id, 'following_id' => $following->id]);
        
        $result = Follower::where('follower_id', $follower->id)
            ->where('following_id', $following->id)
            ->delete();
        
        Log::info("Unfollow database operation result", ['result' => $result]);
        
        return $result > 0;
    }

    public function getFollowers(User $user)
    {
        return $user->followers()->get();
    }

    public function getFollowing(User $user, int $perPage = 15)
    {
        return $user->following()->with('following')->latest()->paginate($perPage);
    }

    public function isFollowing(User $follower, User $following): bool
    {
        return $follower->following()->where('following_id', $following->id)->exists();
    }
}