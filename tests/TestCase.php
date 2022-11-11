<?php

namespace Spatie\MissingPageRedirector\Test;

use Illuminate\Contracts\Http\Kernel;
use Orchestra\Testbench\TestCase as Orchestra;
use PHPUnit\Framework\Assert as PHPUnit;
use Route;
use Spatie\MissingPageRedirector\MissingPageRedirectorServiceProvider;
use Spatie\MissingPageRedirector\RedirectsMissingPages;

abstract class TestCase extends Orchestra
{
    protected function setUp(): void
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
            MissingPageRedirectorServiceProvider::class,
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

    /**
     * Assert whether the client was redirected to a given URI.
     *
     * @param  string  $uri
     * @param  array  $with
     * @return $this
     */
    public function assertRedirectedTo($uri, $with = [])
    {
        PHPUnit::assertInstanceOf('Illuminate\Http\RedirectResponse', $this->response);

        PHPUnit::assertEquals($this->app['url']->to($uri), $this->response->headers->get('Location'));

        return $this;
    }
}
