<?php

namespace App\Providers;

use App\Services\Services\CoinService;
use Illuminate\Support\ServiceProvider;

class CoinServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind("CoinService", function ($app) {
            return new CoinService();
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
