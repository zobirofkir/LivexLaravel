<?php

namespace App\Providers;

use App\Services\Services\PhoneAuthService;
use App\Services\Auth\AuthPhoneService;
use Illuminate\Support\ServiceProvider;

class PhoneAuthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton("PhoneAuthService", function($app) {
            return new PhoneAuthService(
                $this->app->make(AuthPhoneService::class)
            );
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
