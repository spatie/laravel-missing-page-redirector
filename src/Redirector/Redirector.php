<?php

namespace Spatie\MissingPageRedirector\Redirector;

use Illuminate\Http\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

interface Redirector
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return RedirectResponse|null
     */
    public function getRedirectFor(Request $request);
}
