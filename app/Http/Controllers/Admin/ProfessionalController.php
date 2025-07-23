<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Profissional;
use Illuminate\Http\Request;

class ProfessionalController extends Controller
{
    public function index()
    {
        $profissionais = Profissional::all();
        return view('profissionais.index', compact('profissionais'));
    }

    public function create()
    {
        return view('profissionais.create');
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);

        if ($request->hasFile('foto')) {
            $data['foto_path'] = $request->file('foto')->store('profissionais', 'public');
        }

        Profissional::create($data);

        return redirect()->route('profissionais.index')->with('success', 'Profissional salvo com sucesso.');
    }

    public function edit(Profissional $profissional)
    {
        return view('profissionais.edit', compact('profissional'));
    }

    public function update(Request $request, Profissional $profissional)
    {
        $data = $this->validateData($request);

        if ($request->hasFile('foto')) {
            $data['foto_path'] = $request->file('foto')->store('profissionais', 'public');
        }

        $profissional->update($data);

        return redirect()->route('profissionais.index')->with('success', 'Profissional atualizado com sucesso.');
    }

    public function destroy(Profissional $profissional)
    {
        $profissional->delete();
        return redirect()->route('profissionais.index')->with('success', 'Profissional removido com sucesso.');
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'nome' => 'required',
            'nome_meio' => 'nullable',
            'ultimo_nome' => 'required',
            'data_nascimento' => 'nullable|date',
            'sexo' => 'nullable',
            'naturalidade' => 'nullable',
            'nacionalidade' => 'nullable',
            'cpf' => 'nullable',
            'rg' => 'nullable',
            'email' => 'nullable|email',
            'telefone' => 'nullable',
            'cep' => 'nullable',
            'logradouro' => 'nullable',
            'numero' => 'nullable',
            'complemento' => 'nullable',
            'bairro' => 'nullable',
            'cidade' => 'nullable',
            'estado' => 'nullable',
            'foto' => 'nullable|image',
        ]);
    }
}
