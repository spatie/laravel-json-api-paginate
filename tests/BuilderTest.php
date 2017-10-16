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
        $paginator = TestModel::jsonPaginate();

        $this->assertCount(30, $paginator);
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
        $paginator = TestModel::jsonPaginate(50);

        $this->assertCount(40, $paginator);
    }
}
