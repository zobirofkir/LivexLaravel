<?php

namespace App\Http\Controllers\api_v1\actions;

use App\Http\Controllers\Controller;
use App\Http\Requests\Like\LikeRequest;
use App\Http\Requests\Like\UnlikeRequest;
use App\Models\Video;
use App\Services\Services\LikeService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\LikeResource; 

class LikeController extends Controller
{
    protected $likeService;

    public function __construct(LikeService $likeService)
    {
        $this->likeService = $likeService;
    }

    public function like(Video $video): JsonResponse
    {
        $user = Auth::user();
                
        $like = $this->likeService->like($user, $video);
        
        return response()->json(LikeResource::make($like));
    }

    public function unlike(Video $video): JsonResponse
    {
        $user = Auth::user();
        
        // Check if the user can unlike the video
        if ($this->likeService->canUnlike($user, $video)) {
            $result = $this->likeService->unlike($user, $video);
        } else {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json($result);
    }

    public function likedVideos(): JsonResponse
    {
        $user = Auth::user();
                
        $likedVideos = $this->likeService->getLikedVideos($user);
        return response()->json(LikeResource::collection($likedVideos));
    }

    public function totalLikes(): JsonResponse
    {
        $user = Auth::user();
        
        $totalLikes = $this->likeService->getTotalLikes($user);
        return response()->json($totalLikes);
    }

    public function isLiked(Video $video): JsonResponse
    {
        $user = Auth::user();        
        
        // Check if the user can check if the video is liked
        if ($this->likeService->canCheckIsLiked($user, $video)) {
            $isLiked = $this->likeService->isLiked($user, $video);
        } else {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json($isLiked);
    }

    public function totalLikesForVideo(Video $video): JsonResponse
    {
        $totalLikes = $video->likes()->count();
        return response()->json(['video_id' => $video->id, 'total_likes' => $totalLikes]);
    }
}