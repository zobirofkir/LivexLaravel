<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Like\LikeRequest;
use App\Http\Requests\Like\UnlikeRequest;
use App\Models\Video;
use App\Services\Facades\LikeFacade;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function like(LikeRequest $request, Video $video)
    {
        $user = Auth::user();
        LikeFacade::like($user, $video);
        return response()->json(['message' => 'Video liked successfully']);
    }

    public function unlike(UnlikeRequest $request, Video $video)
    {
        $user = Auth::user();
        LikeFacade::unlike($user, $video);
        return response()->json(['message' => 'Video unliked successfully']);
    }

    public function likedVideos()
    {
        $user = Auth::user();
        $likedVideos = LikeFacade::getLikedVideos($user);
        return response()->json($likedVideos);
    }

    public function totalLikes()
    {
        $user = Auth::user();
        $totalLikes = LikeFacade::getTotalLikes($user);
        return response()->json(['total_likes' => $totalLikes]);
    }

    public function isLiked(Video $video)
    {
        $user = Auth::user();
        $isLiked = LikeFacade::isLiked($user, $video);
        return response()->json(['is_liked' => $isLiked]);
    }
}