<?php

namespace Spatie\MissingPageRedirector;

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
