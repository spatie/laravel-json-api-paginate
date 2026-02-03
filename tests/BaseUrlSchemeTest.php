<?php

use Spatie\JsonApiPaginate\Test\TestModel;

describe('base_url with HTTP/HTTPS schemes', function () {

    describe('matching schemes', function () {

        it('uses HTTP base_url for HTTP request', function () {
            config()->set('json-api-paginate.base_url', 'http://example.com');

            $result = TestModel::jsonPaginate()->nextPageUrl();

            expect($result)->toEqual('http://example.com?page%5Bnumber%5D=2');
        });

        it('uses HTTPS base_url for HTTPS request', function () {
            config()->set('json-api-paginate.base_url', 'https://example.com');

            $_SERVER['HTTPS'] = 'on';
            $result = TestModel::jsonPaginate()->nextPageUrl();
            unset($_SERVER['HTTPS']);

            expect($result)->toEqual('https://example.com?page%5Bnumber%5D=2');
        });

    });

    describe('scheme mismatches', function () {

        it('downgrades to HTTP when base_url is HTTP but request is HTTPS', function () {
            config()->set('json-api-paginate.base_url', 'http://example.com');

            $_SERVER['HTTPS'] = 'on';
            $result = TestModel::jsonPaginate()->nextPageUrl();
            unset($_SERVER['HTTPS']);

            expect($result)->toEqual('http://example.com?page%5Bnumber%5D=2');
        });

        it('upgrades to HTTPS when base_url is HTTPS but request is HTTP', function () {
            config()->set('json-api-paginate.base_url', 'https://example.com');

            $result = TestModel::jsonPaginate()->nextPageUrl();

            expect($result)->toEqual('https://example.com?page%5Bnumber%5D=2');
        });

    });

    describe('path segments', function () {

        it('preserves path segments in base_url', function () {
            config()->set('json-api-paginate.base_url', 'https://api.example.com/v1');

            $result = TestModel::jsonPaginate()->nextPageUrl();

            expect($result)->toEqual('https://api.example.com/v1?page%5Bnumber%5D=2');
        });

        it('handles trailing slash in base_url', function () {
            config()->set('json-api-paginate.base_url', 'https://example.com/');

            $result = TestModel::jsonPaginate()->nextPageUrl();

            // Strict assertion - must include pagination parameters
            // Both formats acceptable: with or without slash before ?
            expect($result)->toMatch('/^https:\/\/example\.com\/?\?page%5Bnumber%5D=2$/');
        });

        it('handles path with trailing slash in base_url', function () {
            config()->set('json-api-paginate.base_url', 'https://example.com/api/');

            $result = TestModel::jsonPaginate()->nextPageUrl();

            // Strict assertion - must include pagination parameters
            expect($result)->toMatch('/^https:\/\/example\.com\/api\/?\?page%5Bnumber%5D=2$/');
        });

        it('includes pagination params with trailing slash', function () {
            config()->set('json-api-paginate.base_url', 'https://example.com/');

            $result = TestModel::jsonPaginate()->nextPageUrl();

            // Verify pagination parameters are never lost
            expect($result)->toContain('page%5Bnumber%5D=2');
        });

        it('includes pagination params with path and trailing slash', function () {
            config()->set('json-api-paginate.base_url', 'https://example.com/api/');

            $result = TestModel::jsonPaginate()->nextPageUrl();

            // Verify pagination parameters are never lost
            expect($result)->toContain('page%5Bnumber%5D=2');
        });

    });

    describe('query parameter preservation', function () {

        it('preserves query params across scheme mismatch', function () {
            config()->set('json-api-paginate.base_url', 'http://example.com');

            $_SERVER['HTTPS'] = 'on';

            $response = $this->get('/?filter=active&page[number]=1');

            unset($_SERVER['HTTPS']);

            $response->assertJsonFragment([
                'next_page_url' => 'http://example.com?filter=active&page%5Bnumber%5D=2',
            ]);
        });

        it('preserves complex query params with scheme mismatch', function () {
            config()->set('json-api-paginate.base_url', 'http://example.com');

            $_SERVER['HTTPS'] = 'on';

            $response = $this->get('/?filter[status]=active&sort=-created_at&page[number]=1');

            unset($_SERVER['HTTPS']);

            $nextUrl = $response->json('next_page_url');

            expect($nextUrl)
                ->toStartWith('http://example.com')
                ->toContain('filter%5Bstatus%5D=active')
                ->toContain('sort=-created_at');
        });

    });

    describe('cursor pagination', function () {

        it('uses correct scheme with cursor pagination on scheme mismatch', function () {
            config()->set('json-api-paginate.base_url', 'http://example.com');
            config()->set('json-api-paginate.use_cursor_pagination', true);

            $_SERVER['HTTPS'] = 'on';
            $result = TestModel::orderBy('id')->jsonPaginate()->nextPageUrl();
            unset($_SERVER['HTTPS']);

            expect($result)
                ->toStartWith('http://example.com')
                ->toContain('page%5Bcursor%5D=');
        });

        it('preserves query params with cursor and scheme mismatch', function () {
            config()->set('json-api-paginate.base_url', 'http://example.com');

            $_SERVER['HTTPS'] = 'on';

            $response = $this->get('cursor/?filter=active');

            unset($_SERVER['HTTPS']);

            $nextUrl = $response->json('next_page_url');

            expect($nextUrl)
                ->toStartWith('http://example.com')
                ->toContain('filter=active')
                ->toContain('page%5Bcursor%5D=');
        });

    });

    describe('edge cases', function () {

        it('preserves custom port in base_url', function () {
            config()->set('json-api-paginate.base_url', 'https://example.com:8443');

            $result = TestModel::jsonPaginate()->nextPageUrl();

            expect($result)->toEqual('https://example.com:8443?page%5Bnumber%5D=2');
        });

        it('uses different domain when specified in base_url', function () {
            config()->set('json-api-paginate.base_url', 'https://cdn.example.com');

            $result = TestModel::jsonPaginate()->nextPageUrl();

            expect($result)->toEqual('https://cdn.example.com?page%5Bnumber%5D=2');
        });

        it('handles relative path as base_url', function () {
            config()->set('json-api-paginate.base_url', '/api/v1');

            $result = TestModel::jsonPaginate()->nextPageUrl();

            // Document behavior: relative paths may produce unexpected results
            expect($result)->toContain('/api/v1');
        });

    });

});
