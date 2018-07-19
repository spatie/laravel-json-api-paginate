<?php

namespace Spatie\JsonApiPaginate\Test;

class BuilderTest extends TestCase
{
    /** @test */
    public function it_can_paginate_records()
    {
        $paginator = TestModel::jsonPaginate();

        $this->assertEquals('http://localhost?page%5Bnumber%5D=2', $paginator->nextPageUrl());
    }

    /** @test */
    public function it_returns_the_amount_of_records_specified_in_the_config_file()
    {
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
    public function it_will_not_return_more_records_that_the_configured_maximum()
    {
        $paginator = TestModel::jsonPaginate(15);

        $this->assertCount(15, $paginator);
    }

    /** @test */
    public function it_will_return_the_configured_default_when_maximum_is_removed()
    {
        $paginator = TestModel::jsonPaginate(0);

        $this->assertCount(30, $paginator);
    }

    /** @test */
    public function it_can_set_a_custom_base_url_in_the_config_file()
    {
        config()->set('json-api-paginate.base_url', 'https://example.com');

        $paginator = TestModel::jsonPaginate();

        $this->assertEquals('https://example.com?page%5Bnumber%5D=2', $paginator->nextPageUrl());
    }
}
