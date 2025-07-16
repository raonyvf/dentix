<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ClinicController;
use App\Http\Controllers\Admin\CadeiraController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\PatientController;
use App\Http\Controllers\Admin\OrganizationController;

Route::get('/', function () {
    return view('dashboard');
})->name('admin.index');

Route::resource('clinicas', ClinicController::class)
    ->parameters(['clinicas' => 'clinic']);
Route::resource('cadeiras', CadeiraController::class);
Route::resource('perfis', ProfileController::class)->parameters(['perfis' => 'perfil']);
Route::resource('usuarios', UserController::class);
Route::resource('pacientes', PatientController::class)->parameters(['pacientes' => 'paciente']);
Route::resource('organizacoes', OrganizationController::class)->parameters(['organizacoes' => 'organization']);
