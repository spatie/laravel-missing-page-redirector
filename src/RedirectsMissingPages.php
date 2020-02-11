<?php

namespace Spatie\MissingPageRedirector;

use Closure;
use Illuminate\Http\Request;

class RedirectsMissingPages
{
    /** @var \Spatie\MissingPageRedirector\MissingPageRouter */
    protected $mpr;

    public function __construct(MissingPageRouter $mpr)
    {
        $this->mpr = $mpr;
    }

    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if (! $this->shouldRedirect($response)) {
            return $response;
        }

        return $this->mpr->getRedirectFor($request) ?? $response;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Response|mixed $response
     *
     * @return bool
     */
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
