<?php

namespace Spatie\MissingPageRedirector;

use Closure;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectsMissingPages
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if ($response->getStatusCode() !== Response::HTTP_NOT_FOUND) {
            return $response;
        }

        $redirectResponse = app(MissingPageRouter::class)->getRedirectFor($request);

        return $redirectResponse ?? $response;
    }
}
