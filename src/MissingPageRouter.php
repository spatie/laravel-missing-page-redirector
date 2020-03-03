<?php

namespace Spatie\MissingPageRedirector;

use Exception;
use Illuminate\Routing\Router;
use Spatie\MissingPageRedirector\Events\RedirectNotFound;
use Spatie\MissingPageRedirector\Events\RouteWasHit;
use Spatie\MissingPageRedirector\Redirector\Redirector;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MissingPageRouter
{
    /** @var \Illuminate\Routing\Router */
    protected $router;

    /** @var \Spatie\MissingPageRedirector\Redirector\Redirector */
    protected $redirector;

    public function __construct(Router $router, Redirector $redirector)
    {
        $this->router = $router;
        $this->redirector = $redirector;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Illuminate\Http\Response|null
     */
    public function getRedirectFor(Request $request)
    {
        $redirects = $this->redirector->getRedirectsFor($request);

        collect($redirects)->each(function ($redirects, $missingUrl) {
            $this->router->get($missingUrl, function () use ($redirects, $missingUrl) {
                $redirectUrl = $this->determineRedirectUrl($redirects);
                $statusCode = $this->determineRedirectStatusCode($redirects);

                event(new RouteWasHit($redirectUrl, $missingUrl, $statusCode));

                return redirect()->to(
                    $redirectUrl,
                    $statusCode
                );
            });
        });

        try {
            return $this->router->dispatch($request);
        } catch (Exception $e) {
            event(new RedirectNotFound($request));

            return;
        }
    }

    protected function determineRedirectUrl($redirects): string
    {
        if (is_array($redirects)) {
            return $this->resolveRouterParameters($redirects[0]);
        }

        return $this->resolveRouterParameters($redirects);
    }

    protected function determineRedirectStatusCode($redirects): int
    {
        return is_array($redirects) ? $redirects[1] : Response::HTTP_MOVED_PERMANENTLY;
    }

    protected function resolveRouterParameters(string $redirectUrl): string
    {
        foreach ($this->router->getCurrentRoute()->parameters() as $key => $value) {
            $redirectUrl = str_replace("{{$key}}", $value, $redirectUrl);
        }

        $redirectUrl = preg_replace('/\/{[\w-]+}/', '', $redirectUrl);

        return $redirectUrl;
    }
}
