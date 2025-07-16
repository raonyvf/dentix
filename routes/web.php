<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('admin.index');
    }

    return redirect()->route('login');
});

require __DIR__.'/auth.php';

Route::middleware(['web', 'auth', 'forcepasswordchange'])->group(function () {
    Route::get('/password/change', [\App\Http\Controllers\Auth\PasswordController::class, 'edit'])->name('password.change');
    Route::post('/password/change', [\App\Http\Controllers\Auth\PasswordController::class, 'update'])->name('password.update');
});
