<?php

return [

    'repository' => [
        //'inquiry' => TODO: bind repository,
        //'operator' => TODO: bind repository,
        //'match' => TODO: bind repository,
    ],

    //'schedule' => TODO: bind schedule,

    'narrowers' => [
        'inquiry' => '!bound',
        'operator' => ['!busy', 'online', 'ready'],
        'match' => 'valid',
    ],

    'ordering' => [
        'inquiry' => ['weight', SORT_DESC],
        'operator' => ['inquiryCount'],
        'match' => ['latestBindingTimestamp', SORT_DESC],
    ],

];
