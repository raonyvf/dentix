<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\Usuario;
use App\Models\Perfil;
use App\Models\Pessoa;
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
        $pessoa = Pessoa::create(array_merge(
            ['organization_id' => auth()->user()->organization_id],
            $this->extractPessoaData($data)
        ));
        $patientData = [
            'organization_id' => auth()->user()->organization_id,
            'pessoa_id' => $pessoa->id,
            'menor_idade' => $request->menor_idade === 'Sim',
            'responsavel_first_name' => $data['responsavel_first_name'] ?? null,
            'responsavel_middle_name' => $data['responsavel_middle_name'] ?? null,
            'responsavel_last_name' => $data['responsavel_last_name'] ?? null,
            'responsavel_cpf' => $data['responsavel_cpf'] ?? null,
        ];

        $paciente = Patient::create($patientData);

        if ($request->boolean('create_user') && $paciente->email) {
            $perfil = Perfil::firstOrCreate([
                'nome' => 'Paciente',
                'organization_id' => auth()->user()->organization_id,
            ]);

            $password = Str::random(8);
            $usuario = Usuario::create([
                'name' => $paciente->pessoa->first_name.' '.$paciente->pessoa->last_name,
                'email' => $paciente->email,
                'organization_id' => auth()->user()->organization_id,
                'password' => Hash::make($password),
                'must_change_password' => true,
            ]);

            $usuario->perfis()->syncWithoutDetaching([$perfil->id => ['clinic_id' => null]]);

            $paciente->usuario_id = $usuario->id;
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
        $paciente->pessoa->update($this->extractPessoaData($data));

        $paciente->update([
            'menor_idade' => $request->menor_idade === 'Sim',
            'responsavel_first_name' => $data['responsavel_first_name'] ?? null,
            'responsavel_middle_name' => $data['responsavel_middle_name'] ?? null,
            'responsavel_last_name' => $data['responsavel_last_name'] ?? null,
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
        $results = Patient::with('pessoa')
            ->whereHas('pessoa', function ($q) use ($term) {
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
                $pessoa = $p->pessoa;
                return [
                    'id' => $p->id,
                    'name' => trim(($pessoa->first_name ?? '') . ' ' . ($pessoa->last_name ?? '')),
                ];
            })
            ->values();

        return response()->json($results);
    }

    private function validateData(Request $request): array
    {
        $rules = [
            'first_name' => 'required',
            'middle_name' => 'nullable',
            'last_name' => 'required',
            'data_nascimento' => 'required|date',
            'cpf' => 'nullable',
            'menor_idade' => 'required|in:Sim,Não',
            'responsavel_first_name' => 'nullable',
            'responsavel_middle_name' => 'nullable',
            'responsavel_last_name' => 'nullable',
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
            $rules['responsavel_first_name'] = 'required';
            $rules['responsavel_cpf'] = 'required';
            $rules['cpf'] = 'nullable';
        } else {
            $rules['cpf'] = 'required';
        }

        $data = $request->validate($rules);
        return $data;
    }

    private function extractPessoaData(array $data): array
    {
        return [
            'first_name' => $data['first_name'],
            'middle_name' => $data['middle_name'] ?? null,
            'last_name' => $data['last_name'],
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
