<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Formulario;
use Illuminate\Http\Request;

class FormularioController extends Controller
{
    public function index()
    {
        $formularios = Formulario::orderBy('nome')->get();
        return view('admin.formularios.index', compact('formularios'));
    }

    public function create()
    {
        return view('admin.formularios.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nome' => 'required',
            'perguntas' => 'array',
            'perguntas.*.enunciado' => 'required',
            'perguntas.*.tipo' => 'required',
            'perguntas.*.opcoes' => 'nullable',
        ]);

        $formulario = Formulario::create(['nome' => $data['nome']]);

        foreach ($data['perguntas'] ?? [] as $ordem => $pergunta) {
            $formulario->perguntas()->create([
                'enunciado' => $pergunta['enunciado'],
                'tipo' => $pergunta['tipo'],
                'opcoes' => $pergunta['opcoes'] ?? null,
                'ordem' => $ordem,
            ]);
        }

        return redirect()->route('formularios.index')->with('success', 'Formulário salvo com sucesso.');
    }

    public function edit(Formulario $formulario)
    {
        if ($formulario->respostas()->exists()) {
            return redirect()->route('formularios.index')->with('error', 'Formulário já respondido e não pode ser editado.');
        }

        $formulario->load('perguntas');
        return view('admin.formularios.edit', compact('formulario'));
    }

    public function update(Request $request, Formulario $formulario)
    {
        if ($formulario->respostas()->exists()) {
            return redirect()->route('formularios.index')->with('error', 'Formulário já respondido e não pode ser editado.');
        }

        $data = $request->validate([
            'nome' => 'required',
            'perguntas' => 'array',
            'perguntas.*.enunciado' => 'required',
            'perguntas.*.tipo' => 'required',
            'perguntas.*.opcoes' => 'nullable',
        ]);

        $formulario->update(['nome' => $data['nome']]);

        $formulario->perguntas()->delete();
        foreach ($data['perguntas'] ?? [] as $ordem => $pergunta) {
            $formulario->perguntas()->create([
                'enunciado' => $pergunta['enunciado'],
                'tipo' => $pergunta['tipo'],
                'opcoes' => $pergunta['opcoes'] ?? null,
                'ordem' => $ordem,
            ]);
        }

        return redirect()->route('formularios.index')->with('success', 'Formulário atualizado com sucesso.');
    }

    public function destroy(Formulario $formulario)
    {
        if ($formulario->respostas()->exists()) {
            return redirect()->route('formularios.index')->with('error', 'Formulário não pode ser removido pois possui respostas.');
        }
        $formulario->delete();
        return redirect()->route('formularios.index')->with('success', 'Formulário removido com sucesso.');
    }
}
