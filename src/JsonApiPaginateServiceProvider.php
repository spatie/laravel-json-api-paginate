<?php

namespace Spatie\JsonApiPaginate;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
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
        Builder::macro(config('json-api-paginate.method_name'), function (int $maxResults = null, int $defaultSize = null) {
            return resolve(ForwardPagination::class)->execute($this, $maxResults, $defaultSize);
        });

        BelongsToMany::macro(config('json-api-paginate.method_name'), function (int $maxResults = null, int $defaultSize = null) {
            return resolve(ForwardPagination::class)->execute($this, $maxResults, $defaultSize);
        });

        HasManyThrough::macro(config('json-api-paginate.method_name'), function (int $maxResults = null, int $defaultSize = null) {
            return resolve(ForwardPagination::class)->execute($this, $maxResults, $defaultSize);
        });
    }
}
