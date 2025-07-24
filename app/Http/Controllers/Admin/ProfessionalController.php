<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Profissional;
use App\Models\Person;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class ProfessionalController extends Controller
{
    public function index()
    {
        $profissionais = Profissional::with('user.person')->get();
        return view('profissionais.index', compact('profissionais'));
    }

    public function create()
    {
        return view('profissionais.create');
    }

    public function show(Profissional $profissional)
    {
        return view('profissionais.show', compact('profissional'));
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);

        $personData = $this->extractPersonData($data);
        if ($request->hasFile('foto')) {
            $personData['photo_path'] = $request->file('foto')->store('profissionais', 'public');
        }
        $person = Person::create(array_merge([
            'organization_id' => auth()->user()->organization_id
        ], $personData));

        $user = null;
        if ($person->email) {
            $user = User::firstWhere('email', $person->email);
            if (!$user) {
                $user = User::create([
                    'email' => $person->email,
                    'organization_id' => auth()->user()->organization_id,
                    'password' => Hash::make(Str::random(8)),
                    'must_change_password' => true,
                    'person_id' => $person->id,
                ]);
            } else {
                $user->update(['person_id' => $person->id]);
            }
        }

        Profissional::create([
            'organization_id' => auth()->user()->organization_id,
            'person_id' => $person->id,
            'user_id' => $user?->id,
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
            'first_name' => 'required',
            'middle_name' => 'nullable',
            'last_name' => 'required',
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
            'first_name' => $data['first_name'],
            'middle_name' => $data['middle_name'] ?? null,
            'last_name' => $data['last_name'],
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
