<?php

use TTBooking\InquiryDispenser\Contracts;

return [

    'repositories' => [

        //Contracts\Repositories\InquiryRepository::class => TODO: bind repository,

        //Contracts\Repositories\OperatorRepository::class => TODO: bind repository,

        //Contracts\Repositories\MatchRepository::class => TODO: bind repository,

        //Contracts\Schedule::class => TODO: bind schedule,

    ],

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
