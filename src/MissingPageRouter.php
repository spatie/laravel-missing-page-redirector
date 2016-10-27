<?php

namespace Spatie\MissingPageRedirector;

use Exception;
use Illuminate\Routing\Router;
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

            $this->router->get($missingUrl, function () use ($redirects) {

                return redirect()->to(
                    $this->getRedirectUrlFromRedirects($redirects),
                    $this->getStatusCodeFromRedirects($redirects)
                );

            });
        });

        try {
            return $this->router->dispatch($request);
        } catch (Exception $e) {
            return;
        }
    }

    protected function getRedirectUrlFromRedirects($redirects): string
    {
        if (is_array($redirects)) {
            return $this->resolveRouterParameters($redirects[0]);
        }

        return $this->resolveRouterParameters($redirects);
    }

    protected function getStatusCodeFromRedirects($redirects): int
    {
        return is_array($redirects) ? $redirects[1] : Response::HTTP_MOVED_PERMANENTLY;
    }

    protected function resolveRouterParameters(string $redirectUrl): string
    {
        foreach ($this->router->getCurrentRoute()->parameters() as $key => $value) {
            $redirectUrl = str_replace("{{$key}}", $value, $redirectUrl);
        }

        return $redirectUrl;
    }
}
