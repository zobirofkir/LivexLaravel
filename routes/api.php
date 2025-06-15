<?php

use Illuminate\Support\Facades\Route;

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