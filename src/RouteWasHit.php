<?php

namespace Spatie\MissingPageRedirector;

class RouteWasHit
{
    public $route;

    public function __construct($route)
    {
        $this->route = $route;
    }
}