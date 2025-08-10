<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class EnsurePatientPerfil
{
    public function handle($request, Closure $next)
    {
        $user = Auth::user();
        if ($user && $user->perfis()->where('nome', 'Paciente')->exists()) {
            return $next($request);
        }

        abort(403);
    }
}
