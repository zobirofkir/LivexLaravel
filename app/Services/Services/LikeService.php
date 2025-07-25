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
        $like = Like::updateOrCreate(
            ['user_id' => $user->id, 'video_id' => $video->id],
            []
        );

        return $like;
    }

    public function unlike(User $user, Video $video): bool
    {
        $like = Like::where('user_id', $user->id)
                    ->where('video_id', $video->id)
                    ->first();
        
        if ($like) {
            $like->delete();
            return true;
        }
        
        return false;
    }

    public function getLikedVideos(User $user, int $perPage = 15)
    {
        return $user->likes()->with('video')->latest()->paginate($perPage);
    }

    public function getTotalLikes(User $user): int
    {
        return $user->likes()->count(); 
    }

    public function isLiked(User $user, Video $video): bool
    {
        return Like::where('user_id', $user->id)
                  ->where('video_id', $video->id)
                  ->exists();
    }

    public function canUnlike(User $user, Video $video): bool
    {
        // Any user can unlike a video they've previously liked
        return true;
    }

    public function canCheckIsLiked(User $user, Video $video): bool
    {
        // Any authenticated user can check if they've liked a video
        return true;
    }
}