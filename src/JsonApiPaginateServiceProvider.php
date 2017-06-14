<?php

namespace Spatie\JsonApiPaginate;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\ServiceProvider;

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
        $this->mergeConfigFrom(__DIR__ . '/../config/json-api-paginate.php', 'json-api-paginate');
    }

    protected function registerMacro()
    {
        Builder::macro('jsonPaginate', function (int $maxResults = null) {
            $configuredMaximum = config('json-api-paginate.max_results');

            if (is_null($maxResults)) {
                $maxResults = $configuredMaximum;
            }

            $size = request()->input('page.size', $maxResults);

            if ($size > $configuredMaximum) {
                $size = $configuredMaximum;
            }

            return $this->paginate($size, ['*'], 'page.number')
                ->setPageName('page[number]')
                ->appends(array_except(request()->input(), 'page.number'));;
        });
    }
}
