<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class SetClinicContext
{
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            app()->instance('clinicId', Auth::user()->clinic_id);
        }

        return $next($request);
    }
}
