<?php

return [

    //'schedule' => TODO: bind schedule,

    'inquiry' => [
        //'repository' => TODO: bind repository,
        'filtering' => '!bound',
        'ordering' => ['weight', SORT_DESC],
    ],

    'operator' => [
        //'repository' => TODO: bind repository,
        'filtering' => ['!busy', 'online', 'ready'],
        'ordering' => ['inquiryCount'],
    ],

    'match' => [
        //'repository' => TODO: bind repository,
        'filtering' => 'valid',
        'ordering' => ['latestBindingTimestamp', SORT_DESC],
    ],

];
