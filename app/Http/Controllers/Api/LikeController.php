<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Like\LikeRequest;
use App\Http\Requests\Like\UnlikeRequest;
use App\Models\Video;
use App\Services\Services\LikeService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;

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
        
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }
        
        $like = $this->likeService->like($user, $video);
        
        if ($like->wasRecentlyCreated) {
            return response()->json(['message' => 'Video liked successfully', 'liked' => true], 201);
        } else {
            return response()->json(['message' => 'Video was already liked', 'liked' => true], 200);
        }
    }

    public function unlike(Video $video): JsonResponse
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $result = $this->likeService->unlike($user, $video);

        if ($result) {
            return response()->json(['message' => 'Video unliked successfully'], 200);
        } else {
            return response()->json(['message' => 'Video was not liked'], 200);
        }
    }

    public function likedVideos(): JsonResponse
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }
        
        $likedVideos = $this->likeService->getLikedVideos($user);
        return response()->json($likedVideos);
    }

    public function totalLikes(): JsonResponse
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }
        
        $totalLikes = $this->likeService->getTotalLikes($user);
        return response()->json(['total_likes' => $totalLikes]);
    }

    public function isLiked(Video $video): JsonResponse
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }
        
        $isLiked = $this->likeService->isLiked($user, $video);
        return response()->json(['is_liked' => $isLiked]);
    }
}