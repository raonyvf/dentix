<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\OrganizationController;

Route::get('/', [OrganizationController::class, 'index'])->name('backend.index');
Route::resource('organizacoes', OrganizationController::class)->parameters(['organizacoes' => 'organization']);
