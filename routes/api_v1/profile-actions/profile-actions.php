<?php

use App\Http\Controllers\api_v1\actions\EarningController;
use App\Http\Controllers\api_v1\actions\FollowerController;
use App\Http\Controllers\api_v1\actions\LikeController;
use App\Http\Controllers\api_v1\actions\CommentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->prefix('auth')->group(function () {
    
    /**
     * Earnings routes
     */
    Route::get('/earnings', [EarningController::class, 'index']);
    Route::post('/earnings', [EarningController::class, 'store']);

    /**
     * Follower routes
     */
    Route::post('/follow/{user}', [FollowerController::class, 'follow']);
    Route::post('/unfollow/{user}', [FollowerController::class, 'unfollow']);
    Route::get('/following/{user}', [FollowerController::class, 'following']);
    Route::get('/is-following/{user}', [FollowerController::class, 'isFollowing']);
    Route::get('/followers/{user}', [FollowerController::class, 'followers']);

    /**
     * Like routes
     */
    Route::post('/like/{video}', [LikeController::class, 'like']);
    Route::match(['post', 'get'], '/unlike/{video}', [LikeController::class, 'unlike']);
    Route::get('/liked-videos', [LikeController::class, 'likedVideos']);
    Route::get('/total-likes', [LikeController::class, 'totalLikes']);
    Route::get('/is-liked/{video}', [LikeController::class, 'isLiked']);
    Route::get('/like/{video}', [LikeController::class, 'totalLikesForVideo']);

    /**
     * Comment routes
     */
    Route::post('/reel/{video}/comment', [CommentController::class, 'addComment']);
    Route::get('/reel/{video}/comments', [CommentController::class, 'listComments']);
});
