<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    public function index(Request $request)
    {
        $query = Patient::query();

        if ($request->filled('q')) {
            $query->where('nome', 'like', '%' . $request->q . '%');
        }

        $patients = $query->orderBy('nome')->get();

        $total = Patient::count();
        $lastThirty = Patient::where('created_at', '>=', now()->subDays(30))->count();
        $variation = $total > 0 ? ($lastThirty / $total) * 100 : 0;

        $scheduledAppointments = Patient::whereNotNull('proxima_consulta')
            ->whereBetween('proxima_consulta', [now(), now()->addDays(30)])
            ->count();

        $pendingReturns = Patient::whereNull('proxima_consulta')
            ->whereNotNull('ultima_consulta')
            ->count();

        return view('patients.index', compact(
            'patients',
            'total',
            'variation',
            'scheduledAppointments',
            'pendingReturns'
        ));
    }

    public function create()
    {
        return view('patients.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nome' => 'required',
            'cpf' => 'required',
            'responsavel' => 'nullable',
            'idade' => 'nullable|integer',
            'telefone' => 'nullable',
            'menor_idade' => 'nullable|boolean',
            'responsavel_cpf' => 'required_if:menor_idade,1',
            'ultima_consulta' => 'nullable|date',
            'proxima_consulta' => 'nullable|date',
        ]);

        $clinicId = app()->bound('clinic_id') ? app('clinic_id') : null;

        Patient::create(array_merge(
            $data,
            [
                'clinic_id' => $clinicId,
                'organization_id' => auth()->user()->organization_id,
            ]
        ));

        return redirect()->route('pacientes.index')->with('success', 'Paciente salvo com sucesso.');
    }

    public function edit(Patient $paciente)
    {
        return view('patients.edit', compact('paciente'));
    }

    public function update(Request $request, Patient $paciente)
    {
        $data = $request->validate([
            'nome' => 'required',
            'cpf' => 'required',
            'responsavel' => 'nullable',
            'idade' => 'nullable|integer',
            'telefone' => 'nullable',
            'menor_idade' => 'nullable|boolean',
            'responsavel_cpf' => 'required_if:menor_idade,1',
            'ultima_consulta' => 'nullable|date',
            'proxima_consulta' => 'nullable|date',
        ]);

        $paciente->update($data);

        return redirect()->route('pacientes.index')->with('success', 'Paciente atualizado com sucesso.');
    }

    public function destroy(Patient $paciente)
    {
        $paciente->delete();

        return redirect()->route('pacientes.index')->with('success', 'Paciente removido com sucesso.');
    }
}
