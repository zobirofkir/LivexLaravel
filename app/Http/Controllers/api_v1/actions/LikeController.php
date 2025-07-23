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
        
        $result = $this->likeService->unlike($user, $video);

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
        
        $isLiked = $this->likeService->isLiked($user, $video);
        return response()->json($isLiked);
    }
}