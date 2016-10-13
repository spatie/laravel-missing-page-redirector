<?php

namespace Spatie\MissingPageRedirector\Redirector;

use Spatie\MissingPageRedirector\MissingPageRouter;
use Symfony\Component\HttpFoundation\Request;

class ConfigurationRedirector implements Redirector
{
    /** @var \Spatie\MissingPageRedirector\MissingPageRouter */
    protected $missingPageRouter;

    public function __construct(MissingPageRouter $missingPageRouter)
    {
        $this->missingPageRouter = $missingPageRouter;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return RedirectResponse|null
     */
    public function getRedirectFor(Request $request)
    {
        $this->missingPageRouter->setRedirects(config('laravel-missing-page-redirector.redirects'));

        return $this->missingPageRouter->getRedirectFor($request);
    }
}
