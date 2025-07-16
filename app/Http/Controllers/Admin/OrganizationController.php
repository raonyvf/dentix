<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\Profile;
use App\Models\User;
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
            'nome_fantasia' => 'required',
            'razao_social' => 'nullable',
            'cnpj' => 'required',
            'email' => 'required|email|unique:users,email',
            'telefone' => 'nullable',
            'endereco_faturamento' => 'nullable',
            'logo_url' => 'nullable',
            'status' => 'in:ativo,inativo,suspenso',
            'responsavel' => 'required',
        ]);

        $organization = Organization::create([
            'nome_fantasia' => $data['nome_fantasia'],
            'razao_social' => $data['razao_social'] ?? null,
            'cnpj' => $data['cnpj'],
            'email' => $data['email'],
            'telefone' => $data['telefone'] ?? null,
            'endereco_faturamento' => $data['endereco_faturamento'] ?? null,
            'logo_url' => $data['logo_url'] ?? null,
            'status' => $data['status'] ?? 'ativo',
        ]);
     
        $profile = Profile::create([
            'organization_id' => $organization->id,
            'nome' => 'Administrador',
        ]);

        $password = '123mudar';

        $user = User::create([
            'name' => $data['responsavel'],
            'email' => $data['email'],
            'organization_id' => $organization->id,
            'profile_id' => $profile->id,
            'password' => Hash::make($password),
            'must_change_password' => true,
        ]);

        return redirect()->route('organizacoes.index')
            ->with('success', 'Organização criada com sucesso.');
    }
}
