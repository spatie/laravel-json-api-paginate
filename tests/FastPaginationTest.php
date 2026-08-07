<?php

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Support\Facades\DB;
use Spatie\JsonApiPaginate\Test\TestModel;
use Symfony\Component\HttpKernel\Exception\HttpException;

// Eloquent's builder keeps its global macros in a static, and has no way to unregister
// one, so we put back whatever was registered before each test.
beforeEach(function () {
    config()->set('json-api-paginate.use_fast_pagination', true);

    $this->registeredMacros = new ReflectionProperty(EloquentBuilder::class, 'macros');
    $this->originalMacros = $this->registeredMacros->getValue();
});

afterEach(function () {
    $this->registeredMacros->setValue(null, $this->originalMacros);
});

it('uses the fastPaginate macro when a package provides one', function () {
    EloquentBuilder::macro('fastPaginate', fn (...$arguments) => $this->paginate(...$arguments));

    expect(TestModel::jsonPaginate()->nextPageUrl())
        ->toEqual('http://localhost?page%5Bnumber%5D=2');
});

it('uses the simpleFastPaginate macro when simple pagination is on', function () {
    config()->set('json-api-paginate.use_simple_pagination', true);

    EloquentBuilder::macro('simpleFastPaginate', fn (...$arguments) => $this->simplePaginate(...$arguments));

    expect(TestModel::jsonPaginate()->nextPageUrl())
        ->toEqual('http://localhost?page%5Bnumber%5D=2');
});

it('aborts when no package provides fast pagination', function () {
    TestModel::jsonPaginate();
})->throws(HttpException::class, 'No installed package provides a `fastPaginate` method.');

it('aborts on a query builder, which fast pagination packages do not cover', function () {
    EloquentBuilder::macro('fastPaginate', fn (...$arguments) => $this->paginate(...$arguments));

    DB::table('test_models')->jsonPaginate();
})->throws(HttpException::class, 'No installed package provides a `fastPaginate` method.');

it('does not look for a macro when fast pagination is off', function () {
    config()->set('json-api-paginate.use_fast_pagination', false);

    expect(TestModel::jsonPaginate()->nextPageUrl())
        ->toEqual('http://localhost?page%5Bnumber%5D=2');
});
