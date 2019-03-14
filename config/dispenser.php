<?php

return [

    'schedule' => function (\Illuminate\Console\Scheduling\Event $event) {
        $event->everyMinute();
    },

    'connections' => [

        'database' => [
            'connection' => 'default',
            'table' => 'factor_track',
        ],

    ],

];
