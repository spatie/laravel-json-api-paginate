<?php

namespace Spatie\JsonApiPaginate\Test;

class RequestTest extends TestCase
{
    /** @test */
    public function it_can_return_paginated_results()
    {
        $response = $this->get('/');

        $this->assertMatchesSnapshot(json_encode($response));
    }

    /** @test */
    public function it_will_discover_the_page_size_parameter()
    {
        $response = $this->get('/?page[size]=2');

        $response->assertJsonFragment(['per_page' => '2']);
    }

    /** @test */
    public function it_will_discover_the_page_number_parameter()
    {
        $response = $this->get('/?page[number]=2');

        $response->assertJsonFragment(['current_page' => 2]);
    }
}
