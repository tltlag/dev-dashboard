<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\BexioService;

class BexioServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(BexioService::class, function ($app) {
            return new BexioService();
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
