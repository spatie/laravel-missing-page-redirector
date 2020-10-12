# Redirect missing pages in your Laravel application

[![Latest Version on Packagist](https://img.shields.io/packagist/v/spatie/laravel-missing-page-redirector.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-missing-page-redirector)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
![Test Status](https://img.shields.io/github/workflow/status/spatie/laravel-missing-page-redirector/run-tests?label=tests)
![Code Style Status](https://img.shields.io/github/workflow/status/spatie/laravel-missing-page-redirector/Check%20&%20fix%20styling?label=code%20style)
[![Total Downloads](https://img.shields.io/packagist/dt/spatie/laravel-missing-page-redirector.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-missing-page-redirector)

When transitioning from a old site to a new one your URLs may change. If your old site was popular you probably want to retain your SEO worth. One way of doing this is by providing [permanent redirects from your old URLs to your new URLs](https://support.google.com/webmasters/answer/93633?hl=en). This package makes that process very easy.

When installed you only need to [add your redirects to the config file](https://github.com/spatie/laravel-missing-page-redirector#usage). Want to use the database as your source of redirects? [No problem](https://github.com/spatie/laravel-missing-page-redirector#creating-your-own-redirector)!

## Support us

[<img src="https://github-ads.s3.eu-central-1.amazonaws.com/laravel-missing-page-redirector.jpg?t=1" width="419px" />](https://spatie.be/github-ad-click/laravel-missing-page-redirector)

We invest a lot of resources into creating [best in class open source packages](https://spatie.be/open-source). You can support us by [buying one of our paid products](https://spatie.be/open-source/support-us).

We highly appreciate you sending us a postcard from your hometown, mentioning which of our package(s) you are using. You'll find our address on [our contact page](https://spatie.be/about-us). We publish all received postcards on [our virtual postcard wall](https://spatie.be/open-source/postcards).

## Installation

You can install the package via composer:

``` bash
composer require spatie/laravel-missing-page-redirector
```

The package will automatically register itself.

Next you must register the `Spatie\MissingPageRedirector\RedirectsMissingPages`-middleware:

```php
//app/Http/Kernel.php

protected $middleware = [
       ...
       \Spatie\MissingPageRedirector\RedirectsMissingPages::class,
    ],
```

Finally you must publish the config file:

```php
php artisan vendor:publish --provider="Spatie\MissingPageRedirector\MissingPageRedirectorServiceProvider"
```

This is the contents of the published config file:

```php
return [
    /*
     * This is the class responsible for providing the URLs which must be redirected.
     * The only requirement for the redirector is that it needs to implement the
     * `Spatie\MissingPageRedirector\Redirector\Redirector`-interface
     */
    'redirector' => \Spatie\MissingPageRedirector\Redirector\ConfigurationRedirector::class,
    
    /*
     * By default the package will only redirect 404s. If you want to redirect on other
     * response codes, just add them to the array. Leave the array empty to redirect
     * always no matter what the response code.
     */
    'redirect_status_codes' => [
        \Symfony\Component\HttpFoundation\Response::HTTP_NOT_FOUND
    ],
    
    /*
     * When using the `ConfigurationRedirector` you can specify the redirects in this array.
     * You can use Laravel's route parameters here.
     */
    'redirects' => [
//        '/non-existing-page' => '/existing-page',
//        '/old-blog/{url}' => '/new-blog/{url}',
    ],

];
```

## Usage

Creating a redirect is easy. You just have to add an entry to the `redirects` key in the config file.

```php
'redirects' => [
   '/non-existing-page' => '/existing-page',
],
```

You may use route parameters like you're used to when using Laravel's routes:

```php
    'redirects' => [
       '/old-blog/{url}' => '/new-blog/{url}',
    ],
```

Optional parameters are also... an option:

```php
    'redirects' => [
       '/old-blog/{url?}' => '/new-blog/{url}',
    ],
```

By default it only redirects if the request has a `404` response code but it's possible to be redirected on any response code.
To achieve this you may change the ```redirect_status_codes``` option to an array of response codes or leave it empty if you wish to be redirected no matter what the response code was sent to the URL.
You may override this using the following syntax to achieve this:  

```php
    'redirect_status_codes' => [\Symfony\Component\HttpFoundation\Response::HTTP_NOT_FOUND],
```

It is also possible to optionally specify which http response code is used when performing the redirect. By default the ```301 Moved Permanently``` response code is set. You may override this using the following syntax:   

```php
    'redirects' => [
       'old-page' => ['/new-page', 302],
    ],
```

## Events

The package will fire a `RouteWasHit` event when it found a redirect for the route. A `RedirectNotFound` is fired when no redirect was found.

## Creating your own redirector

By default this package will use the `Spatie\MissingPageRedirector\Redirector\ConfigurationRedirector` which will get its redirects from the config file. If you want to use another source for your redirects (for example a database) you can create your own redirector.

A valid redirector is any class that implements the `Spatie\MissingPageRedirector\Redirector\Redirector`-interface. That interface looks like this:

```php
namespace Spatie\MissingPageRedirector\Redirector;

use Symfony\Component\HttpFoundation\Request;

interface Redirector
{
    public function getRedirectsFor(Request $request): array;
}

```

The `getRedirectsFor` method should return an array in which the keys are the old URLs and the values the new URLs.

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email freek@spatie.be instead of using the issue tracker.

## Credits

- [Freek Van der Herten](https://github.com/freekmurze)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
