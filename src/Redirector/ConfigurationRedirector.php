<?php

namespace Spatie\MissingPageRedirector\Redirector;

use Spatie\MissingPageRedirector\MissingPageRouter;
use Symfony\Component\HttpFoundation\Request;

class ConfigurationRedirector implements Redirector
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return RedirectResponse|null
     */
    public function getRedirectsFor(Request $request)
    {
        return config('laravel-missing-page-redirector.redirects');
    }
}
