<?php

namespace App\Services\Services;

use App\Models\Like;
use App\Models\User;
use App\Models\Video;
use App\Services\Constructors\LikeConstructor;

class LikeService implements LikeConstructor
{
    public function like(User $user, Video $video): Like
    {
        return Like::firstOrCreate([
            'user_id' => $user->id,
            'video_id' => $video->id,
        ]);
    }

    public function unlike(User $user, Video $video): bool
    {
        return Like::where('user_id', $user->id)
            ->where('video_id', $video->id)
            ->delete();
    }

    public function getLikedVideos(User $user, int $perPage = 15)
    {
        return $user->likes()->with('video')->latest()->paginate($perPage);
    }

    public function getTotalLikes(User $user): int
    {
        return $user->totalLikes()->count();
    }

    public function isLiked(User $user, Video $video): bool
    {
        return $user->likes()->where('video_id', $video->id)->exists();
    }
}