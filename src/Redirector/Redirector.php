<?php

namespace Spatie\MissingPageRedirector\Redirector;

use Symfony\Component\HttpFoundation\Request;

interface Redirector
{
    public function getRedirectsFor(Request $request): array;
}
