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
use App\Http\Controllers\Admin\AgendamentoController;
use App\Http\Controllers\Admin\ClinicContextController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EscalaTrabalhoController;
use App\Http\Controllers\Admin\FinanceiroController;
use App\Http\Controllers\Admin\EstoqueController;

Route::get('/', [DashboardController::class, 'index'])->name('admin.index');

Route::get('agenda', [AgendaController::class, 'index'])->name('agenda.index');
Route::get('agendamentos', [AgendamentoController::class, 'index'])->name('agendamentos.index');
Route::post('agendamentos', [AgendamentoController::class, 'store'])->name('agendamentos.store');
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

Route::resource('escalas', EscalaTrabalhoController::class)->only(['index','store']);

Route::get('financeiro', [FinanceiroController::class, 'index'])->name('financeiro.index');
Route::get('estoque', [EstoqueController::class, 'index'])->name('estoque.index');

