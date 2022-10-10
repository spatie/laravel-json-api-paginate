<?php

namespace Spatie\JsonApiPaginate\Test\BelongsToMany;

use Carbon\Carbon;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Application;
use Orchestra\Testbench\TestCase as Orchestra;
use Route;

abstract class TestCase extends Orchestra
{
    public function setUp(): void
    {
        parent::setUp();

        Carbon::setTestNow(Carbon::create('2017', '1', '1', '1', '1', '1'));

        $this->setUpDatabase($this->app);

        $this->setUpRoutes($this->app);
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            \Spatie\JsonApiPaginate\JsonApiPaginateServiceProvider::class,
        ];
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        $app['config']->set('app.key', '6rE9Nz59bGRbeMATftriyQjrpF7DcOQm');
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     */
    protected function setUpDatabase($app)
    {
        $app['db']->connection()->getSchemaBuilder()->create('test_models', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamps();
        });

        $app['db']->connection()->getSchemaBuilder()->create('result_models', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamps();
        });

        $app['db']->connection()->getSchemaBuilder()->create('result_model_test_model', function (Blueprint $table) {
            $table->increments('id');
            $table->foreignId('result_model_id');
            $table->foreignId('test_model_id');
        });

        $test = TestModel::create(['name' => "test1"]);

        foreach (range(1, 40) as $index) {
            $result = ResultModel::create(['name' => "result{$index}"]);

            $test->results()->attach($result);
        }
    }

    protected function setUpRoutes(Application $app)
    {
        Route::any('/', function () {
            return TestModel::find(1)->results()->jsonPaginate();
        });

        Route::any('cursor/', function () {
            config()->set('json-api-paginate.use_cursor_pagination', true);

            return TestModel::find(1)->results()->orderBy('result_models.id')->jsonPaginate();
        });
    }
}
