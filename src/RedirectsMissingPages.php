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

        $allowedStatusCode = config('missing-page-redirector.status_code', [Response::HTTP_NOT_FOUND]);
        if(!is_array($allowedStatusCode)){
            $allowedStatusCode = is_integer($allowedStatusCode) ? [$allowedStatusCode] : [];
        }

        if (!empty($allowedStatusCode) && !in_array($response->getStatusCode(), $allowedStatusCode)) {
            return $response;
        }
        
        $redirectResponse = app(MissingPageRouter::class)->getRedirectFor($request);

        return $redirectResponse ?? $response;
    }
}
