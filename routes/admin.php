<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ClinicController;
use App\Http\Controllers\Admin\CadeiraController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\PatientController;

Route::get('/', function () {
    return view('dashboard');
})->name('admin.index');

Route::resource('clinicas', ClinicController::class)->middleware('module:Clínicas')
    ->parameters(['clinicas' => 'clinic']);
Route::resource('cadeiras', CadeiraController::class)->middleware('module:Cadeiras');
Route::resource('perfis', ProfileController::class)->middleware('module:Usuários')->parameters(['perfis' => 'perfil']);
Route::resource('usuarios', UserController::class)->middleware('module:Usuários');
Route::resource('pacientes', PatientController::class)->middleware('module:Pacientes')->parameters(['pacientes' => 'paciente']);
