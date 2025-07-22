<?php

namespace App\Providers;

use App\Services\Services\FollowerService;
use Illuminate\Support\ServiceProvider;

class FollowerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind('FollowerService', function($app) {
            return new FollowerService();
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
