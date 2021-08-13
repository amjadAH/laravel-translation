<?php

namespace AmjadAH\LaravelTranslation;

use Illuminate\Support\ServiceProvider;
use AmjadAH\LaravelTranslation\Commands\GenerateTranslationFiles;

class TranslationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/vendor/amjad-ah/laravel-translation/src/config/lang.php' => config_path('lang.php')
        ], 'config');

        $this->mergeConfigFrom(
            __DIR__.'/config/lang.php', 'lang'
        );

        if ($this->app->runningInConsole()) {
            $this->commands([
                GenerateTranslationFiles::class
            ]);
        }
    }
}
