<?php

namespace Spatie\MissingPageRedirector\Test;

use Route;
use Illuminate\Contracts\Http\Kernel;
use Orchestra\Testbench\TestCase as Orchestra;
use Spatie\MissingPageRedirector\RedirectsMissingPages;

abstract class TestCase extends Orchestra
{
    public function setUp()
    {
        parent::setUp();

        $this->setUpRoutes($this->app);
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            \Spatie\MissingPageRedirector\MissingPageRedirectorServiceProvider::class,
        ];
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('app.key', '6rE9Nz59bGRbeMATftriyQjrpF7DcOQm');
        $app['config']->set('app.debug', true);
        $app->make(Kernel::class)->pushMiddleware(RedirectsMissingPages::class);
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     */
    protected function setUpRoutes($app)
    {
        Route::get('/existing-page', function () {
            return 'existing page';
        });

        Route::get('response-code/{responseCode}', function (int $responseCode) {
            abort($responseCode);
        });
    }
}
