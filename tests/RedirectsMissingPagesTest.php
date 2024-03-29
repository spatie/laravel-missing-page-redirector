<?php

use Illuminate\Support\Facades\Event;
use Spatie\MissingPageRedirector\Events\RedirectNotFound;
use Spatie\MissingPageRedirector\Events\RouteWasHit;
use Symfony\Component\HttpFoundation\Response;

it('will not interfere with existing pages')
    ->get('existing-page')
    ->assertSee('existing page');

it('will redirect a non existing page with a permanent redirect')
    ->tap(
        fn () => config()->set('missing-page-redirector.redirects', [
            '/non-existing-page' => '/existing-page',
        ])
    )
    ->get('non-existing-page')
    ->assertStatus(Response::HTTP_MOVED_PERMANENTLY)
    ->assertRedirect('/existing-page');

it('will redirect wildcard routes')
    ->tap(fn () => config()->set('missing-page-redirector.redirects', [
        '/path/*' => '/new-path/{wildcard}',
    ]))
    ->get('path/to/a/page')
    ->assertStatus(Response::HTTP_MOVED_PERMANENTLY)
    ->assertRedirect('/new-path/to/a/page');

it('will not redirect an url that is not configured')
    ->tap(
        fn () => config()->set('missing-page-redirector.redirects', [
            '/non-existing-page' => '/existing-page',
        ])
    )
    ->get('/not-configured')
    ->assertStatus(Response::HTTP_NOT_FOUND);

it('can use named properties')
    ->tap(
        fn () => config()->set('missing-page-redirector.redirects', [
            '/segment1/{id}/segment2/{slug}' => '/segment2/{slug}',
        ])
    )
    ->get('/segment1/123/segment2/abc')
    ->assertRedirect('/segment2/abc');


it('can use (multiple named parameters in one segment')
    ->tap(
        fn () => config()->set('missing-page-redirector.redirects', [
            '/new-segment/{id}-{slug}' => '/new-segment/{id}/',
        ])
    )
    ->get('/new-segment/123-blablabla')
    ->assertRedirect('/new-segment/123');

it('can optionally set the redirect status code')
    ->tap(
        fn () => config()->set('missing-page-redirector.redirects', [
            '/temporarily-moved' => ['/just-for-now', 302],
        ])
    )
    ->get('/temporarily-moved')
    ->assertStatus(302)
    ->assertRedirect('/just-for-now');

it('can use optional parameters', function (string $getRoute, string $redirectRoute) {
    config()->set('missing-page-redirector.redirects', [
        '/old-segment/{parameter1?}/{parameter2?}' => '/new-segment/{parameter1}/{parameter2}',
    ]);

    $this->get($getRoute)
        ->assertRedirect($redirectRoute);
})->with([
    ['/old-segment', '/new-segment'],
    ['/old-segment/old-segment2', '/new-segment/old-segment2'],
    ['/old-segment/old-segment2/old-segment3', '/new-segment/old-segment2/old-segment3'],
]);

test('by default it will not redirect requests that are nit 404s')
    ->get('/response-code/500')
    ->assertStatus(500);

it('will fire an event when a route is hit', function () {
    Event::fake();

    $this->app['config']->set('missing-page-redirector.redirects', [
        '/old-segment/{parameter1?}/{parameter2?}' => '/new-segment/',
    ]);

    $this->get('/old-segment');

    Event::assertDispatched(RouteWasHit::class);
});

it('will redirect depending on redirect status code defined')
    ->tap(
        fn () => config()->set(
            'missing-page-redirector.redirect_status_codes',
            [418, 500]
        )
    )
    ->tap(
        fn () => config()->set('missing-page-redirector.redirects', [
            '/response-code/500' => '/existing-page',
        ])
    )
    ->get('/response-code/500')
    ->assertRedirect('/existing-page');

it('will not redirect if the status code is not specified in the config file')
    ->tap(
        fn () => config()->set(
            'missing-page-redirector.redirect_status_codes',
            [418, 403]
        )
    )
    ->tap(
        fn () => config()->set('missing-page-redirector.redirects', [
            '/response-code/500' => '/existing-page',
        ])
    )
    ->get('/response-code/500')
    ->assertStatus(500);

it('will redirect on any status code')
    ->tap(
        fn () => config()->set('missing-page-redirector.redirect_status_codes', [])
    )
    ->tap(
        fn () => config()->set('missing-page-redirector.redirects', [
            '/response-code/418' => '/existing-page',
        ])
    )
    ->get('/response-code/418')
    ->assertRedirect('/existing-page');

it('will fire an event when no redirect was found', function () {
    Event::fake();

    $this->get('/response-code/404');

    Event::assertDispatched(RedirectNotFound::class);
});
