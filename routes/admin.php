<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ClinicController;
use App\Http\Controllers\Admin\CadeiraController;
use App\Http\Controllers\Admin\PerfilController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\FormularioController;
use App\Http\Controllers\Admin\PatientController;
use App\Http\Controllers\Admin\ProfessionalController;
use App\Http\Controllers\Admin\AgendaController;
use App\Http\Controllers\Admin\AgendamentoController;
use App\Http\Controllers\Admin\ClinicContextController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EscalaTrabalhoController;
use App\Http\Controllers\Admin\EstoqueController;

Route::get('/', [DashboardController::class, 'index'])->name('admin.index');

Route::get('agenda', [AgendaController::class, 'index'])->name('agenda.index');
Route::view('agenda/fluxo', 'admin.agenda.fluxo')->name('agenda.fluxo');
Route::get('agenda/consultas', [AgendamentoController::class, 'consultasDia'])->name('agenda.consultas');
Route::get('agendamentos', [AgendamentoController::class, 'index'])->name('agendamentos.index');
Route::post('agendamentos', [AgendamentoController::class, 'store'])->name('agendamentos.store');
Route::put('agendamentos/{agendamento}', [AgendamentoController::class, 'update'])->name('agendamentos.update');
Route::get('agendamentos/horarios', [AgendaController::class, 'horarios'])->name('agendamentos.horarios');
Route::get('agendamentos/profissionais', [AgendamentoController::class, 'professionals'])->name('agendamentos.professionals');
Route::get('agendamentos/waitlist', [AgendamentoController::class, 'waitlist'])->name('agendamentos.waitlist');
Route::get('waitlist/month', [AgendamentoController::class, 'waitlistMonthly'])->name('waitlist.month');


Route::resource('clinicas', ClinicController::class)
    ->parameters(['clinicas' => 'clinic']);
Route::resource('cadeiras', CadeiraController::class);
Route::resource('perfis', PerfilController::class)->parameters(['perfis' => 'perfil']);
Route::resource('usuarios', UserController::class)->only(['index','edit','update']);
Route::resource('profissionais', ProfessionalController::class)
    ->parameters(['profissionais' => 'profissional']);

Route::resource('formularios', FormularioController::class);
Route::get('pacientes/search', [PatientController::class, 'search'])->name('pacientes.search');
Route::resource('pacientes', PatientController::class)
    ->parameters(['pacientes' => 'paciente']);

Route::post('selecionar-clinica', [ClinicContextController::class, 'update'])->name('clinicas.selecionar');

Route::resource('escalas', EscalaTrabalhoController::class)->only(['index','store','update','destroy']);

Route::get('estoque', [EstoqueController::class, 'index'])->name('estoque.index');

Route::view('financeiro', 'financeiro.index', ['clinics' => ['Clínica Centro', 'Clínica Norte']])->name('financeiro.index');

