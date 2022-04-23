<?php

namespace Spatie\JsonApiPaginate\Test;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

class BuilderTest extends TestCase
{
    /** @test */
    public function it_can_paginate_records()
    {
        $paginator = TestModel::jsonPaginate();

        $this->assertEquals('http://localhost?page%5Bnumber%5D=2', $paginator->nextPageUrl());
    }

    /** @test */
    public function it_can_paginate_records_with_cursor()
    {
        config()->set('json-api-paginate.use_cursor_pagination', true);

        $paginator = TestModel::jsonPaginate();

        $this->assertEquals('http://localhost?page%5Bcursor%5D=eyJ0ZXN0X21vZGVscy5pZCI6MzAsIl9wb2ludHNUb05leHRJdGVtcyI6dHJ1ZX0', $paginator->nextPageUrl());
    }

    /** @test */
    public function it_returns_the_amount_of_records_specified_in_the_config_file()
    {
        config()->set('json-api-paginate.default_size', 10);

        $paginator = TestModel::jsonPaginate();

        $this->assertCount(10, $paginator);
    }

    /** @test */
    public function it_returns_the_amount_of_records_specified_in_the_config_file_using_cursor()
    {
        config()->set('json-api-paginate.use_cursor_pagination', true);
        config()->set('json-api-paginate.default_size', 10);

        $paginator = TestModel::jsonPaginate();

        $this->assertCount(10, $paginator);
    }

    /** @test */
    public function it_can_return_the_specified_amount_of_records()
    {
        $paginator = TestModel::jsonPaginate(15);

        $this->assertCount(15, $paginator);
    }

    /** @test */
    public function it_can_return_the_specified_amount_of_records_with_cursor()
    {
        config()->set('json-api-paginate.use_cursor_pagination', true);

        $paginator = TestModel::jsonPaginate(15);

        $this->assertCount(15, $paginator);
    }

    /** @test */
    public function it_will_not_return_more_records_that_the_configured_maximum()
    {
        $paginator = TestModel::jsonPaginate(15);

        $this->assertCount(15, $paginator);
    }

    /** @test */
    public function it_can_set_a_custom_base_url_in_the_config_file()
    {
        config()->set('json-api-paginate.base_url', 'https://example.com');

        $paginator = TestModel::jsonPaginate();

        $this->assertEquals('https://example.com?page%5Bnumber%5D=2', $paginator->nextPageUrl());
    }

    /** @test */
    public function it_can_use_simple_pagination()
    {
        config()->set('json-api-paginate.use_simple_pagination', true);

        $paginator = TestModel::jsonPaginate();

        $this->assertFalse(method_exists($paginator, 'total'));
    }

    /** @test */
    public function it_can_use_cursor_pagination()
    {
        config()->set('json-api-paginate.use_cursor_pagination', true);

        $paginator = TestModel::jsonPaginate();

        $this->assertFalse(method_exists($paginator, 'total'));
    }

    /** @test */
    public function it_can_use_base_query_builder()
    {
        $paginator = DB::table('test_models')->jsonPaginate();
        $this->assertEquals('http://localhost?page%5Bnumber%5D=2', $paginator->nextPageUrl());
    }

    /** @test */
    public function it_can_use_base_query_builder_with_cursor_pagination()
    {
        config()->set('json-api-paginate.use_cursor_pagination', true);

        $paginator = DB::table('test_models')->orderBy('id')->jsonPaginate(10);
        $this->assertEquals('http://localhost?page%5Bcursor%5D=eyJpZCI6MTAsIl9wb2ludHNUb05leHRJdGVtcyI6dHJ1ZX0', $paginator->nextPageUrl());
    }
}
