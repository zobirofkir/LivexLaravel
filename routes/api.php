<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\EarningController;
use App\Http\Controllers\Api\FollowerController;
use App\Http\Controllers\Api\LikeController;

/**
 * authenticated routes
 */
require __DIR__.'/api_v1/authenticated/authenticated.php';

/**
 * live Routes
 */
require __DIR__.'/api_v1/live/live.php';

/**
 * mail routes
 */
require __DIR__.'/api_v1/mail/mail.php';

/**
 * phone routes
 */
require __DIR__.'/api_v1/phone/phone.php';

/**
 * offers routes
 */
require __DIR__.'/api_v1/offers/offers.php';

/**
 * Profile Actions routes
 */
require __DIR__.'/api_v1/profile-actions/profile-actions.php';

/**
 * User routes
 */
require __DIR__.'/api_v1/users/users.php';

/**
 * Chats routes
 */
require __DIR__.'/api_v1/chats/chats.php';

/**
 * Coins routes
 */
require __DIR__.'/api_v1/coins/coins.php';