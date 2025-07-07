<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

// Placeholder for auth routes (Breeze would generate real routes)
Route::get('/login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');
