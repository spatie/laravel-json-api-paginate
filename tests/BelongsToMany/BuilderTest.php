<?php

namespace Spatie\JsonApiPaginate\Test\BelongsToMany;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use PHPUnit\Framework\Attributes\Test;

class BuilderTest extends TestCase
{
    #[Test]
    public function it_can_paginate_records()
    {
        $paginator = TestModel::find(1)->results()->jsonPaginate();

        $this->assertEquals('http://localhost?page%5Bnumber%5D=2', $paginator->nextPageUrl());
    }

    #[Test]
    public function it_can_paginate_records_with_cursor()
    {
        config()->set('json-api-paginate.use_cursor_pagination', true);

        $paginator = TestModel::find(1)->results()->jsonPaginate();

        $this->assertEquals('http://localhost?page%5Bcursor%5D=eyJyZXN1bHRfbW9kZWxzLmlkIjozMCwiX3BvaW50c1RvTmV4dEl0ZW1zIjp0cnVlfQ', $paginator->nextPageUrl());
    }

    #[Test]
    public function it_returns_the_amount_of_records_specified_in_the_config_file()
    {
        config()->set('json-api-paginate.default_size', 10);

        $paginator = TestModel::find(1)->results()->jsonPaginate();

        $this->assertCount(10, $paginator);
    }

    #[Test]
    public function it_returns_the_amount_of_records_specified_in_the_config_file_using_cursor()
    {
        config()->set('json-api-paginate.use_cursor_pagination', true);
        config()->set('json-api-paginate.default_size', 10);

        $paginator = TestModel::find(1)->results()->jsonPaginate();

        $this->assertCount(10, $paginator);
    }

    #[Test]
    public function it_can_return_the_specified_amount_of_records()
    {
        $paginator = TestModel::find(1)->results()->jsonPaginate(15);

        $this->assertCount(15, $paginator);
    }

    #[Test]
    public function it_can_return_the_specified_amount_of_records_with_cursor()
    {
        config()->set('json-api-paginate.use_cursor_pagination', true);

        $paginator = TestModel::find(1)->results()->jsonPaginate(15);

        $this->assertCount(15, $paginator);
    }

    #[Test]
    public function it_will_not_return_more_records_that_the_configured_maximum()
    {
        $paginator = TestModel::find(1)->results()->jsonPaginate(15);

        $this->assertCount(15, $paginator);
    }

    #[Test]
    public function it_can_set_a_custom_base_url_in_the_config_file()
    {
        config()->set('json-api-paginate.base_url', 'https://example.com');

        $paginator = TestModel::find(1)->results()->jsonPaginate();

        $this->assertEquals('https://example.com?page%5Bnumber%5D=2', $paginator->nextPageUrl());
    }

    #[Test]
    public function it_can_use_simple_pagination()
    {
        config()->set('json-api-paginate.use_simple_pagination', true);

        $paginator = TestModel::find(1)->results()->jsonPaginate();

        $this->assertFalse(method_exists($paginator, 'total'));
    }

    #[Test]
    public function it_can_use_cursor_pagination()
    {
        config()->set('json-api-paginate.use_cursor_pagination', true);

        $paginator = TestModel::find(1)->results()->jsonPaginate();

        $this->assertFalse(method_exists($paginator, 'total'));
    }
}
