<?php

return [

    'schedule' => function (\Illuminate\Console\Scheduling\Event $event) {
        $event->everyMinute();
    },

    'narrowers' => [
        'inquiry' => '!bound',
        'operator' => 'free',
        'match' => 'valid',
    ],

    'connections' => [

        'database' => [
            'connection' => 'default',
            'table' => 'factor_track',
        ],

    ],

];
