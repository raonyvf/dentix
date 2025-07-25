<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next, string ...$guards)
    {
        if (Auth::guard($guards)->check()) {
            return redirect(RouteServiceProvider::HOME);
        }

        return $next($request);
    }
}
