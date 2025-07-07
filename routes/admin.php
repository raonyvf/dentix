<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ClinicController;
use App\Http\Controllers\Admin\UnidadeController;
use App\Http\Controllers\Admin\CadeiraController;

Route::get('/', function () {
    return view('admin.index');
})->name('admin.index');

Route::resource('clinicas', ClinicController::class);
Route::resource('unidades', UnidadeController::class);
Route::resource('cadeiras', CadeiraController::class);
