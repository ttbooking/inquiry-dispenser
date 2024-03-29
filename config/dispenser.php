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

    'batch_size' => env('DISP_BATCH_SIZE', 50),

    /*
    |--------------------------------------------------------------------------
    | Group entries by inquiry property
    |--------------------------------------------------------------------------
    |
    | Maximum entry count for each inquiry property value.
    |
    */

    'group_by' => env('DISP_GROUP_BY', false),

    'group_limit' => env('DISP_GROUP_LIMIT', 5),

];
