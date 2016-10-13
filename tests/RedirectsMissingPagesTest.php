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
    public function it_will_redirect_a_non_existing_page()
    {
        $this->app['config']->set('laravel-missing-page-redirector.redirects', [
            '/non-existing-page' => '/existing-page'
        ]);

        $this->get('non-existing-page');

        $this->assertRedirectedTo('/existing-page');
    }

    /** @test */
    public function it_will_not_redirect_an_url_that_it_not_configured()
    {
        $this->app['config']->set('laravel-missing-page-redirector.redirects', [
            '/non-existing-page' => '/existing-page'
        ]);

        $this->get('/not-configured');

        $this->assertResponseStatus(Response::HTTP_NOT_FOUND);
    }

    /** @test */
    public function it_can_use_named_parameters()
    {
        $this->app['config']->set('laravel-missing-page-redirector.redirects', [
            '/segment1/{id}/segment2/{slug}' => '/segment2/{slug}'
        ]);

        $this->get('/segment1/123/segment2/abc');

        $this->assertRedirectedTo('/segment2/abc');
    }

    /** @test */
    public function it_can_use_multiple_named_parameters_in_one_segment()
    {
        $this->app['config']->set('laravel-missing-page-redirector.redirects', [
            '/new-segment/{id}-{slug}' => '/new-segment/{id}/'
        ]);

        $this->get('/new-segment/123-blablabla');

        $this->assertRedirectedTo('/new-segment/123');
    }

    /** @test */
    public function it_can_use_optional_parameters()
    {
        $this->app['config']->set('laravel-missing-page-redirector.redirects', [
            '/old-segment/{parameter1?}/{parameter2?}' => '/new-segment/'
        ]);

        $this->get('/old-segment');

        $this->assertRedirectedTo('/new-segment');

        $this->get('/old-segment/old-segment2');

        $this->assertRedirectedTo('/new-segment');

        $this->get('/old-segment/old-segment2/old-segment3');

        $this->assertRedirectedTo('/new-segment');
    }

    public function it_will_not_redirect_requests_that_are_not_404s()
    {

    }
}
