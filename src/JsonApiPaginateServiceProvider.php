<?php

namespace Spatie\JsonApiPaginate;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as BaseBuilder;
use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider;

class JsonApiPaginateServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/json-api-paginate.php' => config_path('json-api-paginate.php'),
            ], 'config');
        }

        $this->registerMacro();
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/json-api-paginate.php', 'json-api-paginate');
    }

    protected function registerMacro()
    {
        $macro = function (int $maxResults = null, int $defaultSize = null) {
            $maxResults = $maxResults ?? config('json-api-paginate.max_results');
            $defaultSize = $defaultSize ?? config('json-api-paginate.default_size');
            $numberParameter = config('json-api-paginate.number_parameter');
            $cursorParameter = config('json-api-paginate.cursor_parameter');
            $sizeParameter = config('json-api-paginate.size_parameter');
            $paginationParameter = config('json-api-paginate.pagination_parameter');
            $paginationMethod = config('json-api-paginate.use_cursor_pagination')
                ? 'cursorPaginate'
                : (config('json-api-paginate.use_simple_pagination') ? 'simplePaginate' : 'paginate');

            $size = (int) request()->input($paginationParameter.'.'.$sizeParameter, $defaultSize);
            $cursor = (string) request()->input($paginationParameter.'.'.$cursorParameter);

            if ($size <= 0) {
                $size = $defaultSize;
            }

            if ($size > $maxResults) {
                $size = $maxResults;
            }

            $paginator = $paginationMethod === 'cursorPaginate'
                ? $this->{$paginationMethod}($size, ['*'], $paginationParameter.'['.$cursorParameter.']', $cursor)
                    ->appends(Arr::except(request()->input(), $paginationParameter.'.'.$cursorParameter))
                : $this
                    ->{$paginationMethod}($size, ['*'], $paginationParameter.'.'.$numberParameter)
                    ->setPageName($paginationParameter.'['.$numberParameter.']')
                    ->appends(Arr::except(request()->input(), $paginationParameter.'.'.$numberParameter));

            if (! is_null(config('json-api-paginate.base_url'))) {
                $paginator->setPath(config('json-api-paginate.base_url'));
            }

            return $paginator;
        };

        EloquentBuilder::macro(config('json-api-paginate.method_name'), $macro);
        BaseBuilder::macro(config('json-api-paginate.method_name'), $macro);
    }
}
