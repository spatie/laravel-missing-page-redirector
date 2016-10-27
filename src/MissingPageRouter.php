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

                if (is_array($redirects)) {
                    $redirectUrl = $this->resolveRouterParameters($redirects[0]);
                } else {
                    $redirectUrl = $this->resolveRouterParameters($redirects);
                }

                $statusCode = is_array($redirects) ? $redirects[1] : Response::HTTP_MOVED_PERMANENTLY;

                return redirect()->to($redirectUrl, $statusCode);
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
