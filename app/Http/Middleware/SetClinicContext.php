<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class SetClinicContext
{
    public function handle($request, Closure $next)
    {
        if ($user = Auth::user()) {
            app()->instance('clinic_id', $user->clinic_id);
        }
        return $next($request);
    }
}
