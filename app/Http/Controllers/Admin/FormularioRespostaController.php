<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Formulario;
use App\Models\Pergunta;
use App\Models\Resposta;
use App\Models\Patient;
use Illuminate\Http\Request;

class FormularioRespostaController extends Controller
{
    public function create(Patient $paciente, Formulario $formulario)
    {
        $formulario->load('perguntas');
        return view('admin.formularios.responder', compact('paciente', 'formulario'));
    }

    public function store(Request $request, Patient $paciente, Formulario $formulario)
    {
        $formulario->load('perguntas');
        $data = $request->validate([
            'respostas' => 'required|array',
        ]);

        foreach ($formulario->perguntas as $pergunta) {
            if (!array_key_exists($pergunta->id, $data['respostas'])) {
                return back()->withErrors(['respostas' => 'Responda todas as perguntas.']);
            }
        }

        foreach ($data['respostas'] as $perguntaId => $resposta) {
            if (is_array($resposta)) {
                $resposta = json_encode($resposta);
            }
            Resposta::create([
                'paciente_id' => $paciente->id,
                'formulario_id' => $formulario->id,
                'pergunta_id' => $perguntaId,
                'resposta' => $resposta,
            ]);
        }

        return redirect()->route('pacientes.edit', $paciente)->with('success', 'Respostas salvas com sucesso.');
    }
}
