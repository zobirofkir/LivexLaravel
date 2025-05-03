<?php

namespace App\Providers;

use App\Services\Auth\AuthEmailService;
use App\Services\Services\EmailAuthService;
use Illuminate\Support\ServiceProvider;

class EmailAuthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton("EmailAuthService", function ($app) {
            return new EmailAuthService(
                $app->make(AuthEmailService::class)
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
