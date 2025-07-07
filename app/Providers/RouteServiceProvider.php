<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    public const HOME = '/home';

    public function boot()
    {
        parent::boot();

        Route::middleware('web')
            ->group(base_path('routes/web.php'));

        Route::prefix('admin')
            ->middleware(['web', 'auth'])
            ->group(base_path('routes/admin.php'));
    }
}
