<?php

namespace Spatie\MissingPageRedirector\Events;

use Symfony\Component\HttpFoundation\Request;

class RedirectNotFound
{
    /** @var Request */
    public $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }
}
