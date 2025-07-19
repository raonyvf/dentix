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

Route::view('agenda', 'agenda')->name('agenda.index');

Route::resource('clinicas', ClinicController::class)
    ->parameters(['clinicas' => 'clinic']);
Route::resource('cadeiras', CadeiraController::class);
Route::resource('perfis', ProfileController::class)->parameters(['perfis' => 'perfil']);
Route::resource('usuarios', UserController::class);
Route::resource('pacientes', PatientController::class)->parameters(['pacientes' => 'paciente']);
use App\Http\Controllers\Admin\FormularioController;
use App\Http\Controllers\Admin\FormularioRespostaController;

Route::resource('formularios', FormularioController::class);
Route::get('pacientes/{paciente}/formularios/{formulario}', [FormularioRespostaController::class, 'create'])->name('pacientes.formularios.create');
Route::post('pacientes/{paciente}/formularios/{formulario}', [FormularioRespostaController::class, 'store'])->name('pacientes.formularios.store');

