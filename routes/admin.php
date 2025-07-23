<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ClinicController;
use App\Http\Controllers\Admin\CadeiraController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\FormularioController;
use App\Http\Controllers\Admin\PatientController;
use App\Http\Controllers\Admin\ProfessionalController;
use App\Http\Controllers\Admin\AgendaController;
use App\Http\Controllers\Admin\ClinicContextController;
use App\Http\Controllers\Admin\DashboardController;

Route::get('/', [DashboardController::class, 'index'])->name('admin.index');

Route::get('agenda', [AgendaController::class, 'index'])->name('agenda.index');
Route::view('agendamentos', 'agendamentos.index')->name('agendamentos.index');
Route::get('agendamentos/horarios', [AgendaController::class, 'horarios'])->name('agendamentos.horarios');


Route::resource('clinicas', ClinicController::class)
    ->parameters(['clinicas' => 'clinic']);
Route::resource('cadeiras', CadeiraController::class);
Route::resource('perfis', ProfileController::class)->parameters(['perfis' => 'perfil']);
Route::resource('usuarios', UserController::class)->only(['index','edit','update']);
Route::resource('profissionais', ProfessionalController::class)
    ->parameters(['profissionais' => 'profissional']);

Route::resource('formularios', FormularioController::class);
Route::resource('pacientes', PatientController::class)
    ->parameters(['pacientes' => 'paciente']);
Route::get('pacientes/buscar', [PatientController::class, 'search'])->name('pacientes.search');

Route::view('orcamentos/assinar', 'orcamentos.assinar')->name('orcamentos.assinar');

Route::post('selecionar-clinica', [ClinicContextController::class, 'update'])->name('clinicas.selecionar');

