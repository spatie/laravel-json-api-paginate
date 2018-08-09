# A paginator that plays nice with the JSON API spec

[![Latest Version on Packagist](https://img.shields.io/packagist/v/spatie/laravel-json-api-paginate.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-json-api-paginate)
[![Build Status](https://img.shields.io/travis/spatie/laravel-json-api-paginate/master.svg?style=flat-square)](https://travis-ci.org/spatie/laravel-json-api-paginate)
[![Quality Score](https://img.shields.io/scrutinizer/g/spatie/laravel-json-api-paginate.svg?style=flat-square)](https://scrutinizer-ci.com/g/spatie/laravel-json-api-paginate)
[![StyleCI](https://styleci.io/repos/94352951/shield?branch=master)](https://styleci.io/repos/94352951)
[![Total Downloads](https://img.shields.io/packagist/dt/spatie/laravel-json-api-paginate.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-json-api-paginate)

In a vanilla Laravel application [the query builder paginators will listen to `page` request parameter](https://laravel.com/docs/5.4/pagination#paginating-query-builder-results). This works great, but it does not comply with [the json:api spec](http://jsonapi.org/). That spec [expects](http://jsonapi.org/examples/#pagination) the query builder paginator to listen to the `page[number]` and `page[size]` request parameters. 

This package adds a `jsonPaginate` method to the Eloquent query builder that listens to those parameters and adds [the pagination links the spec requires](http://jsonapi.org/format/#fetching-pagination).

## Installation

You can install the package via composer:

```bash
composer require spatie/laravel-json-api-paginate
```

In Laravel 5.5 and above the service provider will automatically get registered. In older versions of the framework just add the service provider in `config/app.php` file:

```php
'providers' => [
    ...
    Spatie\JsonApiPaginate\JsonApiPaginateServiceProvider::class,
];
```

Optionally you can publish the config file with:

```bash
php artisan vendor:publish --provider="Spatie\JsonApiPaginate\JsonApiPaginateServiceProvider" --tag="config"
```

This is the content of the file that will be published in `config/json-api-paginate.php`

```php
<?php

return [

    /*
     * The maximum number of results that will be returned
     * when using the JSON API paginator.
     */
    'max_results' => 30,

    /*
     * The default number of results that will be returned
     * when using the JSON API paginator.
     */
    'default_size' => 30,

    /*
     * The key of the page[x] query string parameter for page number.
     */
    'number_parameter' => 'number',

    /*
     * The key of the page[x] query string parameter for page size.
     */
    'size_parameter' => 'size',

    /*
     * The name of the macro that is added to the Eloquent query builder.
     */
    'method_name' => 'jsonPaginate',

    /**
     * Here you can override the base url to be used in the link items.
     */
    'base_url' => null,
];
```

## Usage

To paginate the results according to the json API spec, simply call the `jsonPaginate` method.

```php
YourModel::jsonPaginate();
```

Of course you may still use all the builder methods you know and love:

```php
YourModel::where('my_field', 'myValue')->jsonPaginate();
```

By default the maximum page size is set to 30. You can change this number in the `config` file or just pass the value to  `jsonPaginate`.

```php
$maxResults = 60;

YourModel::jsonPaginate($maxResults);
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

```bash
composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email freek@spatie.be instead of using the issue tracker.

## Postcardware

You're free to use this package (it's [MIT-licensed](LICENSE.md)), but if it makes it to your production environment we highly appreciate you sending us a postcard from your hometown, mentioning which of our package(s) you are using.

Our address is: Spatie, Samberstraat 69D, 2060 Antwerp, Belgium.

We publish all received postcards [on our company website](https://spatie.be/en/opensource/postcards).

## Credits

- [Freek Van der Herten](https://github.com/freekmurze)
- [All Contributors](../../contributors)

The base code of this page was published on [this Laracasts forum thread](https://laracasts.com/discuss/channels/laravel/pagination-using-json-api-strategy?page=1#reply-346619) by [Joram van den Boezem](https://twitter.com/@hongaar)

## Support us

Spatie is a webdesign agency based in Antwerp, Belgium. You'll find an overview of all our open source projects [on our website](https://spatie.be/opensource).

Does your business depend on our contributions? Reach out and support us on [Patreon](https://www.patreon.com/spatie). 
All pledges will be dedicated to allocating workforce on maintenance and new awesome stuff.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
