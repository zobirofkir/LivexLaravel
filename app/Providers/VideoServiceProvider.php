<?php

namespace App\Providers;

use App\Services\Services\VideoService;
use Illuminate\Support\ServiceProvider;

class VideoServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind('VideoService', function($app) {
            return new VideoService();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
