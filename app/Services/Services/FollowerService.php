<?php

namespace App\Services\Services;

use App\Models\Follower;
use App\Models\User;
use App\Services\Constructors\FollowerConstructor;

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
        return Follower::where('follower_id', $follower->id)
            ->where('following_id', $following->id)
            ->delete();
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