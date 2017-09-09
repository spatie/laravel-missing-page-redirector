<?php

return [
    /*
     * This is the class responsible for providing the URLs which must be redirected.
     * The only requirement for the redirector is that it needs to implement the
     * `Spatie\MissingPageRedirector\Redirector\Redirector`-interface
     */
    'redirector' => \Spatie\MissingPageRedirector\Redirector\ConfigurationRedirector::class,
    
    /*
     * This allows you to trigger the redirects on all kind of HTTP codes.
     * To trigger redirects on any HTTP code leave it as an empty array
     */
    'redirect_status_codes' => [\Symfony\Component\HttpFoundation\Response::HTTP_NOT_FOUND],
    
    /*
     * When using the `ConfigurationRedirector` you can specify the redirects in this array.
     * You can use Laravel's route parameters here.
     */
    'redirects' => [
//        '/non-existing-page' => '/existing-page',
//        '/old-blog/{url}' => '/new-blog/{url}',
    ],

];
