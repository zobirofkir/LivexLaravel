<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\EarningController;
use App\Http\Controllers\Api\FollowerController;
use App\Http\Controllers\Api\LikeController;

/**
 * authenticated routes
 */
require __DIR__.'/auth/authenticated.php';

/**
 * live Routes
 */
require __DIR__.'/auth/live.php';

/**
 * mail routes
 */
require __DIR__.'/auth/mail.php';

/**
 * phone routes
 */
require __DIR__.'/auth/phone.php';

/**
 * offers routes
 */
require __DIR__.'/auth/offers.php';

/**
 * Profile Actions routes
 */
require __DIR__.'/auth/profile-actions.php';