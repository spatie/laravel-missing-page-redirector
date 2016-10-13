<?php

namespace Spatie\MissingPageRedirector\Redirector;

use Spatie\MissingPageRedirector\MissingPageRouter;
use Symfony\Component\HttpFoundation\Request;

class ConfigurationRedirector implements Redirector
{
    public function getRedirectsFor(Request $request): array
    {
        return config('laravel-missing-page-redirector.redirects');
    }
}
