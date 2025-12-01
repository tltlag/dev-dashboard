<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\ClockodoService;

class ClockodoServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(ClockodoService::class, function ($app) {
            return new ClockodoService();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
