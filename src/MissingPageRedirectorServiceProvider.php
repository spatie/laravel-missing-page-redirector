<?php

namespace Spatie\MissingPageRedirector;

use Illuminate\Support\ServiceProvider;

class MissingPageRedirectorServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
           $this->publishes([
               __DIR__.'/../config/laravel-missing-page-redirector.php' => config_path('laravel-missing-page-redirector.php'),
           ], 'config');
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/laravel-missing-page-redirector.php', 'laravel-missing-page-redirector');
    }
}
