# Changelog

All notable changes to `laravel-missing-page-redirector` will be documented in this file

## 2.11.1 - 2025-02-21

### What's Changed

* Laravel 12.x Compatibility by @laravel-shift in https://github.com/spatie/laravel-missing-page-redirector/pull/88

**Full Changelog**: https://github.com/spatie/laravel-missing-page-redirector/compare/2.11.0...2.11.1

## 2.11.0 - 2025-01-06

### What's Changed

* Update README.md by @hofmannsven in https://github.com/spatie/laravel-missing-page-redirector/pull/86
* Update README.md by @chengkangzai in https://github.com/spatie/laravel-missing-page-redirector/pull/85
* fix php8.4 nullable is deprecated by @it-can in https://github.com/spatie/laravel-missing-page-redirector/pull/87

### New Contributors

* @hofmannsven made their first contribution in https://github.com/spatie/laravel-missing-page-redirector/pull/86
* @chengkangzai made their first contribution in https://github.com/spatie/laravel-missing-page-redirector/pull/85
* @it-can made their first contribution in https://github.com/spatie/laravel-missing-page-redirector/pull/87

**Full Changelog**: https://github.com/spatie/laravel-missing-page-redirector/compare/2.10.0...2.11.0

## 2.10.0 - 2024-03-12

### What's Changed

* Laravel 11.x Compatibility by @laravel-shift in https://github.com/spatie/laravel-missing-page-redirector/pull/84

**Full Changelog**: https://github.com/spatie/laravel-missing-page-redirector/compare/2.9.4...2.10.0

## 2.9.4 - 2023-01-24

### What's Changed

- Refactor tests to Pest by @alexmanase in https://github.com/spatie/laravel-missing-page-redirector/pull/79
- Add PHP 8.2 Support by @patinthehat in https://github.com/spatie/laravel-missing-page-redirector/pull/80
- Laravel 10.x Compatibility by @laravel-shift in https://github.com/spatie/laravel-missing-page-redirector/pull/81

### New Contributors

- @alexmanase made their first contribution in https://github.com/spatie/laravel-missing-page-redirector/pull/79
- @patinthehat made their first contribution in https://github.com/spatie/laravel-missing-page-redirector/pull/80

**Full Changelog**: https://github.com/spatie/laravel-missing-page-redirector/compare/2.9.3...2.9.4

## 2.9.3 - 2022-10-13

### What's Changed

- Use Laravel container on private Router - closes #77 by @rodrigopedra in https://github.com/spatie/laravel-missing-page-redirector/pull/78

### New Contributors

- @rodrigopedra made their first contribution in https://github.com/spatie/laravel-missing-page-redirector/pull/78

**Full Changelog**: https://github.com/spatie/laravel-missing-page-redirector/compare/2.9.2...2.9.3

## 2.9.2 - 2022-05-13

## What's Changed

- remove Str::of for Laravel 6 compatibility by @chrisGeonet in https://github.com/spatie/laravel-missing-page-redirector/pull/76

## New Contributors

- @chrisGeonet made their first contribution in https://github.com/spatie/laravel-missing-page-redirector/pull/76

**Full Changelog**: https://github.com/spatie/laravel-missing-page-redirector/compare/2.9.1...2.9.2

## 2.9.1 - 2022-04-21

- use `Str` class instead of `str` helper function

**Full Changelog**: https://github.com/spatie/laravel-missing-page-redirector/compare/2.9.0...2.9.1

## 2.9.0 - 2022-04-21

- Add support for wildcard route parameters that span multiple route segments (`/old/*` -> `/new/{wildcard}`)

**Full Changelog**: https://github.com/spatie/laravel-missing-page-redirector/compare/2.8.0...2.9.0

## 2.7.2 - 2021-04-06

- prep for Octane

## 2.7.1 - 2020-12-04

- add support for PHP 8

## 2.7.0 - 2020-09-09

- add support for Laravel 8

## 2.6.0 - 2020-03-03

- add support for Laravel 7

## 2.5.0 - 2019-09-04

- add support for Laravel 6

## 2.4.0 - 2019-02-27

- drop support for PHP 7.1 and below
- drop support for Laravel 5.7 and below

## 2.3.4 - 2019-02-27

- add support for Laravel 5.8

## 2.3.3 - 2018-12-29

- fix for PHP 7.3

## 2.3.2 - 2018-08-27

- Added: Laravel 5.7 compatibility

## 2.3.1 - 2018-08-14

- Fixed: Optional parameters not working as expected (#44)

## 2.3.0 - 2018-05-02

- Added: an event will get fired when a route was not found

## 2.2.0 - 2018-02-08

- Added: Laravel 5.6 compatibility

## 2.1.1 - 2017-10-19

- Added: Response code to `RouteWasHit` event

## 2.1.0 - 2017-09-09

- Added: Allow redirects to be enable on a status code basis

## 2.0.0 - 2017-08-31

- Added: Laravel 5.5 compatibility
- Removed: Dropped support for older Laravel versions
- Changed: Renamed config file from `laravel-missing-page-redirector` to `missing-page-redirector`
- Refactored tests

## 1.3.0 - 2017-06-11

- Added: `RouteWasHit` event

## 1.2.0 - 2017-01-23

- Added: Laravel 5.4 compatibility
- Removed: Dropped support for older Laravel versions

## 1.1.0 - 2016-10-27

- Added: Support for determining http status code for a redirect

## 1.0.0 - 2016-10-14

- Initial release
