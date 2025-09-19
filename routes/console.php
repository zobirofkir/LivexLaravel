<?php

use App\Jobs\DeleteOldLiveStreams;
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
 * Schedule automatic live stream deletion
 */
Schedule::job(new DeleteOldLiveStreams)->everyThirtySeconds();
