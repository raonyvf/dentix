<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\User;
use App\Models\Profile;
use App\Models\Person;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class PatientController extends Controller
{
    public function index()
    {
        $pacientes = Patient::all();
        return view('pacientes.index', compact('pacientes'));
    }

    public function create()
    {
        return view('pacientes.create');
    }

    public function show(Patient $paciente)
    {
        return view('pacientes.show', compact('paciente'));
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        $person = Person::create(array_merge(
            ['organization_id' => auth()->user()->organization_id],
            $this->extractPersonData($data)
        ));
        $patientData = [
            'organization_id' => auth()->user()->organization_id,
            'person_id' => $person->id,
            'menor_idade' => $request->menor_idade === 'Sim',
            'responsavel_nome' => $data['responsavel_nome'] ?? null,
            'responsavel_nome_meio' => $data['responsavel_nome_meio'] ?? null,
            'responsavel_ultimo_nome' => $data['responsavel_ultimo_nome'] ?? null,
            'responsavel_cpf' => $data['responsavel_cpf'] ?? null,
        ];

        $paciente = Patient::create($patientData);

        if ($request->boolean('create_user') && $paciente->email) {
            $profile = Profile::firstOrCreate([
                'nome' => 'Paciente',
                'organization_id' => auth()->user()->organization_id,
            ]);

            $password = Str::random(8);
            $user = User::create([
                'name' => $paciente->nome.' '.$paciente->ultimo_nome,
                'email' => $paciente->email,
                'organization_id' => auth()->user()->organization_id,
                'password' => Hash::make($password),
                'must_change_password' => true,
            ]);

            $user->profiles()->syncWithoutDetaching([$profile->id => ['clinic_id' => null]]);

            $paciente->user_id = $user->id;
            $paciente->save();
        }

        return redirect()->route('pacientes.index')->with('success', 'Paciente salvo com sucesso.');
    }

    public function edit(Patient $paciente)
    {
        return view('pacientes.edit', compact('paciente'));
    }

    public function update(Request $request, Patient $paciente)
    {
        $data = $this->validateData($request);
        $paciente->person->update($this->extractPersonData($data));

        $paciente->update([
            'menor_idade' => $request->menor_idade === 'Sim',
            'responsavel_nome' => $data['responsavel_nome'] ?? null,
            'responsavel_nome_meio' => $data['responsavel_nome_meio'] ?? null,
            'responsavel_ultimo_nome' => $data['responsavel_ultimo_nome'] ?? null,
            'responsavel_cpf' => $data['responsavel_cpf'] ?? null,
        ]);
        return redirect()->route('pacientes.index')->with('success', 'Paciente atualizado com sucesso.');
    }

    public function destroy(Patient $paciente)
    {
        $paciente->delete();
        return redirect()->route('pacientes.index')->with('success', 'Paciente removido com sucesso.');
    }

    public function search(Request $request)
    {
        $term = $request->get('q', '');
        $results = Patient::with('person')
            ->whereHas('person', function ($q) use ($term) {
                $q->where('first_name', 'like', "%{$term}%")
                    ->orWhere('last_name', 'like', "%{$term}%")
                    ->orWhere('phone', 'like', "%{$term}%")
                    ->orWhere('whatsapp', 'like', "%{$term}%")
                    ->orWhere('email', 'like', "%{$term}%")
                    ->orWhere('cpf', 'like', "%{$term}%");
            })
            ->limit(10)
            ->get()
            ->map(function ($p) {
                return $p->person->first_name;
            });

        return response()->json($results);
    }

    private function validateData(Request $request): array
    {
        $rules = [
            'nome' => 'required',
            'nome_meio' => 'nullable',
            'ultimo_nome' => 'required',
            'data_nascimento' => 'required|date',
            'cpf' => 'nullable',
            'menor_idade' => 'required|in:Sim,Não',
            'responsavel_nome' => 'nullable',
            'responsavel_nome_meio' => 'nullable',
            'responsavel_ultimo_nome' => 'nullable',
            'responsavel_cpf' => 'nullable',
            'telefone' => 'nullable',
            'whatsapp' => 'nullable',
            'email' => 'nullable|email',
            'cep' => 'nullable',
            'logradouro' => 'nullable',
            'numero' => 'nullable',
            'complemento' => 'nullable',
            'bairro' => 'nullable',
            'cidade' => 'nullable',
            'estado' => 'nullable',
        ];

        if ($request->menor_idade === 'Sim') {
            $rules['responsavel_nome'] = 'required';
            $rules['responsavel_cpf'] = 'required';
            $rules['cpf'] = 'nullable';
        } else {
            $rules['cpf'] = 'required';
        }

        $data = $request->validate($rules);
        return $data;
    }

    private function extractPersonData(array $data): array
    {
        return [
            'first_name' => $data['nome'],
            'middle_name' => $data['nome_meio'] ?? null,
            'last_name' => $data['ultimo_nome'],
            'data_nascimento' => $data['data_nascimento'],
            'cpf' => $data['cpf'] ?? null,
            'phone' => $data['telefone'] ?? null,
            'whatsapp' => $data['whatsapp'] ?? null,
            'email' => $data['email'] ?? null,
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
