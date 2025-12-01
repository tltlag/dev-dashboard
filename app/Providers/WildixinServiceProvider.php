<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\WildixinService;

class WildixinServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(WildixinService::class, function ($app) {
            return new WildixinService();
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
