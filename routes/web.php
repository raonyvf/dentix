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
