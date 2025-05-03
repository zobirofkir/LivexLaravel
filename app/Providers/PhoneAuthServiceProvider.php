<?php

namespace App\Providers;

use App\Services\Services\PhoneAuthService;
use Illuminate\Support\ServiceProvider;

class PhoneAuthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton("PhoneAuthService", function($app) {
            return new PhoneAuthService();
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
