<?php

namespace App\Providers;

use Config;
use App\Models\Translation;
use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;

class TimezoneServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Set the application timezone programmatically
        $timezone = config('global.DEFAULT_TIMEZONE', 'UTC'); // Replace with your desired timezone
        config(['app.timezone' => $timezone]);

        // Set the PHP timezone if necessary
        date_default_timezone_set($timezone);

        App::setLocale(config('global.SITE_LOCALE', Translation::LANG_CODE_ENGLISH));
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}