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
        return view('admin.organizations.index', compact('organizations'));
    }

    public function create()
    {
        return view('admin.organizations.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nome' => 'required',
            'responsavel' => 'required',
            'email' => 'required|email|unique:users,email',
        ]);

        $organization = Organization::create(['nome' => $data['nome']]);

        $profile = Profile::create([
            'organization_id' => $organization->id,
            'nome' => 'Administrador',
        ]);

        $password = Str::random(10);

        $user = User::create([
            'name' => $data['responsavel'],
            'email' => $data['email'],
            'organization_id' => $organization->id,
            'profile_id' => $profile->id,
            'password' => Hash::make($password),
        ]);

        return redirect()->route('organizacoes.index')
            ->with('success', 'Organização criada com sucesso.');
    }
}
