<?php

namespace App\Providers;

use App\Services\Services\LiveStreamService;
use Illuminate\Support\ServiceProvider;

class LiveStreamServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind('LiveStreamService', LiveStreamService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
