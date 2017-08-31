<?php

namespace Spatie\MissingPageRedirector\Redirector;

use Symfony\Component\HttpFoundation\Request;

class ConfigurationRedirector implements Redirector
{
    public function getRedirectsFor(Request $request): array
    {
        return config('missing-page-redirector.redirects');
    }
}
