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

        $allowedStatusCodes = config('missing-page-redirector.redirect_status_codes', [Response::HTTP_NOT_FOUND]);

        //If option is set as null or the response status code is not present in the config then skip redirects
        if (is_null($allowedStatusCodes) || (!empty($allowedStatusCodes) && !in_array($response->getStatusCode(), $allowedStatusCodes))) {
            return $response;
        }
        
        $redirectResponse = app(MissingPageRouter::class)->getRedirectFor($request);

        return $redirectResponse ?? $response;
    }
}
