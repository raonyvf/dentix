<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class EnsurePatientProfile
{
    public function handle($request, Closure $next)
    {
        $user = Auth::user();
        if ($user && $user->profiles()->where('nome', 'Paciente')->exists()) {
            return $next($request);
        }

        abort(403);
    }
}
