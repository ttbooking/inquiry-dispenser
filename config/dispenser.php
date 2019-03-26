<?php

return [

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
        'operator' => 'free',
        'match' => 'valid',
    ],

];
