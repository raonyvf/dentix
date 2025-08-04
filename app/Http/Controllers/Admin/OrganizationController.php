<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\Perfil;
use App\Models\Usuario;
use App\Models\Pessoa;
use App\Notifications\NewAdminPasswordNotification;
use App\Jobs\SendNewAdminPasswordEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class OrganizationController extends Controller
{
    public function index()
    {
        $organizations = Organization::all();
        return view('backend.organizations.index', compact('organizations'));
    }

    public function create()
    {
        return view('backend.organizations.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'primeiro_nome' => 'required',
            'nome_meio' => 'nullable',
            'ultimo_nome' => 'required',
            'nome_fantasia' => 'required',
            'razao_social' => 'nullable',
            'cnpj' => 'required',
            'email' => 'required|email|unique:usuarios,email',
            'telefone' => 'nullable',
            'cep' => 'nullable',
            'logradouro' => 'nullable',
            'numero' => 'nullable',
            'complemento' => 'nullable',
            'bairro' => 'nullable',
            'cidade' => 'nullable',
            'estado' => 'nullable',
            'logo_url' => 'nullable',
            'status' => 'in:ativo,inativo,suspenso',
            'timezone' => 'required|timezone',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $organization = Organization::create([
            'nome_fantasia' => $data['nome_fantasia'],
            'razao_social' => $data['razao_social'] ?? null,
            'cnpj' => $data['cnpj'],
            'email' => $data['email'],
            'telefone' => $data['telefone'] ?? null,
            'responsavel_primeiro_nome' => $data['primeiro_nome'],
            'responsavel_nome_meio' => $data['nome_meio'] ?? null,
            'responsavel_ultimo_nome' => $data['ultimo_nome'],
            'cep' => $data['cep'] ?? null,
            'logradouro' => $data['logradouro'] ?? null,
            'numero' => $data['numero'] ?? null,
            'complemento' => $data['complemento'] ?? null,
            'bairro' => $data['bairro'] ?? null,
            'cidade' => $data['cidade'] ?? null,
            'estado' => $data['estado'] ?? null,
            'logo_url' => $data['logo_url'] ?? null,
            'status' => $data['status'] ?? 'ativo',
            'timezone' => $data['timezone'],
        ]);
     
        $perfil = Perfil::create([
            'organizacao_id' => $organization->id,
            'nome' => 'Administrador',
        ]);

        $modules = [
            'Pacientes',
            'Agenda',
            'Prontuários',
            'Profissionais',
            'Estoque',
            'Financeiro',
            'Clínicas',
            'Cadeiras',
            'Usuários',
        ];

        foreach ($modules as $module) {
            $perfil->permissoes()->create([
                'modulo' => $module,
                'leitura' => true,
                'escrita' => true,
                'atualizacao' => true,
                'exclusao' => true,
            ]);
        }

        $password = $data['password'] ?? Str::random(10);

        $pessoa = Pessoa::create([
            'organizacao_id' => $organization->id,
            'primeiro_nome' => $data['primeiro_nome'],
            'nome_meio' => $data['nome_meio'] ?? null,
            'ultimo_nome' => $data['ultimo_nome'],
            'email' => $data['email'],
        ]);

        $usuario = Usuario::create([
            'email' => $data['email'],
            'organizacao_id' => $organization->id,
            'password' => Hash::make($password),
            'must_change_password' => true,
            'pessoa_id' => $pessoa->id,
        ]);
        $usuario->perfis()->syncWithoutDetaching([$perfil->id => ['clinica_id' => null]]);

        SendNewAdminPasswordEmail::dispatch($usuario, $password);

        return redirect()->route('organizacoes.index')
            ->with('success', 'Organização criada com sucesso.');
    }

    public function edit(Organization $organization)
    {
        return view('backend.organizations.edit', compact('organization'));
    }

    public function update(Request $request, Organization $organization)
    {
        $data = $request->validate([
            'primeiro_nome' => 'sometimes',
            'nome_meio' => 'nullable',
            'ultimo_nome' => 'sometimes',
            'nome_fantasia' => 'required',
            'razao_social' => 'nullable',
            'cnpj' => 'required',
            'email' => 'required|email',
            'telefone' => 'nullable',
            'cep' => 'nullable',
            'logradouro' => 'nullable',
            'numero' => 'nullable',
            'complemento' => 'nullable',
            'bairro' => 'nullable',
            'cidade' => 'nullable',
            'estado' => 'nullable',
            'logo_url' => 'nullable',
            'status' => 'in:ativo,inativo,suspenso',
            'timezone' => 'required|timezone',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $organization->update([
            'nome_fantasia' => $data['nome_fantasia'],
            'razao_social' => $data['razao_social'] ?? null,
            'cnpj' => $data['cnpj'],
            'email' => $data['email'],
            'telefone' => $data['telefone'] ?? null,
            'responsavel_primeiro_nome' => $data['primeiro_nome'] ?? $organization->responsavel_primeiro_nome,
            'responsavel_nome_meio' => $data['nome_meio'] ?? $organization->responsavel_nome_meio,
            'responsavel_ultimo_nome' => $data['ultimo_nome'] ?? $organization->responsavel_ultimo_nome,
            'cep' => $data['cep'] ?? null,
            'logradouro' => $data['logradouro'] ?? null,
            'numero' => $data['numero'] ?? null,
            'complemento' => $data['complemento'] ?? null,
            'bairro' => $data['bairro'] ?? null,
            'cidade' => $data['cidade'] ?? null,
            'estado' => $data['estado'] ?? null,
            'logo_url' => $data['logo_url'] ?? null,
            'status' => $data['status'] ?? $organization->status,
            'timezone' => $data['timezone'],
        ]);

        $usuario = Usuario::where('organizacao_id', $organization->id)->first();
        if ($usuario) {
            if ($request->filled('primeiro_nome') || $request->filled('nome_meio') || $request->filled('ultimo_nome')) {
                $usuario->pessoa?->update([
                    'primeiro_nome' => $request->input('primeiro_nome') ?? $usuario->pessoa->primeiro_nome,
                    'nome_meio' => $request->input('nome_meio') ?? $usuario->pessoa->nome_meio,
                    'ultimo_nome' => $request->input('ultimo_nome') ?? $usuario->pessoa->ultimo_nome,
                ]);
            }
            if ($request->filled('password')) {
                $usuario->password = Hash::make($request->input('password'));
                $usuario->must_change_password = true;
            }
            $usuario->save();
        }

        return redirect()->route('organizacoes.index')
            ->with('success', 'Organização atualizada com sucesso.');
    }
}
