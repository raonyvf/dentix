<?php

// Laravel's Pusher broadcaster requires the "pusher/pusher-php-server" package.
// In environments where that package isn't installed (such as CI or local
// development without the dependency), attempting to use the "pusher" driver
// results in a fatal error: "Class 'Pusher\\Pusher' not found". To make the
// configuration more robust, we fallback to the "log" driver whenever the
// Pusher class cannot be resolved. This keeps the application functional while
// still allowing Pusher to be used when the dependency is present.

$defaultDriver = env('BROADCAST_DRIVER', 'null');

if ($defaultDriver === 'pusher' && ! class_exists(\Pusher\Pusher::class)) {
    $defaultDriver = 'log';
}

return [
    'default' => $defaultDriver,

    'connections' => [
        'pusher' => [
            'driver' => 'pusher',
            'key' => env('PUSHER_APP_KEY'),
            'secret' => env('PUSHER_APP_SECRET'),
            'app_id' => env('PUSHER_APP_ID'),
            'options' => [
                'cluster' => env('PUSHER_APP_CLUSTER'),
                'useTLS' => env('PUSHER_USE_TLS', false),
                'host' => env('PUSHER_HOST', '127.0.0.1'),
                'port' => env('PUSHER_PORT', 6001),
                'scheme' => env('PUSHER_SCHEME', 'http'),
            ],
        ],

        'redis' => [
            'driver' => 'redis',
            'connection' => 'default',
        ],

        'log' => [
            'driver' => 'log',
        ],

        'null' => [
            'driver' => 'null',
        ],
    ],
];
