<?php

namespace Spatie\MissingPageRedirector\Helpers;

use Illuminate\Routing\Route;
use Illuminate\Routing\RouteAction;
use Illuminate\Routing\RouteCollection;
use Spatie\MissingPageRedirector\Events\RouteWasHit;
use Symfony\Component\HttpFoundation\Response;

class RoutesTransformer
{
    /**
     * @param array $redirects
     *
     * @return \Illuminate\Routing\RouteCollection
     */
    public static function transform(array $redirects): RouteCollection
    {
        $routes = new RouteCollection;

        foreach ($redirects as $missingUrl => $redirectUrl) {
            $routes->add(static::makeRoute($missingUrl, $redirectUrl));
        }

        return $routes;
    }

    /**
     * Make route.
     *
     * @param string $missingUrl
     * @param string|array $redirects
     *
     * @return \Illuminate\Routing\Route
     */
    public static function makeRoute(string $missingUrl, $redirects): Route
    {
        $route = new Route(['GET', 'HEAD'], $missingUrl, []);

        return $route->setAction(RouteAction::parse($route->uri(), function () use ($route, $redirects, $missingUrl) {
            $redirectUrl = self::determineRedirectUrl($route, $redirects);
            $statusCode = self::determineRedirectStatusCode($redirects);

            event(new RouteWasHit($redirectUrl, $missingUrl, $statusCode));

            return redirect()->to($redirectUrl, $statusCode);
        }));
    }

    /**
     * @param  \Illuminate\Routing\Route  $route
     * @param  string|array               $redirectUrl
     *
     * @return string
     */
    private static function determineRedirectUrl(Route $route, $redirectUrl): string
    {
        if (is_array($redirectUrl)) {
            $redirectUrl = $redirectUrl[0];
        }

        foreach ($route->parameters() as $key => $value) {
            $redirectUrl = str_replace("{{$key}}", $value, $redirectUrl);
        }

        return preg_replace('/\/{[\w-]+}/', '', $redirectUrl);
    }

    /**
     * @param  string|array  $redirectUrl
     *
     * @return int
     */
    private static function determineRedirectStatusCode($redirectUrl): int
    {
        return is_array($redirectUrl)
            ? $redirectUrl[1]
            : Response::HTTP_MOVED_PERMANENTLY;
    }
}
