# A paginator that plays nice with the JSON API spec

[![Latest Version on Packagist](https://img.shields.io/packagist/v/spatie/laravel-json-api-paginate.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-json-api-paginate)
[![Check & fix styling](https://github.com/spatie/laravel-json-api-paginate/actions/workflows/php-cs-fixer.yml/badge.svg)](https://github.com/spatie/laravel-json-api-paginate/actions/workflows/php-cs-fixer.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/spatie/laravel-json-api-paginate.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-json-api-paginate)

In a vanilla Laravel application [the query builder paginators will listen to `page` request parameter](https://laravel.com/docs/master/pagination#paginating-query-builder-results). This works great, but it does not follow the example solution of [the json:api spec](http://jsonapi.org/). That example [expects](http://jsonapi.org/examples/#pagination) the query builder paginator to listen to the `page[number]` and `page[size]` request parameters.

This package adds a `jsonPaginate` method to the Eloquent query builder that listens to those parameters and adds [the pagination links the spec requires](http://jsonapi.org/format/#fetching-pagination).

## Support us

[<img src="https://github-ads.s3.eu-central-1.amazonaws.com/laravel-json-api-paginate.jpg?t=1" width="419px" />](https://spatie.be/github-ad-click/laravel-json-api-paginate)

We invest a lot of resources into creating [best in class open source packages](https://spatie.be/open-source). You can support us by [buying one of our paid products](https://spatie.be/open-source/support-us).

We highly appreciate you sending us a postcard from your hometown, mentioning which of our package(s) you are using. You'll find our address on [our contact page](https://spatie.be/about-us). We publish all received postcards on [our virtual postcard wall](https://spatie.be/open-source/postcards).

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
     * The key of the page[x] query string parameter for cursor.
     */
    'cursor_parameter' => 'cursor',

    /*
     * The name of the macro that is added to the Eloquent query builder.
     */
    'method_name' => 'jsonPaginate',

    /*
     * If you only need to display Next and Previous links, you may use
     * simple pagination to perform a more efficient query.
     */
    'use_simple_pagination' => false,

    /*
     * If you want to use cursor pagination, set this to true.
     * This would override use_simple_pagination.
     */
    'use_cursor_pagination' => false,

    /*
     * use simpleFastPaginate() or fastPaginate from https://github.com/hammerstonedev/fast-paginate
     * use may installed it via `composer require hammerstone/fast-paginate`
     */
    'use_fast_pagination' => false,

    /*
     * Here you can override the base url to be used in the link items.
     */
    'base_url' => null,

    /*
     * The name of the query parameter used for pagination
     */
    'pagination_parameter' => 'page',
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

You can also paginate results for relations:

```php
$model = YourModel::find(1);

$model->relation()->jsonPaginate();
```

### Override default behavior

By default the maximum page size is set to 30. You can change this number in the `config` file or just pass the value to  `jsonPaginate`.

```php
$maxResults = 60;

YourModel::jsonPaginate($maxResults);
```

By default the default page size is set to 30. You can change this number in the `config` file or just pass the value to  `jsonPaginate`.

```php
$defaultSize = 15;

YourModel::jsonPaginate(null, $defaultSize);
```

You can also pass the total count to the `paginate` function directly. This can be useful for performance reasons or to prevent issues with `DISTINCT` keyword ([more info](https://github.com/laravel/framework/issues?q=is%3Aissue+paginate+total)).

⚠️ This is effective only with basic pagination (no effect with cursor, simple or fast pagination)

```php
$total = 42;

YourModel::jsonPaginate(null, null, $total);
```

### Cursor pagination

This package also supports cursor pagination, which can be briefly defined by the Laravel Framework as follows:

> While paginate and simplePaginate create queries using the SQL "offset" clause, cursor pagination works by constructing "where" clauses that compare the values of the ordered columns contained in the query, providing the most efficient database performance available amongst all of Laravel's pagination methods.

You can find more about cursor pagination in the [Laravel Documentation](https://laravel.com/docs/9.x/pagination#cursor-pagination).

If you want to use cursor pagination, you can set the `use_cursor_pagination` to true in the `config` file.

It's also possible to modify the pagination parameter in the `config` file, by modifying the `cursor_parameter` value.

```php
<?php

return [
    // ..... other config options .....

    /*
     * The key of the page[x] query string parameter for cursor.
     */
    'cursor_parameter' => 'cursor',

    /*
     * If you want to cursor pagination, set this to true.
     * This would override use_simple_pagination.
     */
    'use_cursor_pagination' => true,

    // ..... other config options .....
];

```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

```bash
composer test
```

## Contributing

Please see [CONTRIBUTING](https://github.com/spatie/.github/blob/main/CONTRIBUTING.md) for details.

## Security

If you've found a bug regarding security please mail [security@spatie.be](mailto:security@spatie.be) instead of using the issue tracker.

## Credits

- [Freek Van der Herten](https://github.com/freekmurze)
- [All Contributors](../../contributors)

The base code of this page was published on [this Laracasts forum thread](https://laracasts.com/discuss/channels/laravel/pagination-using-json-api-strategy?page=1#reply-346619) by [Joram van den Boezem](https://twitter.com/@hongaar)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
