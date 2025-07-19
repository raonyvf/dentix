<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Clinic;
use App\Rules\Cnpj;
use Illuminate\Http\Request;

class ClinicController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->isOrganizationAdmin()) {
            $clinics = Clinic::all();
        } else {
            $clinics = $user->clinics()->get();
        }

        return view('admin.clinics.index', compact('clinics'));
    }

    public function create()
    {
        if (! auth()->user()->isOrganizationAdmin()) {
            abort(403);
        }

        return view('admin.clinics.create');
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        if (! $user->isOrganizationAdmin()) {
            abort(403);
        }

        $data = $request->validate([
            'nome' => 'required',
            'cnpj' => ['required', new Cnpj],
            'responsavel_tecnico' => 'required',
            'cro' => 'required',
            'endereco' => 'required',
            'cidade' => 'required',
            'estado' => 'required',
            'telefone' => 'required',
            'email' => 'required|email',
            'horarios' => 'required|array',
        ]);

        $horarios = $data['horarios'];
        unset($data['horarios']);

        $data['organization_id'] = auth()->user()->organization_id;

        $clinic = Clinic::create($data);

        foreach ($horarios as $dia => $horario) {
            if (($horario['abertura'] ?? false) && ($horario['fechamento'] ?? false)) {
                $clinic->horarios()->create([
                    'clinic_id' => $clinic->id,
                    'dia_semana' => $dia,
                    'hora_inicio' => $horario['abertura'],
                    'hora_fim' => $horario['fechamento'],
                ]);
            }
        }

        $adminProfile = \App\Models\Profile::where('organization_id', $clinic->organization_id)
            ->where('nome', 'Administrador')
            ->first();
        if ($adminProfile) {
            foreach ($adminProfile->users as $admin) {
                $admin->clinics()->syncWithoutDetaching([$clinic->id => ['profile_id' => $adminProfile->id]]);
            }
        }

        return redirect()->route('clinicas.index')->with('success', 'Clínica salva com sucesso.');
    }

    public function edit(Clinic $clinic)
    {
        $user = auth()->user();
        if (! $user->isOrganizationAdmin() && ! $user->clinics->contains($clinic->id)) {
            abort(403);
        }

        $horarios = $clinic->horarios
            ->mapWithKeys(fn($h) => [
                $h->dia_semana => [
                    'abertura' => $h->hora_inicio,
                    'fechamento' => $h->hora_fim,
                ],
            ])->toArray();

        return view('admin.clinics.edit', compact('clinic', 'horarios'));
    }

    public function update(Request $request, Clinic $clinic)
    {
        $user = auth()->user();
        if (! $user->isOrganizationAdmin() && ! $user->clinics->contains($clinic->id)) {
            abort(403);
        }

        $data = $request->validate([
            'nome' => 'required',
            'cnpj' => ['required', new Cnpj],
            'responsavel_tecnico' => 'required',
            'cro' => 'required',
            'endereco' => 'required',
            'cidade' => 'required',
            'estado' => 'required',
            'telefone' => 'required',
            'email' => 'required|email',
            'horarios' => 'required|array',
        ]);

        $horarios = $data['horarios'];
        unset($data['horarios']);

        $clinic->update($data);
        $clinic->horarios()->delete();

        foreach ($horarios as $dia => $horario) {
            if (($horario['abertura'] ?? false) && ($horario['fechamento'] ?? false)) {
                $clinic->horarios()->create([
                    'clinic_id' => $clinic->id,
                    'dia_semana' => $dia,
                    'hora_inicio' => $horario['abertura'],
                    'hora_fim' => $horario['fechamento'],
                ]);
            }
        }

        return redirect()->route('clinicas.index')->with('success', 'Clínica atualizada com sucesso.');
    }
}
