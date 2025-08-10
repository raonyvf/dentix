<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    if (Auth::check()) {
        $user = Auth::user();

        if ($user->isSuperAdmin()) {
            return redirect()->route('backend.index');
        }

        if ($user->perfis()->where('nome', 'Paciente')->exists()) {
            return redirect()->route('portal.index');
        }

        return redirect()->route('admin.index');
    }

    return redirect()->route('login');
});

require __DIR__.'/auth.php';

Route::middleware(['web', 'auth', 'forcepasswordchange'])->group(function () {
    Route::get('/password/change', [\App\Http\Controllers\Auth\PasswordController::class, 'edit'])->name('password.change');
    Route::post('/password/change', [\App\Http\Controllers\Auth\PasswordController::class, 'update'])->name('password.update');
});

Route::middleware(['web', 'auth', 'forcepasswordchange', 'paciente'])
    ->prefix('portal')
    ->name('portal.')
    ->group(function () {
        Route::get('/', [\App\Http\Controllers\PatientPortalController::class, 'index'])->name('index');
        Route::get('/agendamentos', [\App\Http\Controllers\PatientPortalController::class, 'agendamentos'])->name('agendamentos');
    });
