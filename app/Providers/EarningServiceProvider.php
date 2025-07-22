<?php

namespace App\Providers;

use App\Services\Services\EarningService;
use Illuminate\Support\ServiceProvider;

class EarningServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind('EarningService', function ($app) {
            return new EarningService();
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
