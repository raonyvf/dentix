<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Clinic;
use App\Rules\Cnpj;
use Illuminate\Http\Request;
use Carbon\Carbon;

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
            'responsavel_first_name' => 'required',
            'responsavel_middle_name' => 'nullable',
            'responsavel_last_name' => 'required',
            'cro' => 'required',
            'cro_uf' => 'required',
            'cep' => 'required',
            'logradouro' => 'required',
            'numero' => 'nullable',
            'complemento' => 'nullable',
            'bairro' => 'nullable',
            'cidade' => 'required',
            'estado' => 'required',
            'telefone' => 'required',
            'email' => 'required|email',
            'horarios' => 'required|array',
            'horarios.*.abertura' => 'nullable|date_format:H:i',
            'horarios.*.fechamento' => 'nullable|date_format:H:i',
        ]);

        $horarios = $data['horarios'];

        $errors = [];
        foreach ($horarios as $dia => $horario) {
            $abertura = $horario['abertura'] ?? null;
            $fechamento = $horario['fechamento'] ?? null;
            if ($abertura && $fechamento) {
                $start = Carbon::createFromFormat('H:i', $abertura);
                $end = Carbon::createFromFormat('H:i', $fechamento);
                if ($start->gte($end)) {
                    $errors["horarios.$dia.fechamento"] = 'Fechamento deve ser depois da abertura';
                }
            }
        }
        if ($errors) {
            return back()->withErrors($errors)->withInput();
        }

        unset($data['horarios']);

        $data['organization_id'] = auth()->user()->organization_id;
        $data['timezone'] = auth()->user()->organization->timezone;

        $clinic = Clinic::create($data);

        foreach ($horarios as $dia => $horario) {
            if (($horario['abertura'] ?? false) && ($horario['fechamento'] ?? false)) {
                $clinic->horarios()->create([
                    'clinic_id' => $clinic->id,
                    'organization_id' => $clinic->organization_id,
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
            ->mapWithKeys(function ($h) {
                return [
                    $h->dia_semana => [
                        'abertura' => Carbon::createFromTimeString($h->hora_inicio)->format('H:i'),
                        'fechamento' => Carbon::createFromTimeString($h->hora_fim)->format('H:i'),
                    ],
                ];
            })->toArray();

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
            'responsavel_first_name' => 'required',
            'responsavel_middle_name' => 'nullable',
            'responsavel_last_name' => 'required',
            'cro' => 'required',
            'cro_uf' => 'required',
            'cep' => 'required',
            'logradouro' => 'required',
            'numero' => 'nullable',
            'complemento' => 'nullable',
            'bairro' => 'nullable',
            'cidade' => 'required',
            'estado' => 'required',
            'telefone' => 'required',
            'email' => 'required|email',
            'horarios' => 'required|array',
            'horarios.*.abertura' => 'nullable|date_format:H:i',
            'horarios.*.fechamento' => 'nullable|date_format:H:i',
        ]);

        $horarios = $data['horarios'];

        $errors = [];
        foreach ($horarios as $dia => $horario) {
            $abertura = $horario['abertura'] ?? null;
            $fechamento = $horario['fechamento'] ?? null;
            if ($abertura && $fechamento) {
                $start = Carbon::createFromFormat('H:i', $abertura);
                $end = Carbon::createFromFormat('H:i', $fechamento);
                if ($start->gte($end)) {
                    $errors["horarios.$dia.fechamento"] = 'Fechamento deve ser depois da abertura';
                }
            }
        }
        if ($errors) {
            return back()->withErrors($errors)->withInput();
        }

        unset($data['horarios']);

        $clinic->update($data);
        $clinic->horarios()->delete();

        foreach ($horarios as $dia => $horario) {
            if (($horario['abertura'] ?? false) && ($horario['fechamento'] ?? false)) {
                $clinic->horarios()->create([
                    'clinic_id' => $clinic->id,
                    'organization_id' => $clinic->organization_id,
                    'dia_semana' => $dia,
                    'hora_inicio' => $horario['abertura'],
                    'hora_fim' => $horario['fechamento'],
                ]);
            }
        }

        return redirect()->route('clinicas.index')->with('success', 'Clínica atualizada com sucesso.');
    }
}
