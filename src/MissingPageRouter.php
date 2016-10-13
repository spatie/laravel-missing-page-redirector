<?php

namespace Spatie\MissingPageRedirector;

use Exception;
use Illuminate\Routing\Router;
use Spatie\MissingPageRedirector\Redirector\Redirector;
use Symfony\Component\HttpFoundation\Request;

class MissingPageRouter
{
    /** @var \Illuminate\Routing\Router */
    protected $router;

    /** @var array */
    protected $redirects;

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
     * @return RedirectResponse|null
     */
    public function getRedirectFor(Request $request)
    {
        $redirects = $this->redirector->getRedirectsFor($request);

        collect($redirects)->each(function ($redirectUrl, $missingUrl) {
            $this->router->get($missingUrl, function () use ($redirectUrl) {
                $redirectUrl = $this->resolveRouterParameters($redirectUrl);

                return redirect()->to($redirectUrl);
            });
        });

        try {
            return $this->router->dispatch($request);
        } catch (Exception $e) {
            return;
        }
    }

    protected function resolveRouterParameters(string $redirectUrl): string
    {
        foreach ($this->router->getCurrentRoute()->parameters() as $key => $value) {
            $redirectUrl = str_replace("{{$key}}", $value, $redirectUrl);
        }

        return $redirectUrl;
    }
}
