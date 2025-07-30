<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\EarningController;
use App\Http\Controllers\Api\FollowerController;
use App\Http\Controllers\Api\LikeController;

/**
 * authenticated routes
 */
require __DIR__.'/auth/authenticated/authenticated.php';

/**
 * live Routes
 */
require __DIR__.'/auth/live/live.php';

/**
 * mail routes
 */
require __DIR__.'/auth/mail/mail.php';

/**
 * phone routes
 */
require __DIR__.'/auth/phone/phone.php';

/**
 * offers routes
 */
require __DIR__.'/auth/offers/offers.php';

/**
 * Profile Actions routes
 */
require __DIR__.'/auth/profile-actions/profile-actions.php';

/**
 * User routes
 */
require __DIR__.'/auth/users/users.php';

/**
 * Chats routes
 */
require __DIR__.'/auth/chats/chats.php';