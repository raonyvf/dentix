<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class SetClinicContext
{
    public function handle($request, Closure $next)
    {
        if ($user = Auth::user()) {
            $clinicId = session('clinic_id');
            if (! $clinicId) {
                $clinicId = optional($user->clinics()->first())->id;
                if ($clinicId) {
                    session(['clinic_id' => $clinicId]);
                }
            }
            app()->instance('clinic_id', $clinicId);
        }
        return $next($request);
    }
}
