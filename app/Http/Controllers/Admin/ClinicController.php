<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Clinic;
use App\Rules\Cnpj;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Enums\DiaSemana;

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

        $data['organizacao_id'] = auth()->user()->organizacao_id;
        $data['timezone'] = auth()->user()->organization->timezone;

        $clinic = Clinic::create($data);

        foreach ($horarios as $dia => $horario) {
            $diaNumero = DiaSemana::fromName($dia)?->value;
            if ($diaNumero && ($horario['abertura'] ?? false) && ($horario['fechamento'] ?? false)) {
                $clinic->horarios()->create([
                    'clinica_id' => $clinic->id,
                    'organizacao_id' => $clinic->organizacao_id,
                    'dia_semana' => $diaNumero,
                    'hora_inicio' => $horario['abertura'],
                    'hora_fim' => $horario['fechamento'],
                ]);
            }
        }

        $adminPerfil = \App\Models\Perfil::where('organizacao_id', $clinic->organizacao_id)
            ->where('nome', 'Administrador')
            ->first();
        if ($adminPerfil) {
            foreach ($adminPerfil->usuarios as $admin) {
                $admin->clinics()->syncWithoutDetaching([$clinic->id => ['perfil_id' => $adminPerfil->id]]);
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
                $diaNome = DiaSemana::from($h->dia_semana)->toName();
                return [
                    $diaNome => [
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
            $diaNumero = DiaSemana::fromName($dia)?->value;
            if ($diaNumero && ($horario['abertura'] ?? false) && ($horario['fechamento'] ?? false)) {
                $clinic->horarios()->create([
                    'clinica_id' => $clinic->id,
                    'organizacao_id' => $clinic->organizacao_id,
                    'dia_semana' => $diaNumero,
                    'hora_inicio' => $horario['abertura'],
                    'hora_fim' => $horario['fechamento'],
                ]);
            }
        }

        return redirect()->route('clinicas.index')->with('success', 'Clínica atualizada com sucesso.');
    }
}
