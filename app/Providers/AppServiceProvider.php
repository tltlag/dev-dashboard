<?php

namespace App\Providers;

use App\Helpers\CommonHelper;
use App\Models\Configuration;
use App\Models\Translation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(Request $request)
    {
        $this->loadTranslations();

        if (Schema::hasTable((new Configuration())->getTable())) {
            config([
                'global' => Configuration::all([
                    'key', 'value'
                ])
                    ->keyBy('key')
                    ->transform(function ($setting) {
                        return $setting->value;
                    })
                    ->toArray()
            ]);
        }
    }

    protected function loadTranslations()
    {
        if (! Schema::hasTable('translations')) {
            return;
        }

        if (Cache::has('translations')) {
            $translations = Cache::get('translations');
        } else {
            $translations = Translation::all()->groupBy('locale')->map(function ($translations) {
                return $translations->pluck('value', 'key');
            })->toArray();
            Cache::put('translations', $translations, 3600);
        }

        foreach ($translations as $locale => $keys) {
            foreach ($keys as $key => $value) {
                Config::set("languages.{$locale}.{$key}", $value);
            }
        }
    }
}
