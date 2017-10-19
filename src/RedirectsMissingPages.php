<?php

namespace Spatie\MissingPageRedirector;

use Closure;
use Illuminate\Http\Request;

class RedirectsMissingPages
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if (! $this->shouldRedirect($response)) {
            return $response;
        }

        $redirectResponse = app(MissingPageRouter::class)->getRedirectFor($request);

        return $redirectResponse ?? $response;
    }

    protected function shouldRedirect($response): bool
    {
        $redirectStatusCodes = config('missing-page-redirector.redirect_status_codes');

        if (is_null($redirectStatusCodes)) {
            return false;
        }

        if (! count($redirectStatusCodes)) {
            return true;
        }

        return in_array($response->getStatusCode(), $redirectStatusCodes);
    }
}
