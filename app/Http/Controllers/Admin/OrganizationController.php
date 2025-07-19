<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\Profile;
use App\Models\User;
use App\Notifications\NewAdminPasswordNotification;
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
            'nome' => 'required',
            'nome_meio' => 'nullable',
            'sobrenome' => 'required',
            'nome_fantasia' => 'required',
            'razao_social' => 'nullable',
            'cnpj' => 'required',
            'email' => 'required|email|unique:users,email',
            'telefone' => 'nullable',
            'cep' => 'nullable',
            'endereco_rua' => 'nullable',
            'numero' => 'nullable',
            'complemento' => 'nullable',
            'bairro' => 'nullable',
            'cidade' => 'nullable',
            'estado' => 'nullable',
            'logo_url' => 'nullable',
            'status' => 'in:ativo,inativo,suspenso',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $organization = Organization::create([
            'nome_fantasia' => $data['nome_fantasia'],
            'razao_social' => $data['razao_social'] ?? null,
            'cnpj' => $data['cnpj'],
            'email' => $data['email'],
            'telefone' => $data['telefone'] ?? null,
            'responsavel_nome' => $data['nome'],
            'responsavel_nome_meio' => $data['nome_meio'] ?? null,
            'responsavel_ultimo_nome' => $data['sobrenome'],
            'cep' => $data['cep'] ?? null,
            'rua' => $data['endereco_rua'] ?? null,
            'numero' => $data['numero'] ?? null,
            'complemento' => $data['complemento'] ?? null,
            'bairro' => $data['bairro'] ?? null,
            'cidade' => $data['cidade'] ?? null,
            'estado' => $data['estado'] ?? null,
            'logo_url' => $data['logo_url'] ?? null,
            'status' => $data['status'] ?? 'ativo',
        ]);
     
        $profile = Profile::create([
            'organization_id' => $organization->id,
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
            $profile->permissions()->create([
                'modulo' => $module,
                'leitura' => true,
                'escrita' => true,
                'atualizacao' => true,
                'exclusao' => true,
            ]);
        }

        $password = $data['password'] ?? Str::random(10);

        $nomeCompleto = trim($data['nome'] . ' ' . ($data['nome_meio'] ? $data['nome_meio'] . ' ' : '') . $data['sobrenome']);

        $user = User::create([
            'name' => $nomeCompleto,
            'email' => $data['email'],
            'organization_id' => $organization->id,
            'password' => Hash::make($password),
            'must_change_password' => true,
        ]);

        $user->profiles()->syncWithoutDetaching([$profile->id => ['clinic_id' => null]]);

        $user->notify(new NewAdminPasswordNotification($password));

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
            'nome' => 'sometimes',
            'nome_meio' => 'nullable',
            'sobrenome' => 'sometimes',
            'nome_fantasia' => 'required',
            'razao_social' => 'nullable',
            'cnpj' => 'required',
            'email' => 'required|email',
            'telefone' => 'nullable',
            'cep' => 'nullable',
            'endereco_rua' => 'nullable',
            'numero' => 'nullable',
            'complemento' => 'nullable',
            'bairro' => 'nullable',
            'cidade' => 'nullable',
            'estado' => 'nullable',
            'logo_url' => 'nullable',
            'status' => 'in:ativo,inativo,suspenso',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $organization->update([
            'nome_fantasia' => $data['nome_fantasia'],
            'razao_social' => $data['razao_social'] ?? null,
            'cnpj' => $data['cnpj'],
            'email' => $data['email'],
            'telefone' => $data['telefone'] ?? null,
            'responsavel_nome' => $data['nome'] ?? $organization->responsavel_nome,
            'responsavel_nome_meio' => $data['nome_meio'] ?? $organization->responsavel_nome_meio,
            'responsavel_ultimo_nome' => $data['sobrenome'] ?? $organization->responsavel_ultimo_nome,
            'cep' => $data['cep'] ?? null,
            'rua' => $data['endereco_rua'] ?? null,
            'numero' => $data['numero'] ?? null,
            'complemento' => $data['complemento'] ?? null,
            'bairro' => $data['bairro'] ?? null,
            'cidade' => $data['cidade'] ?? null,
            'estado' => $data['estado'] ?? null,
            'logo_url' => $data['logo_url'] ?? null,
            'status' => $data['status'] ?? $organization->status,
        ]);

        $usuario = User::where('organization_id', $organization->id)->first();
        if ($usuario) {
            if ($request->filled('nome') || $request->filled('nome_meio') || $request->filled('sobrenome')) {
                $usuario->name = trim($request->input('nome') . ' ' . ($request->input('nome_meio') ? $request->input('nome_meio') . ' ' : '') . $request->input('sobrenome'));
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
