<?php

namespace Spatie\MissingPageRedirector\Events;

class RouteWasHit
{
    public $route;

    public $missingUrl;

    public function __construct(string $route, string $missingUrl)
    {
        $this->route = $route;

        $this->missingUrl = $missingUrl;
    }
}
