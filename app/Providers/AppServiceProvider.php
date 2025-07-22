<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;
use App\Services\Services\FollowerService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        Passport::ignoreRoutes();
        $this->app->bind('follower.service', function ($app) {
            return new FollowerService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
