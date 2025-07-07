<?php

return [
    'name' => env('APP_NAME', 'Dentix'),

    'env' => env('APP_ENV', 'production'),

    'debug' => (bool) env('APP_DEBUG', false),

    'url' => env('APP_URL', 'http://localhost'),

    'timezone' => 'UTC',

    'locale' => 'en',

    'fallback_locale' => 'en',

    'providers' => [
        Illuminate\Foundation\Providers\FoundationServiceProvider::class,
        Illuminate\Auth\AuthServiceProvider::class,
        Illuminate\Database\DatabaseServiceProvider::class,
        Illuminate\View\ViewServiceProvider::class,
        Laravel\Breeze\BreezeServiceProvider::class,
        App\Providers\RouteServiceProvider::class,
    ],

    'aliases' => [
        'App' => Illuminate\Support\Facades\App::class,
        'DB' => Illuminate\Support\Facades\DB::class,
        'Route' => Illuminate\Support\Facades\Route::class,
    ],
];
