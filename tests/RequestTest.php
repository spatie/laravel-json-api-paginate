<?php

it('will discover the page size parameter')
    ->get('/?page[size]=2')
    ->assertJsonFragment(['per_page' => 2]);

it('will discover the page number parameter')
    ->get('/?page[number]=2')
    ->assertJsonFragment(['current_page' => 2]);

it('will discover the cursor parameter')
    ->get('cursor/?page[cursor]=eyJpZCI6MTAsIl9wb2ludHNUb05leHRJdGVtcyI6dHJ1ZX0')
    ->assertJsonFragment(['prev_cursor' => 'eyJpZCI6MTEsIl9wb2ludHNUb05leHRJdGVtcyI6ZmFsc2V9']);

it('will use the default page size')
    ->get('/')
    ->assertJsonFragment(['per_page' => 30]);

it('will use the configured page size parameter')
    ->tap(fn () => config(['json-api-paginate.size_parameter' => 'modified_size']))
    ->get('/?page[modified_size]=2')
    ->assertJsonFragment(['per_page' => 2]);

it('will use the configured page size parameter for cursor')
    ->tap(fn () => config(['json-api-paginate.size_parameter' => 'modified_size']))
    ->get('cursor/?page[modified_size]=2')
    ->assertJsonFragment(['per_page' => 2]);

it('will use the configured page number parameter')
    ->tap(fn () => config(['json-api-paginate.number_parameter' => 'modified_number']))
    ->get('/?page[modified_number]=2')
    ->assertJsonFragment(['current_page' => 2]);

it('will use the configured cursor parameter')
    ->tap(fn () => config(['json-api-paginate.cursor_parameter' => 'modified_cursor']))
    ->get('cursor/?page[size]=10&page[modified_cursor]=eyJpZCI6MTAsIl9wb2ludHNUb05leHRJdGVtcyI6dHJ1ZX0')
    ->assertJsonFragment(['next_cursor' => 'eyJpZCI6MjAsIl9wb2ludHNUb05leHRJdGVtcyI6dHJ1ZX0']);

it('will use the configured size parameter for cursor')
    ->get('cursor/?page[size]=10')
    ->assertJsonFragment([
        'per_page' => 10,
        'next_cursor' => 'eyJpZCI6MTAsIl9wb2ludHNUb05leHRJdGVtcyI6dHJ1ZX0'
    ]);

it('will use default size when page size is 0', function () {
    $default_size = config('json-api-paginate.default_size');

    $response = $this->get('/?page[size]=0');

    $response->assertJsonFragment(['per_page' => $default_size]);
});

it('will use default size when page size is negative', function () {
    $default_size = config('json-api-paginate.default_size');

    $response = $this->get('/?page[size]=-1');

    $response->assertJsonFragment(['per_page' => $default_size]);
});

it('will use default size when page size is illegal', function () {
    $default_size = config('json-api-paginate.default_size');

    $response = $this->get('/?page[size]=Rpfwj5N1b7');

    $response->assertJsonFragment(['per_page' => $default_size]);
});

it('will append other parameters to urls', function() {
    $response = $this->get('/?page[size]=10&page[number]=3');

    $response->assertJsonFragment([
        'next_page_url' => url('/?page%5Bsize%5D=10&page%5Bnumber%5D=4'),
        'prev_page_url' => url('/?page%5Bsize%5D=10&page%5Bnumber%5D=2'),
    ]);
});

it('will append other parameters to urls for cursor', function() {
    $response = $this->get('cursor/?page[size]=10&page[cursor]=eyJpZCI6MTAsIl9wb2ludHNUb05leHRJdGVtcyI6dHJ1ZX0');
    
    $response->assertJsonFragment([
        'next_page_url' => url('cursor/?page%5Bsize%5D=10&page%5Bcursor%5D=eyJpZCI6MjAsIl9wb2ludHNUb05leHRJdGVtcyI6dHJ1ZX0'),
        'prev_page_url' => url('cursor/?page%5Bsize%5D=10&page%5Bcursor%5D=eyJpZCI6MTEsIl9wb2ludHNUb05leHRJdGVtcyI6ZmFsc2V9'),
    ]);
});
