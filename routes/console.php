<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

/**
 * Display an inspiring quote at random
 */
Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/**
 * Schedule automatic offer expiration
 */
Schedule::command('offers:expire')->daily();

/**
 * Clear live streams every 30 minutes
 */
Schedule::command('livestreams:clear')->everyThirtySeconds();

