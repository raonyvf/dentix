<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\OrganizationController;
use App\Http\Controllers\Admin\AdminUserController;

Route::get('/', [OrganizationController::class, 'index'])->name('backend.index');
Route::resource('organizacoes', OrganizationController::class)->parameters(['organizacoes' => 'organization']);
Route::resource('usuarios-admin', AdminUserController::class)
    ->only(['index', 'edit', 'update'])
    ->parameters(['usuarios-admin' => 'usuario']);
