<?php

namespace Spatie\MissingPageRedirector;

use Exception;
use Spatie\MissingPageRedirector\Events\RedirectNotFound;
use Spatie\MissingPageRedirector\Helpers\RoutesTransformer;
use Spatie\MissingPageRedirector\Redirector\Redirector;
use Symfony\Component\HttpFoundation\Request;

class MissingPageRouter
{
    /** @var \Spatie\MissingPageRedirector\Redirector\Redirector */
    protected $redirector;

    public function __construct(Redirector $redirector)
    {
        $this->redirector = $redirector;
    }

    /**
     * @param \Illuminate\Http\Request|mixed $request
     *
     * @return \Illuminate\Http\Response|mixed|void
     */
    public function getRedirectFor(Request $request)
    {
        $routes = RoutesTransformer::transform(
            $this->redirector->getRedirectsFor($request)
        );

        try {
            return $routes->match($request)->run();
        } catch (Exception $e) {
            event(new RedirectNotFound($request));

            return;
        }
    }
}
