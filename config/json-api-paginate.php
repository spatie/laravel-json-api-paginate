<?php

return [

    /*
     * The maximum number of results that will be returned
     * when using the JSON API paginator.
     */
    'max_results' => 0,

    /*
     * The default number of results that will be returned
     * when using the JSON API paginator.
     */
    'default_size' => 50,

    /*
     * The key of the page[x] query string parameter for page number.
     */
    'number_parameter' => 'number',

    /*
     * The key of the page[x] query string parameter for page size.
     */
    'size_parameter' => 'size',

    /*
     * The name of the macro that is added to the Eloquent query builder.
     */
    'method_name' => 'jsonPaginate',

    /*
     * Here you can override the base url to be used in the link items.
     */
    'base_url' => null,
];
