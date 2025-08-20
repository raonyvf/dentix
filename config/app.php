<?php

return [
    'name' => env('APP_NAME', 'Dentix'),

    'env' => env('APP_ENV', 'production'),

    'debug' => (bool) env('APP_DEBUG', false),

    'url' => env('APP_URL', 'http://localhost'),

    'key' => env('APP_KEY'),

    'cipher' => 'AES-256-CBC',

    'timezone' => 'UTC',

    'locale' => 'pt_BR',

    'fallback_locale' => 'en',

    'providers' => [
        Illuminate\Foundation\Providers\FoundationServiceProvider::class,
        Illuminate\Auth\AuthServiceProvider::class,
        Illuminate\Database\DatabaseServiceProvider::class,
        Illuminate\Filesystem\FilesystemServiceProvider::class,
        Illuminate\Cache\CacheServiceProvider::class,
        Illuminate\Foundation\Providers\ConsoleSupportServiceProvider::class,
        Illuminate\View\ViewServiceProvider::class,
        Illuminate\Translation\TranslationServiceProvider::class,
        Illuminate\Cookie\CookieServiceProvider::class,
        Illuminate\Session\SessionServiceProvider::class,
        Illuminate\Encryption\EncryptionServiceProvider::class,
        Illuminate\Hashing\HashServiceProvider::class,
        Illuminate\Bus\BusServiceProvider::class,
        Illuminate\Pipeline\PipelineServiceProvider::class,
        Illuminate\Queue\QueueServiceProvider::class,
        Illuminate\Notifications\NotificationServiceProvider::class,
        Illuminate\Broadcasting\BroadcastServiceProvider::class,
        Illuminate\Validation\ValidationServiceProvider::class,
        Illuminate\Mail\MailServiceProvider::class,
        Illuminate\Redis\RedisServiceProvider::class,
        Laravel\Breeze\BreezeServiceProvider::class,
        App\Providers\RouteServiceProvider::class,
        App\Providers\BroadcastServiceProvider::class,
    ],

    'aliases' => [
        'App' => Illuminate\Support\Facades\App::class,
        'DB' => Illuminate\Support\Facades\DB::class,
        'Route' => Illuminate\Support\Facades\Route::class,
        'Hash' => Illuminate\Support\Facades\Hash::class,
        'Cache' => Illuminate\Support\Facades\Cache::class,
        'Auth' => Illuminate\Support\Facades\Auth::class,
        'Validator' => Illuminate\Support\Facades\Validator::class,
        'Mail' => Illuminate\Support\Facades\Mail::class,
        'Redis' => Illuminate\Support\Facades\Redis::class,
    ],
];
