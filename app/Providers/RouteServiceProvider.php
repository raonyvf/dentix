<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    public const HOME = '/admin';

    public function boot()
    {
        parent::boot();

        Route::middleware('web')
            ->group(base_path('routes/web.php'));

        Route::prefix('admin')
            ->middleware(['web', 'auth', 'forcepasswordchange', 'deny_superadmin'])
            ->group(base_path('routes/admin.php'));

        Route::prefix('backend')
            ->middleware(['web', 'auth', 'forcepasswordchange', 'superadmin'])
            ->group(base_path('routes/backend.php'));
    }
}
