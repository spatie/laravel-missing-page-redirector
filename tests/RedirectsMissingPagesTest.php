<?php

namespace Spatie\MissingPageRedirector\Test;

use Symfony\Component\HttpFoundation\Response;

class RedirectsMissingPagesTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    /** @test */
    public function it_will_not_interfere_with_existing_pages()
    {
        $this->visit('existing-page')->see('existing page');
    }

    /** @test */
    public function it_will_redirect_a_non_existing_page_with_a_permanent_redirect()
    {
        $this->app['config']->set('laravel-missing-page-redirector.redirects', [
            '/non-existing-page' => '/existing-page',
        ]);

        $this->get('non-existing-page');

        $this->assertRedirectedTo('/existing-page');

        $this->assertResponseStatus(Response::HTTP_MOVED_PERMANENTLY);
    }

    /** @test */
    public function it_will_not_redirect_an_url_that_it_not_configured()
    {
        $this->app['config']->set('laravel-missing-page-redirector.redirects', [
            '/non-existing-page' => '/existing-page',
        ]);

        $this->get('/not-configured');

        $this->assertResponseStatus(Response::HTTP_NOT_FOUND);
    }

    /** @test */
    public function it_can_use_named_parameters()
    {
        $this->app['config']->set('laravel-missing-page-redirector.redirects', [
            '/segment1/{id}/segment2/{slug}' => '/segment2/{slug}',
        ]);

        $this->get('/segment1/123/segment2/abc');

        $this->assertRedirectedTo('/segment2/abc');
    }

    /** @test */
    public function it_can_use_multiple_named_parameters_in_one_segment()
    {
        $this->app['config']->set('laravel-missing-page-redirector.redirects', [
            '/new-segment/{id}-{slug}' => '/new-segment/{id}/',
        ]);

        $this->get('/new-segment/123-blablabla');

        $this->assertRedirectedTo('/new-segment/123');
    }

    /** @test */
    public function it_can_use_optional_parameters()
    {
        $this->app['config']->set('laravel-missing-page-redirector.redirects', [
            '/old-segment/{parameter1?}/{parameter2?}' => '/new-segment/',
        ]);

        $this->get('/old-segment');

        $this->assertRedirectedTo('/new-segment');

        $this->get('/old-segment/old-segment2');

        $this->assertRedirectedTo('/new-segment');

        $this->get('/old-segment/old-segment2/old-segment3');

        $this->assertRedirectedTo('/new-segment');
    }

    /** @test */
    public function it_automatically_appends_existing_query_strings_when_redirecting()
    {
        $this->app['config']->set('laravel-missing-page-redirector.redirects', [
            '/old-page' => '/new-page',
        ]);

        $this->get('/old-page?param=1');

        $this->assertRedirectedTo('/new-page?param=1');

        $this->get('/old-page?param=1&param2=2');

        $this->assertRedirectedTo('/new-page?param=1&param2=2');

        $this->get('/old-page?param=1&param2=2&param3=3');

        $this->assertRedirectedTo('/new-page?param=1&param2=2&param3=3');

    }

    /** @test */
    public function it_will_not_redirect_requests_that_are_not_404s()
    {
        $this->get('/response-code/500');

        $this->assertResponseStatus(500);
    }
}
