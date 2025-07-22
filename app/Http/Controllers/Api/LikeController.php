<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Like\LikeRequest;
use App\Http\Requests\Like\UnlikeRequest;
use App\Models\Video;
use App\Services\Facades\LikeFacade;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;

class LikeController extends Controller
{
    public function like(Video $video): JsonResponse
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }
        
        $like = LikeFacade::like($user, $video);
        
        if ($like->wasRecentlyCreated) {
            return response()->json(['message' => 'Video liked successfully', 'liked' => true], 201);
        } else {
            return response()->json(['message' => 'Video was already liked', 'liked' => true], 200);
        }
    }

    public function unlike(UnlikeRequest $request, Video $video): JsonResponse
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }
        
        LikeFacade::unlike($user, $video);
        return response()->json(['message' => 'Video unliked successfully']);
    }

    public function likedVideos(): JsonResponse
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }
        
        $likedVideos = LikeFacade::getLikedVideos($user);
        return response()->json($likedVideos);
    }

    public function totalLikes(): JsonResponse
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }
        
        $totalLikes = LikeFacade::getTotalLikes($user);
        return response()->json(['total_likes' => $totalLikes]);
    }

    public function isLiked(Video $video): JsonResponse
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }
        
        $isLiked = LikeFacade::isLiked($user, $video);
        return response()->json(['is_liked' => $isLiked]);
    }
}