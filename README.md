# Redirect missing pages in your Laravel application

[![Latest Version on Packagist](https://img.shields.io/packagist/v/spatie/laravel-missing-page-redirector.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-missing-page-redirector)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/spatie/laravel-missing-page-redirector/master.svg?style=flat-square)](https://travis-ci.org/spatie/laravel-missing-page-redirector)
[![SensioLabsInsight](https://img.shields.io/sensiolabs/i/964175f9-d8aa-4198-a40e-32875f59b6b7.svg?style=flat-square)](https://insight.sensiolabs.com/projects/964175f9-d8aa-4198-a40e-32875f59b6b7)
[![Quality Score](https://img.shields.io/scrutinizer/g/spatie/laravel-missing-page-redirector.svg?style=flat-square)](https://scrutinizer-ci.com/g/spatie/laravel-missing-page-redirector)
[![StyleCI](https://styleci.io/repos/70787365/shield?branch=master)](https://styleci.io/repos/70787365)
[![Total Downloads](https://img.shields.io/packagist/dt/spatie/laravel-missing-page-redirector.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-missing-page-redirector)

When transitioning from a old site to a new one your URLs may change. If your old site was popular you probably want to retain your SEO worth. One way of doing this is by providing [permanent redirects from your old URLs to your new URLs](https://support.google.com/webmasters/answer/93633?hl=en). This package makes that process very easy.

When installed you only need to [add your redirects to the config file](https://github.com/spatie/laravel-missing-page-redirector#usage). Want to use the database as your source of redirects? [No problem](https://github.com/spatie/laravel-missing-page-redirector#creating-your-own-redirector)!

Spatie is a webdesign agency based in Antwerp, Belgium. You'll find an overview of all our open source projects [on our website](https://spatie.be/opensource).

## Postcardware

You're free to use this package (it's [MIT-licensed](LICENSE.md)), but if it makes it to your production environment you are required to send us a postcard from your hometown, mentioning which of our package(s) you are using.

Our address is: Spatie, Samberstraat 69D, 2060 Antwerp, Belgium.

The best postcards will get published on the open source page on our website.

## Installation

You can install the package via composer:

``` bash
composer require spatie/laravel-missing-page-redirector
```

Here's how to install the service provider:

```php
// config/app.php
'providers' => [
    ...
    Spatie\MissingPageRedirector\MissingPageRedirectorServiceProvider::class,
];
```

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

    /**
     * This is the class responsible for providing the URLs which must be redirected.
     * The only requirement for the redirector is that it needs to implement the
     * `Spatie\MissingPageRedirector\Redirector\Redirector`-interface
     */
    'redirector' => \Spatie\MissingPageRedirector\Redirector\ConfigurationRedirector::class,

    /**
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

If your missing page uses query strings, these are automatically supported without any additional configuration:

```php
    'redirects' => [
       '/old-search' => '/new-search',
    ],
```

When accessing ```/old-search?query=FooBar```, the request will automatically be redirected to ```/new-search?query=FooBar```

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

## About Spatie
Spatie is a webdesign agency based in Antwerp, Belgium. You'll find an overview of all our open source projects [on our website](https://spatie.be/opensource).

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
