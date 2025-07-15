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
        $clinics = Clinic::all();
        return view('admin.clinics.index', compact('clinics'));
    }

    public function create()
    {
        return view('admin.clinics.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nome' => 'required',
            'cnpj' => ['required', new Cnpj],
            'responsavel' => 'required',
            'endereco' => 'required',
            'cidade' => 'required',
            'estado' => 'required',
            'contato' => 'required',
            'horarios' => 'required|array',
        ]);

        $horarios = $data['horarios'];
        unset($data['horarios']);

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

        return redirect()->route('clinicas.index')->with('success', 'Clínica salva com sucesso.');
    }

    public function edit(Clinic $clinic)
    {
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
        $data = $request->validate([
            'nome' => 'required',
            'cnpj' => ['required', new Cnpj],
            'responsavel' => 'required',
            'endereco' => 'required',
            'cidade' => 'required',
            'estado' => 'required',
            'contato' => 'required',
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
