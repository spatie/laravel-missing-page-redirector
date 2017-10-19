<?php

namespace Spatie\MissingPageRedirector\Events;

class RouteWasHit
{
    /** @var string */
    public $route;

    /** @var string */
    public $missingUrl;

    /** @var int|null */
    public $statusCode;

    public function __construct(string $route, string $missingUrl, int $statusCode = null)
    {
        $this->route = $route;

        $this->missingUrl = $missingUrl;

        $this->statusCode = $statusCode;
    }
}
