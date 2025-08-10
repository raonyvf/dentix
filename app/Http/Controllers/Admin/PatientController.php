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
            ['organizacao_id' => auth()->user()->organizacao_id],
            $this->extractPessoaData($data)
        ));
        $patientData = [
            'organizacao_id' => auth()->user()->organizacao_id,
            'pessoa_id' => $pessoa->id,
            'menor_idade' => $request->menor_idade === 'Sim',
            'responsavel_primeiro_nome' => $data['responsavel_primeiro_nome'] ?? null,
            'responsavel_nome_meio' => $data['responsavel_nome_meio'] ?? null,
            'responsavel_ultimo_nome' => $data['responsavel_ultimo_nome'] ?? null,
            'responsavel_cpf' => $data['responsavel_cpf'] ?? null,
        ];

        $paciente = Patient::create($patientData);

        if ($request->boolean('create_user') && $paciente->email) {
            $perfil = Perfil::firstOrCreate([
                'nome' => 'Paciente',
                'organizacao_id' => auth()->user()->organizacao_id,
            ]);

            $password = Str::random(8);
            $usuario = Usuario::create([
                'name' => $paciente->pessoa->primeiro_nome.' '.$paciente->pessoa->ultimo_nome,
                'email' => $paciente->email,
                'organizacao_id' => auth()->user()->organizacao_id,
                'password' => Hash::make($password),
                'must_change_password' => true,
            ]);

            $usuario->perfis()->syncWithoutDetaching([$perfil->id => ['clinica_id' => null]]);

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
            'responsavel_primeiro_nome' => $data['responsavel_primeiro_nome'] ?? null,
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
        $q = trim($request->query('q', ''));

        if ($q === '') {
            return response()->json([]);
        }

        $normalized = Str::of($q)->ascii()->lower();
        $digits = preg_replace('/\D/', '', $q);

        $patients = Patient::with('pessoa')
            ->whereHas('pessoa', function ($query) use ($normalized, $q, $digits) {
                $query->where('normalized_name', 'like', "%$normalized%")
                    ->orWhere('email', 'like', "%$q%")
                    ->orWhere('digits_cpf', 'like', "%$digits%")
                    ->orWhere('digits_phone', 'like', "%$digits%")
                    ->orWhere('digits_whatsapp', 'like', "%$digits%");
            })
            ->limit(10)
            ->get();

        $result = $patients->map(fn ($p) => [
            'id' => $p->id,
            'nome' => $p->pessoa->full_name,
            'email' => $p->pessoa->email,
        ]);

        return response()->json($result);
    }

    private function validateData(Request $request): array
    {
        $rules = [
            'primeiro_nome' => 'required',
            'nome_meio' => 'nullable',
            'ultimo_nome' => 'required',
            'data_nascimento' => 'required|date',
            'sexo' => 'required',
            'cpf' => 'nullable',
            'menor_idade' => 'required|in:Sim,Não',
            'responsavel_primeiro_nome' => 'nullable',
            'responsavel_nome_meio' => 'nullable',
            'responsavel_ultimo_nome' => 'nullable',
            'responsavel_cpf' => 'nullable',
            'telefone' => 'required',
            'whatsapp' => 'nullable',
            'email' => 'nullable|email',
            'cep' => 'required',
            'logradouro' => 'required',
            'numero' => 'required',
            'complemento' => 'nullable',
            'bairro' => 'required',
            'cidade' => 'required',
            'estado' => 'required',
        ];

        if ($request->menor_idade === 'Sim') {
            $rules['responsavel_primeiro_nome'] = 'required';
            $rules['responsavel_ultimo_nome'] = 'required';
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
            'primeiro_nome' => $data['primeiro_nome'],
            'nome_meio' => $data['nome_meio'] ?? null,
            'ultimo_nome' => $data['ultimo_nome'],
            'data_nascimento' => $data['data_nascimento'],
            'sexo' => $data['sexo'],
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
