<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Patient;
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

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        $data['menor_idade'] = $request->menor_idade === 'Sim';
        Patient::create($data);
        return redirect()->route('pacientes.index')->with('success', 'Paciente salvo com sucesso.');
    }

    public function edit(Patient $paciente)
    {
        return view('pacientes.edit', compact('paciente'));
    }

    public function update(Request $request, Patient $paciente)
    {
        $data = $this->validateData($request);
        $data['menor_idade'] = $request->menor_idade === 'Sim';
        $paciente->update($data);
        return redirect()->route('pacientes.index')->with('success', 'Paciente atualizado com sucesso.');
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
        $data['organization_id'] = auth()->user()->organization_id;

        return $data;
    }
}
