<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use Illuminate\Http\Request;
use Carbon\Carbon;

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
        $currentClinic = app()->bound('clinic_id') ? app('clinic_id') : null;
        $user = auth()->user();
        if (! $user->isOrganizationAdmin() && ! $user->isSuperAdmin()) {
            if (! $user->clinics->contains($currentClinic)) {
                abort(403);
            }
        }

        return view('patients.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'primeiro_nome' => 'required',
            'nome_meio' => 'nullable',
            'ultimo_nome' => 'required',
            'cpf' => 'nullable|required_unless:menor_idade,1',
            'responsavel_primeiro_nome' => 'nullable|required_if:menor_idade,1',
            'responsavel_nome_meio' => 'nullable',
            'responsavel_ultimo_nome' => 'nullable|required_if:menor_idade,1',
            'data_nascimento' => 'nullable|date',
            'idade' => 'nullable|integer',
            'telefone' => 'nullable',
            'menor_idade' => 'nullable|boolean',
            'responsavel_cpf' => 'required_if:menor_idade,1',
            'ultima_consulta' => 'nullable|date',
            'proxima_consulta' => 'nullable|date',
        ]);

        $data['nome'] = trim($data['primeiro_nome'].' '.($data['nome_meio'] ? $data['nome_meio'].' ' : '').$data['ultimo_nome']);
        unset($data['primeiro_nome'], $data['nome_meio'], $data['ultimo_nome']);

        if ($request->filled('responsavel_primeiro_nome') || $request->filled('responsavel_nome_meio') || $request->filled('responsavel_ultimo_nome')) {
            $data['responsavel'] = trim(($data['responsavel_primeiro_nome'] ?? '').' '.(($data['responsavel_nome_meio'] ?? '') ? $data['responsavel_nome_meio'].' ' : '').($data['responsavel_ultimo_nome'] ?? ''));
        }
        unset($data['responsavel_primeiro_nome'], $data['responsavel_nome_meio'], $data['responsavel_ultimo_nome']);

        if (! empty($data['data_nascimento'])) {
            $data['idade'] = Carbon::parse($data['data_nascimento'])->age;
        }

        $clinicId = app()->bound('clinic_id') ? app('clinic_id') : null;
        $user = auth()->user();
        if (! $user->isOrganizationAdmin() && ! $user->isSuperAdmin()) {
            if (! $user->clinics->contains($clinicId)) {
                abort(403);
            }
        }

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
        $currentClinic = app()->bound('clinic_id') ? app('clinic_id') : null;
        if (! auth()->user()->isOrganizationAdmin() && $paciente->clinic_id != $currentClinic) {
            abort(403);
        }

        return view('patients.edit', compact('paciente'));
    }

    public function update(Request $request, Patient $paciente)
    {
        $data = $request->validate([
            'primeiro_nome' => 'required',
            'nome_meio' => 'nullable',
            'ultimo_nome' => 'required',
            'cpf' => 'nullable|required_unless:menor_idade,1',
            'responsavel_primeiro_nome' => 'nullable|required_if:menor_idade,1',
            'responsavel_nome_meio' => 'nullable',
            'responsavel_ultimo_nome' => 'nullable|required_if:menor_idade,1',
            'data_nascimento' => 'nullable|date',
            'idade' => 'nullable|integer',
            'telefone' => 'nullable',
            'menor_idade' => 'nullable|boolean',
            'responsavel_cpf' => 'required_if:menor_idade,1',
            'ultima_consulta' => 'nullable|date',
            'proxima_consulta' => 'nullable|date',
        ]);

        $data['nome'] = trim($data['primeiro_nome'].' '.($data['nome_meio'] ? $data['nome_meio'].' ' : '').$data['ultimo_nome']);
        unset($data['primeiro_nome'], $data['nome_meio'], $data['ultimo_nome']);

        if ($request->filled('responsavel_primeiro_nome') || $request->filled('responsavel_nome_meio') || $request->filled('responsavel_ultimo_nome')) {
            $data['responsavel'] = trim(($data['responsavel_primeiro_nome'] ?? '').' '.(($data['responsavel_nome_meio'] ?? '') ? $data['responsavel_nome_meio'].' ' : '').($data['responsavel_ultimo_nome'] ?? ''));
        }
        unset($data['responsavel_primeiro_nome'], $data['responsavel_nome_meio'], $data['responsavel_ultimo_nome']);
        if (! empty($data["data_nascimento"])) {
            $data["idade"] = Carbon::parse($data["data_nascimento"])->age;
        }

        $currentClinic = app()->bound('clinic_id') ? app('clinic_id') : null;
        if (! auth()->user()->isOrganizationAdmin() && $paciente->clinic_id != $currentClinic) {
            abort(403);
        }

        $paciente->update($data);

        return redirect()->route('pacientes.index')->with('success', 'Paciente atualizado com sucesso.');
    }

    public function destroy(Patient $paciente)
    {
        $currentClinic = app()->bound('clinic_id') ? app('clinic_id') : null;
        if (! auth()->user()->isOrganizationAdmin() && $paciente->clinic_id != $currentClinic) {
            abort(403);
        }

        $paciente->delete();

        return redirect()->route('pacientes.index')->with('success', 'Paciente removido com sucesso.');
    }
}
