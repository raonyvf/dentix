<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Profissional;
use App\Models\Person;
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

        $personData = $this->extractPersonData($data);
        if ($request->hasFile('foto')) {
            $personData['photo_path'] = $request->file('foto')->store('profissionais', 'public');
        }
        $person = Person::create(array_merge(['organization_id' => auth()->user()->organization_id], $personData));

        Profissional::create([
            'organization_id' => auth()->user()->organization_id,
            'person_id' => $person->id,
        ]);

        return redirect()->route('profissionais.index')->with('success', 'Profissional salvo com sucesso.');
    }

    public function edit(Profissional $profissional)
    {
        return view('profissionais.edit', compact('profissional'));
    }

    public function update(Request $request, Profissional $profissional)
    {
        $data = $this->validateData($request);

        $personData = $this->extractPersonData($data);
        if ($request->hasFile('foto')) {
            $personData['photo_path'] = $request->file('foto')->store('profissionais', 'public');
        }

        $profissional->person->update($personData);

        $profissional->save();
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

    private function extractPersonData(array $data): array
    {
        return [
            'first_name' => $data['nome'],
            'middle_name' => $data['nome_meio'] ?? null,
            'last_name' => $data['ultimo_nome'],
            'data_nascimento' => $data['data_nascimento'] ?? null,
            'sexo' => $data['sexo'] ?? null,
            'naturalidade' => $data['naturalidade'] ?? null,
            'nacionalidade' => $data['nacionalidade'] ?? null,
            'cpf' => $data['cpf'] ?? null,
            'rg' => $data['rg'] ?? null,
            'email' => $data['email'] ?? null,
            'phone' => $data['telefone'] ?? null,
            'cep' => $data['cep'] ?? null,
            'logradouro' => $data['logradouro'] ?? null,
            'numero' => $data['numero'] ?? null,
            'complemento' => $data['complemento'] ?? null,
            'bairro' => $data['bairro'] ?? null,
            'cidade' => $data['cidade'] ?? null,
            'estado' => $data['estado'] ?? null,
        ];
    }
}
