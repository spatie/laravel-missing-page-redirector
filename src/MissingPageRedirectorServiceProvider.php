<?php

namespace Spatie\MissingPageRedirector;

use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Spatie\MissingPageRedirector\Redirector\Redirector;

class MissingPageRedirectorServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/missing-page-redirector.php' => config_path('missing-page-redirector.php'),
        ], 'config');

        $this->app->bind(Redirector::class, config('missing-page-redirector.redirector'));

        $this->app->bind(MissingPageRouter::class, function ($app) {
            $router = new Router($app['events']);

            $redirector = $app->make(Redirector::class);

            return new MissingPageRouter($router, $redirector);
        });
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/missing-page-redirector.php', 'missing-page-redirector');
    }
}
