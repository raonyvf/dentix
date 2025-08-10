<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class EnsureSuperAdmin
{
    public function handle($request, Closure $next)
    {
        if (Auth::check() && Auth::user()->isSuperAdmin()) {
            return $next($request);
        }

        abort(403);
    }
}
