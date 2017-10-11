<?php

namespace Spatie\MissingPageRedirector\Test;

use Illuminate\Support\Facades\Event;
use Symfony\Component\HttpFoundation\Response;
use Spatie\MissingPageRedirector\Events\RouteWasHit;

class RedirectsMissingPagesTest extends TestCase
{
    /** @test */
    public function it_will_not_interfere_with_existing_pages()
    {
        $this
            ->get('existing-page')
            ->assertSee('existing page');
    }

    /** @test */
    public function it_will_redirect_a_non_existing_page_with_a_permanent_redirect()
    {
        $this->app['config']->set('missing-page-redirector.redirects', [
            '/non-existing-page' => '/existing-page',
        ]);

        $this
            ->get('non-existing-page')
            ->assertStatus(Response::HTTP_MOVED_PERMANENTLY)
            ->assertRedirect('/existing-page');
    }

    /** @test */
    public function it_will_not_redirect_an_url_that_it_not_configured()
    {
        $this->app['config']->set('missing-page-redirector.redirects', [
            '/non-existing-page' => '/existing-page',
        ]);

        $this
            ->get('/not-configured')
            ->assertStatus(Response::HTTP_NOT_FOUND);
    }

    /** @test */
    public function it_can_use_named_parameters()
    {
        $this->app['config']->set('missing-page-redirector.redirects', [
            '/segment1/{id}/segment2/{slug}' => '/segment2/{slug}',
        ]);

        $this
            ->get('/segment1/123/segment2/abc')
            ->assertRedirect('/segment2/abc');
    }

    /** @test */
    public function it_can_use_multiple_named_parameters_in_one_segment()
    {
        $this->app['config']->set('missing-page-redirector.redirects', [
            '/new-segment/{id}-{slug}' => '/new-segment/{id}/',
        ]);

        $this
            ->get('/new-segment/123-blablabla')
            ->assertRedirect('/new-segment/123');
    }

    /** @test */
    public function it_can_optionally_set_the_redirect_status_code()
    {
        $this->app['config']->set('missing-page-redirector.redirects', [
            '/temporarily-moved' => ['/just-for-now', 302],
        ]);

        $this
            ->get('/temporarily-moved')
            ->assertStatus(302)
            ->assertRedirect('/just-for-now');
    }

    /** @test */
    public function it_can_use_optional_parameters()
    {
        $this->app['config']->set('missing-page-redirector.redirects', [
            '/old-segment/{parameter1?}/{parameter2?}' => '/new-segment/',
        ]);

        $this
            ->get('/old-segment')
            ->assertRedirect('/new-segment');

        $this
            ->get('/old-segment/old-segment2')
            ->assertRedirect('/new-segment');

        $this
            ->get('/old-segment/old-segment2/old-segment3')
            ->assertRedirect('/new-segment');
    }

    /** @test */
    public function by_default_it_will_not_redirect_requests_that_are_not_404s()
    {
        $this
            ->get('/response-code/500')
            ->assertStatus(500);
    }

    /** @test */
    public function it_will_fire_an_event_when_a_route_is_hit()
    {
        Event::fake();

        $this->app['config']->set('missing-page-redirector.redirects', [
            '/old-segment/{parameter1?}/{parameter2?}' => '/new-segment/',
        ]);

        $this->get('/old-segment');

        Event::assertDispatched(RouteWasHit::class);
    }
    
    /** @test */
    public function it_will_redirect_depending_on_redirect_status_codes_defined()
    {
        $this->app['config']->set('missing-page-redirector.redirect_status_codes', [
            418,
            500,
        ]);

        $this->app['config']->set('missing-page-redirector.redirects', [
            '/response-code/500' => '/existing-page',
        ]);

        $this
            ->get('/response-code/500')
            ->assertRedirect('/existing-page');
    }

    /** @test */
    public function it_will_not_redirect_if_the_status_code_is_not_specified_in_the_config_file()
    {
        $this->app['config']->set('missing-page-redirector.redirect_status_codes', [
            418,
            403,
        ]);

        $this->app['config']->set('missing-page-redirector.redirects', [
            '/response-code/500' => '/existing-page',
        ]);

        $this
            ->get('/response-code/500')
            ->assertStatus(500);
    }
    
    /** @test */
    public function it_will_redirect_on_any_status_code()
    {
        $this->app['config']->set('missing-page-redirector.redirect_status_codes', []);
        $this->app['config']->set('missing-page-redirector.redirects', [
            '/response-code/418' => '/existing-page',
        ]);

        $this
            ->get('/response-code/418')
            ->assertRedirect('/existing-page');
    }
    
     /** @test */
    public function it_will_override_status_code_on_empty_redirect_url()
    {
        $this->app['config']->set('missing-page-redirector.redirects', [
            '/temporarily-moved' => ['', 410],
        ]);

        $this
            ->get('/temporarily-moved')
            ->assertStatus(410);
    }
}
