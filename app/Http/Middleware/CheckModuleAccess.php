<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckModuleAccess
{
    public function handle($request, Closure $next, $module)
    {
        $user = Auth::user();
        if ($user && $user->hasAnyModulePermission($module)) {
            return $next($request);
        }

        abort(403);
    }
}
