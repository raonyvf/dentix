<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

Route::get('/login', [AuthenticatedSessionController::class, 'create'])
    ->name('login')
    ->middleware('throttle:60,1');

Route::post('/login', [AuthenticatedSessionController::class, 'store'])
    ->middleware('throttle:60,1');

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->name('logout');
