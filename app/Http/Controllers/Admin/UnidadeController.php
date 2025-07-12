<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Unidade;
use App\Models\Clinic;
use App\Models\Horario;
use Illuminate\Http\Request;

class UnidadeController extends Controller
{
    public function index()
    {
        $unidades = Unidade::all();
        return view('admin.unidades.index', compact('unidades'));
    }

    public function create()
    {
        $clinics = Clinic::all();
        return view('admin.unidades.create', compact('clinics'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'clinic_id' => 'required|exists:clinics,id',
            'nome' => 'required',
            'endereco' => 'required',
            'cidade' => 'required',
            'estado' => 'required',
            'contato' => 'required',
            'horarios' => 'required|array',
        ]);

        $horarios = $data['horarios'];
        unset($data['horarios']);

        $unidade = Unidade::create($data);

        foreach ($horarios as $dia => $horario) {
            if (($horario['abertura'] ?? false) && ($horario['fechamento'] ?? false)) {
                $unidade->horarios()->create([
                    'clinic_id' => $unidade->clinic_id,
                    'dia_semana' => $dia,
                    'hora_inicio' => $horario['abertura'],
                    'hora_fim' => $horario['fechamento'],
                ]);
            }
        }

        return redirect()->route('unidades.index');
    }

    public function edit(Unidade $unidade)
    {
        $clinics = Clinic::all();
        return view('admin.unidades.edit', compact('unidade', 'clinics'));
    }

    public function update(Request $request, Unidade $unidade)
    {
        $data = $request->validate([
            'clinic_id' => 'required|exists:clinics,id',
            'nome' => 'required',
            'endereco' => 'required',
            'cidade' => 'required',
            'estado' => 'required',
            'contato' => 'required',
            'horarios' => 'required|array',
        ]);

        $horarios = $data['horarios'];
        unset($data['horarios']);

        $unidade->update($data);
        $unidade->horarios()->delete();

        foreach ($horarios as $dia => $horario) {
            if (($horario['abertura'] ?? false) && ($horario['fechamento'] ?? false)) {
                $unidade->horarios()->create([
                    'clinic_id' => $unidade->clinic_id,
                    'dia_semana' => $dia,
                    'hora_inicio' => $horario['abertura'],
                    'hora_fim' => $horario['fechamento'],
                ]);
            }
        }

        return redirect()->route('unidades.index');
    }
}
