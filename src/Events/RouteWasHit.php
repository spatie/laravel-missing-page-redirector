<?php

namespace Spatie\MissingPageRedirector\Events;

class RouteWasHit
{
    /** @var string */
    public $route;

    /** @var string */
    public $missingUrl;
    
    /** @var integer */
    public $statusCode;

    public function __construct(string $route, string $missingUrl, int $statusCode)
    {
        $this->route = $route;

        $this->missingUrl = $missingUrl;
        
        $this->statusCode = $statusCode;
    }
}
