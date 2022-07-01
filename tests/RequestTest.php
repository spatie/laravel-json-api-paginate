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

    /** @test */
    public function it_will_append_other_parameters_to_urls()
    {
        $response = $this->get('/?page[size]=10&page[number]=3');

        $response->assertJsonFragment([
            'next_page_url' => url('/?page%5Bsize%5D=10&page%5Bnumber%5D=4'),
            'prev_page_url' => url('/?page%5Bsize%5D=10&page%5Bnumber%5D=2'),
        ]);
    }

    /** @test */
    public function it_will_append_other_parameters_to_urls_for_cursor()
    {
        $response = $this->get('cursor/?page[size]=10&page[cursor]=eyJpZCI6MTAsIl9wb2ludHNUb05leHRJdGVtcyI6dHJ1ZX0');

        $response->assertJsonFragment([
            'next_page_url' => url('cursor/?page%5Bsize%5D=10&page%5Bcursor%5D=eyJpZCI6MjAsIl9wb2ludHNUb05leHRJdGVtcyI6dHJ1ZX0'),
            'prev_page_url' => url('cursor/?page%5Bsize%5D=10&page%5Bcursor%5D=eyJpZCI6MTEsIl9wb2ludHNUb05leHRJdGVtcyI6ZmFsc2V9'),
        ]);
    }

    /** @test */
    public function it_will_use_default_size_when_page_size_is_zero()
    {
        $default_size = config('json-api-paginate.default_size');

        $response = $this->get('/?page[size]=0');

        $response->assertJsonFragment(['per_page' => $default_size]);
    }

    /** @test */
    public function it_will_use_default_size_when_page_size_is_negative()
    {
        $default_size = config('json-api-paginate.default_size');

        $response = $this->get('/?page[size]=-1');

        $response->assertJsonFragment(['per_page' => $default_size]);
    }

    /** @test */
    public function it_will_use_default_size_when_page_size_is_illegal()
    {
        $default_size = config('json-api-paginate.default_size');

        $response = $this->get('/?page[size]=Rpfwj5N1b7');

        $response->assertJsonFragment(['per_page' => $default_size]);
    }
}
