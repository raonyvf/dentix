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
use Normalizer;

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
        $term = trim($request->get('q', ''));
        if ($term === '') {
            return response()->json([]);
        }

        $normTerm = $this->normalize($term);
        $digitTerm = $this->digits($term);

        $clinicId = function_exists('clinicId') ? clinicId() : (function_exists('app') && app()->bound('clinic_id') ? app('clinic_id') : null);

        $nameExpr = "lower(unaccent(concat(coalesce(pessoas.primeiro_nome,''),' ',coalesce(pessoas.nome_meio,''),' ',coalesce(pessoas.ultimo_nome,''))))";
        $emailExpr = "lower(unaccent(coalesce(pessoas.email,'')))";
        $phoneExpr = "regexp_replace(coalesce(pessoas.phone,''),'\\D','')";
        $whatsExpr = "regexp_replace(coalesce(pessoas.whatsapp,''),'\\D','')";
        $cpfExpr = "regexp_replace(coalesce(pessoas.cpf,''),'\\D','')";

        $patients = Patient::query()
            ->join('pessoas', 'pacientes.pessoa_id', '=', 'pessoas.id')
            ->when($clinicId, fn($q) => $q->whereHas('clinicas', fn($q2) => $q2->where('clinica_id', $clinicId)))
            ->where(function ($query) use ($nameExpr, $emailExpr, $phoneExpr, $whatsExpr, $cpfExpr, $normTerm, $digitTerm) {
                if ($normTerm !== '') {
                    $query->whereRaw("$nameExpr LIKE ?", ["{$normTerm}%"])
                        ->orWhereRaw("$nameExpr LIKE ?", ["%{$normTerm}%"])
                        ->orWhereRaw("$emailExpr LIKE ?", ["%{$normTerm}%"]);
                }

                if ($digitTerm !== '') {
                    $query->orWhereRaw("$phoneExpr LIKE ?", ["%{$digitTerm}%"])
                        ->orWhereRaw("$whatsExpr LIKE ?", ["%{$digitTerm}%"])
                        ->orWhereRaw("$cpfExpr LIKE ?", ["%{$digitTerm}%"])
                        ->orWhere('pacientes.id', $digitTerm);
                }
            })
            ->orderByRaw(
                "CASE\n" .
                "    WHEN $nameExpr LIKE ? THEN 0\n" .
                "    WHEN $nameExpr LIKE ? THEN 1\n" .
                "    WHEN $emailExpr LIKE ? OR $phoneExpr LIKE ? OR $whatsExpr LIKE ? OR $cpfExpr LIKE ? THEN 2\n" .
                "    ELSE 3\n" .
                "END",
                ["{$normTerm}%", "%{$normTerm}%", "%{$normTerm}%", "%{$digitTerm}%", "%{$digitTerm}%", "%{$digitTerm}%"]
            )
            ->limit(10)
            ->get([
                'pacientes.id',
                'pessoas.primeiro_nome',
                'pessoas.nome_meio',
                'pessoas.ultimo_nome',
                'pessoas.phone',
                'pessoas.whatsapp',
                'pessoas.cpf',
            ]);

        $results = $patients->map(function ($p) {
            $name = trim($p->primeiro_nome . ' ' . ($p->nome_meio ? $p->nome_meio . ' ' : '') . $p->ultimo_nome);
            $phone = $p->phone ?? $p->whatsapp ?? '';
            return [
                'id' => $p->id,
                'name' => $name,
                'phone' => $phone,
                'cpf' => $p->cpf ?? '',
            ];
        })->toArray();

        return response()->json($results);
    }

    private function normalize(string $value): string
    {
        $normalized = Normalizer::normalize($value, Normalizer::FORM_D);
        $withoutAccents = preg_replace('/\pM/u', '', $normalized);
        return mb_strtolower($withoutAccents);
    }

    private function digits(string $value): string
    {
        return preg_replace('/\D/', '', $value);
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
