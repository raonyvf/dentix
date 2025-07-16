<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class EnsureSuperAdmin
{
    public function handle($request, Closure $next)
    {
        if (Auth::check() && optional(Auth::user()->profile)->nome === 'Super Administrador') {
            return $next($request);
        }

        abort(403);
    }
}
