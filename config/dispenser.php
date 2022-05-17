<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Subject Repositories
    |--------------------------------------------------------------------------
    |
    | Underlying system's inquiry and operator repository implementations.
    |
    */

    'repository' => [
        //'inquiry' => TODO: bind repository implementation,
        //'operator' => TODO: bind repository implementation,
    ],

    /*
    |--------------------------------------------------------------------------
    | Subject Matching and Dispense Rules
    |--------------------------------------------------------------------------
    |
    | Inquiries and operators must be filtered and ordered before matching.
    | This is usually done by specific factors and traits respectively.
    | Matches also must go through that procedure before first bindings.
    |
    */

    'matching' => [

        'inquiry' => [
            'filtering' => '!bound',
            'ordering' => ['weight', SORT_DESC],
        ],

        'operator' => [
            'filtering' => ['!busy', 'online', 'ready'],
            'ordering' => ['inquiryCount', 'latestBindingTimestamp'],
        ],

        'match' => [
            'filtering' => 'valid',
            'ordering' => ['latestBindingTimestamp', SORT_DESC],
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Match Implementation
    |--------------------------------------------------------------------------
    |
    | Underlying system's match implementation.
    |
    */

    //'match' => TODO: bind match implementation,

    /*
    |--------------------------------------------------------------------------
    | Command Schedule
    |--------------------------------------------------------------------------
    |
    | Dispense and check-out command scheduling.
    |
    */

    //'schedule' => TODO: bind schedule,

    /*
    |--------------------------------------------------------------------------
    | Maximum inquiry batch size
    |--------------------------------------------------------------------------
    |
    | Maximum batch size for matching with operators.
    |
    */

    'batch' => env('DISP_BATCH', 50),

    /*
    |--------------------------------------------------------------------------
    | Limit batch entries by inquiry category
    |--------------------------------------------------------------------------
    |
    | Maximum batch entry count for each inquiry category.
    |
    */

    'limit_per_category' => env('CAT_LIMIT', 5),

];
