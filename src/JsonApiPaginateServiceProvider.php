<?php

namespace Spatie\JsonApiPaginate;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Builder;

class JsonApiPaginateServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/json-api-paginate.php' => config_path('json-api-paginate.php'),
            ], 'config');
        }

        $this->registerMacro();
    }

    /**
     * Register the service provider.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/json-api-paginate.php', 'json-api-paginate');
    }

    protected function registerMacro()
    {
        Builder::macro(config('json-api-paginate.method_name'), function (int $maxResults = null, int $defaultSize = null) {
            $maxResults = $maxResults ?? config('json-api-paginate.max_results');
            $defaultSize = $defaultSize ?? config('json-api-paginate.default_size');
            $numberParameter = config('json-api-paginate.number_parameter');
            $sizeParameter = config('json-api-paginate.size_parameter');

            $size = (int) request()->input('page.'.$sizeParameter, $defaultSize);

            if ($size > $maxResults) {
                $size = $maxResults;
            }

            return $this->paginate($size, ['*'], 'page.'.$numberParameter)
                ->setPageName('page['.$numberParameter.']')
                ->appends(array_except(request()->input(), 'page.'.$numberParameter));
        });
    }
}
