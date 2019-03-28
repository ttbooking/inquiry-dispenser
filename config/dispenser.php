<?php

return [

    'repositories' => [

        \TTBooking\InquiryDispenser\Contracts\Repositories\InquiryRepository::class =>
            \App\InquiryDispenser\Repositories\InquiryRepository::class,

        \TTBooking\InquiryDispenser\Contracts\Repositories\OperatorRepository::class =>
            \App\InquiryDispenser\Repositories\OperatorRepository::class,

        \TTBooking\InquiryDispenser\Contracts\Repositories\MatchRepository::class =>
            \App\InquiryDispenser\Repositories\MatchRepository::class,

    ],

    'schedule' => [

        'checkout' => function (\Illuminate\Console\Scheduling\Event $checkout) {
            $checkout->everyMinute();
        },

        'dispense' => function (\Illuminate\Console\Scheduling\Event $dispense) {
            $dispense->everyMinute();
        },

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
