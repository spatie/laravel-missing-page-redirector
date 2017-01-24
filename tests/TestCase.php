<?php

namespace Spatie\MissingPageRedirector\Test;

use Route;
use Illuminate\Contracts\Http\Kernel;
use PHPUnit_Framework_Assert as PHPUnit;
use Orchestra\Testbench\TestCase as Orchestra;
use Spatie\MissingPageRedirector\RedirectsMissingPages;
use Laravel\BrowserKitTesting\Concerns\MakesHttpRequests;

abstract class TestCase extends Orchestra
{
    use MakesHttpRequests;

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
