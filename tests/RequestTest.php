<?php

namespace Spatie\JsonApiPaginate\Test;

class RequestTest extends TestCase
{
    /** @test */
    public function it_will_discover_the_page_size_parameter()
    {
        $response = $this->get('/?page[size]=2');

        $response->assertJsonFragment(['per_page' => 2]);
    }

    /** @test */
    public function it_will_discover_the_page_number_parameter()
    {
        $response = $this->get('/?page[number]=2');

        $response->assertJsonFragment(['current_page' => 2]);
    }

    /** @test */
    public function it_will_discover_the_cursor_parameter()
    {
        $response = $this->get('cursor/?page[cursor]=eyJpZCI6MTAsIl9wb2ludHNUb05leHRJdGVtcyI6dHJ1ZX0');

        $response->assertJsonFragment(['prev_cursor' => 'eyJpZCI6MTEsIl9wb2ludHNUb05leHRJdGVtcyI6ZmFsc2V9']);
    }

    /** @test */
    public function it_will_use_the_default_page_size()
    {
        $response = $this->get('/');

        $response->assertJsonFragment(['per_page' => 30]);
    }

    /** @test */
    public function it_will_use_the_configured_page_size_parameter()
    {
        config(['json-api-paginate.size_parameter' => 'modified_size']);

        $response = $this->get('/?page[modified_size]=2');

        $response->assertJsonFragment(['per_page' => 2]);
    }

    /** @test */
    public function it_will_use_the_configured_page_size_parameter_for_cursor()
    {
        config(['json-api-paginate.size_parameter' => 'modified_size']);

        $response = $this->get('cursor/?page[modified_size]=2');

        $response->assertJsonFragment(['per_page' => 2]);
    }

    /** @test */
    public function it_will_use_the_configured_page_number_parameter()
    {
        config(['json-api-paginate.number_parameter' => 'modified_number']);

        $response = $this->get('/?page[modified_number]=2');

        $response->assertJsonFragment(['current_page' => 2]);
    }

    /** @test */
    public function it_will_use_the_configured_cursor_parameter()
    {
        config(['json-api-paginate.cursor_parameter' => 'modified_cursor']);


        $response = $this->get('cursor/?page[size]=10&page[modified_cursor]=eyJpZCI6MTAsIl9wb2ludHNUb05leHRJdGVtcyI6dHJ1ZX0');
        $response->assertJsonFragment(['next_cursor' => 'eyJpZCI6MjAsIl9wb2ludHNUb05leHRJdGVtcyI6dHJ1ZX0']);
    }

    /** @test */
    public function it_will_use_the_configured_size_parameter_for_cursor()
    {
        $response = $this->get('cursor/?page[size]=10');

        $response->assertJsonFragment(['next_cursor' => 'eyJpZCI6MTAsIl9wb2ludHNUb05leHRJdGVtcyI6dHJ1ZX0']);
    }
}
