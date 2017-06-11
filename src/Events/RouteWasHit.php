<?php

namespace Spatie\MissingPageRedirector\Events;

class RouteWasHit
{
    /** @var string */
    public $route;

    /** @var string */
    public $missingUrl;

    public function __construct(string $route, string $missingUrl)
    {
        $this->route = $route;

        $this->missingUrl = $missingUrl;
    }
}
