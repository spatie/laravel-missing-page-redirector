<?php

namespace Spatie\MissingPageRedirector;

use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;

class MissingPageRedirectorServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/laravel-missing-page-redirector.php' => config_path('laravel-missing-page-redirector.php'),
        ], 'config');

        $this->app->bind(Redirector::class, config('laravel-missing-page-redirector.redirector'));

        $this->app->bind(MissingPageRouter::class, function () {
            $router = new Router($this->app['events']);

            return new MissingPageRouter($router);
        });
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/laravel-missing-page-redirector.php', 'laravel-missing-page-redirector');
    }
}
