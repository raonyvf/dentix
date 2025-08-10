<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class PatientPortalController extends Controller
{
    public function index()
    {
        $paciente = Auth::user()->patient;
        return view('portal.index', compact('paciente'));
    }

    public function agendamentos()
    {
        $paciente = Auth::user()->patient;
        return view('portal.agendamentos', compact('paciente'));
    }
}
