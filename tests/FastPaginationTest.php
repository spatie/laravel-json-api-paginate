<?php

use Composer\InstalledVersions;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Spatie\JsonApiPaginate\Test\TestModel;
use Symfony\Component\HttpKernel\Exception\HttpException;

beforeEach(function () {
    config()->set('json-api-paginate.use_fast_pagination', true);

    $this->installedPackages = InstalledVersions::getAllRawData()[0];

    // Eloquent's builder keeps its global macros in a static, and has no way to
    // unregister one, so we put back whatever was registered before.
    $this->registeredMacros = new ReflectionProperty(EloquentBuilder::class, 'macros');
    $this->originalMacros = $this->registeredMacros->getValue();
});

afterEach(function () {
    InstalledVersions::reload($this->installedPackages);

    $this->registeredMacros->setValue(null, $this->originalMacros);
});

function pretendPackageIsInstalled(string $package): void
{
    $installed = InstalledVersions::getAllRawData()[0];

    $installed['versions'][$package] = [
        'pretty_version' => '1.0.0',
        'version' => '1.0.0.0',
        'type' => 'library',
        'install_path' => __DIR__,
        'aliases' => [],
        'reference' => null,
        'dev_requirement' => false,
    ];

    InstalledVersions::reload($installed);

    EloquentBuilder::macro('fastPaginate', fn (...$arguments) => $this->paginate(...$arguments));
    EloquentBuilder::macro('simpleFastPaginate', fn (...$arguments) => $this->simplePaginate(...$arguments));
}

it('fast paginates when a known package is installed', function (string $package) {
    pretendPackageIsInstalled($package);

    expect(TestModel::jsonPaginate()->nextPageUrl())
        ->toEqual('http://localhost?page%5Bnumber%5D=2');
})->with([
    'spatie/laravel-fast-paginate',
    'aaronfrancis/fast-paginate',
    'hammerstone/fast-paginate',
]);

it('simple fast paginates when a known package is installed', function () {
    config()->set('json-api-paginate.use_simple_pagination', true);

    pretendPackageIsInstalled('spatie/laravel-fast-paginate');

    expect(TestModel::jsonPaginate()->nextPageUrl())
        ->toEqual('http://localhost?page%5Bnumber%5D=2');
});

it('aborts when no fast pagination package is installed', function () {
    TestModel::jsonPaginate();
})->throws(HttpException::class, 'You need to install spatie/laravel-fast-paginate to use fast pagination.');

it('does not look for a package when fast pagination is off', function () {
    config()->set('json-api-paginate.use_fast_pagination', false);

    expect(TestModel::jsonPaginate()->nextPageUrl())
        ->toEqual('http://localhost?page%5Bnumber%5D=2');
});
