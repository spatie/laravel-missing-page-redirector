<?php

namespace Spatie\MissingPageRedirector;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectsMissingPages
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if ($response->getStatusCode() === Response::HTTP_NOT_FOUND) {
            if ($redirectResponse = app(Redirector::class)->getRedirectFor($request)) {
                return $redirectResponse;
            }
        }

        return $response;
    }
}
