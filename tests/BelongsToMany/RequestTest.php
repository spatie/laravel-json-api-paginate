<?php

namespace Spatie\JsonApiPaginate\Test\BelongsToMany;

use PHPUnit\Framework\Attributes\Test;

class RequestTest extends TestCase
{
    #[Test]
    public function it_will_discover_the_page_size_parameter()
    {
        $response = $this->get('/?page[size]=2');

        $response->assertJsonFragment(['per_page' => 2]);
    }

    #[Test]
    public function it_will_discover_the_page_number_parameter()
    {
        $response = $this->get('/?page[number]=2');

        $response->assertJsonFragment(['current_page' => 2]);
    }

    #[Test]
    public function it_will_discover_the_cursor_parameter()
    {
        $response = $this->get('cursor/?page[cursor]=eyJyZXN1bHRfbW9kZWxzLmlkIjozMCwiX3BvaW50c1RvTmV4dEl0ZW1zIjp0cnVlfQ');

        $response->assertJsonFragment(['prev_cursor' => 'eyJyZXN1bHRfbW9kZWxzLmlkIjozMSwiX3BvaW50c1RvTmV4dEl0ZW1zIjpmYWxzZX0']);
    }

    #[Test]
    public function it_will_use_the_default_page_size()
    {
        $response = $this->get('/');

        $response->assertJsonFragment(['per_page' => 30]);
    }

    #[Test]
    public function it_will_use_the_configured_page_size_parameter()
    {
        config(['json-api-paginate.size_parameter' => 'modified_size']);

        $response = $this->get('/?page[modified_size]=2');

        $response->assertJsonFragment(['per_page' => 2]);
    }

    #[Test]
    public function it_will_use_the_configured_page_size_parameter_for_cursor()
    {
        config(['json-api-paginate.size_parameter' => 'modified_size']);

        $response = $this->get('cursor/?page[modified_size]=2');

        $response->assertJsonFragment(['per_page' => 2]);
    }

    #[Test]
    public function it_will_use_the_configured_page_number_parameter()
    {
        config(['json-api-paginate.number_parameter' => 'modified_number']);

        $response = $this->get('/?page[modified_number]=2');

        $response->assertJsonFragment(['current_page' => 2]);
    }

    #[Test]
    public function it_will_use_the_configured_cursor_parameter()
    {
        config(['json-api-paginate.cursor_parameter' => 'modified_cursor']);

        $response = $this->get('cursor/?page[size]=10&page[modified_cursor]=eyJyZXN1bHRfbW9kZWxzLmlkIjoxMCwiX3BvaW50c1RvTmV4dEl0ZW1zIjp0cnVlfQ');

        $response->assertJsonFragment(['next_cursor' => 'eyJyZXN1bHRfbW9kZWxzLmlkIjoyMCwiX3BvaW50c1RvTmV4dEl0ZW1zIjp0cnVlfQ']);
    }

    #[Test]
    public function it_will_use_the_configured_size_parameter_for_cursor()
    {
        $response = $this->get('cursor/?page[size]=10');

        $response->assertJsonFragment(['next_cursor' => 'eyJyZXN1bHRfbW9kZWxzLmlkIjoxMCwiX3BvaW50c1RvTmV4dEl0ZW1zIjp0cnVlfQ']);
    }

    public function it_will_use_default_size_when_page_size_is_zero()
    {
        $default_size = config('json-api-paginate.default_size');

        $response = $this->get('/?page[size]=0');

        $response->assertJsonFragment(['per_page' => $default_size]);
    }

    #[Test]
    public function it_will_use_default_size_when_page_size_is_negative()
    {
        $default_size = config('json-api-paginate.default_size');

        $response = $this->get('/?page[size]=-1');

        $response->assertJsonFragment(['per_page' => $default_size]);
    }

    #[Test]
    public function it_will_use_default_size_when_page_size_is_illegal()
    {
        $default_size = config('json-api-paginate.default_size');

        $response = $this->get('/?page[size]=Rpfwj5N1b7');

        $response->assertJsonFragment(['per_page' => $default_size]);
    }
}
