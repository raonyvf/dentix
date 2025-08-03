<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ClinicContextController extends Controller
{
    public function update(Request $request)
    {
        $clinicId = $request->input('clinic_id');
        $user = $request->user();

        if ($clinicId && $user->clinics()->where('clinicas.id', $clinicId)->exists()) {
            session(['clinic_id' => $clinicId]);
            app()->instance('clinic_id', $clinicId);
        }

        return back();
    }
}
