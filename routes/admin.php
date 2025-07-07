<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ClinicController;
use App\Http\Controllers\Admin\UnidadeController;
use App\Http\Controllers\Admin\CadeiraController;

Route::middleware(['auth', 'setcliniccontext'])
    ->prefix('admin')
    ->group(function () {
        Route::view('/', 'admin.index')->name('admin.index');
        Route::resource('clinics', ClinicController::class);
        Route::resource('unidades', UnidadeController::class);
        Route::resource('cadeiras', CadeiraController::class);
    });
