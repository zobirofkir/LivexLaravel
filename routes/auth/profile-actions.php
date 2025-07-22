<?php

use App\Http\Controllers\Api\EarningController;
use App\Http\Controllers\Api\FollowerController;
use App\Http\Controllers\Api\LikeController;
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
    Route::get('/followers/{user}', [FollowerController::class, 'followers']);
    Route::get('/following/{user}', [FollowerController::class, 'following']);
    Route::get('/is-following/{user}', [FollowerController::class, 'isFollowing']);

    /**
     * Like routes
     */
    Route::post('/like/{video}', [LikeController::class, 'like']);
    Route::post('/unlike/{video}', [LikeController::class, 'unlike']);
    Route::get('/liked-videos', [LikeController::class, 'likedVideos']);
    Route::get('/total-likes', [LikeController::class, 'totalLikes']);
    Route::get('/is-liked/{video}', [LikeController::class, 'isLiked']);
});
