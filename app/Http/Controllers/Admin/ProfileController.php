<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index()
    {
        $profiles = Profile::all();
        return view('admin.profiles.index', compact('profiles'));
    }

    public function create()
    {
        $modules = $this->modules();
        return view('admin.profiles.create', compact('modules'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nome' => 'required',
            'permissions' => 'array',
        ]);

        $profile = Profile::create([
            'nome' => $data['nome'],
            'organization_id' => auth()->user()->organization_id,
        ]);

        $this->syncPermissions($profile, $data['permissions'] ?? []);

        return redirect()->route('perfis.index')->with('success', 'Perfil salvo com sucesso.');
    }

    public function edit(Profile $perfil)
    {
        $modules = $this->modules();
        $permissions = $perfil->permissions->keyBy('modulo');
        return view('admin.profiles.edit', compact('perfil', 'modules', 'permissions'));
    }

    public function update(Request $request, Profile $perfil)
    {
        $data = $request->validate([
            'nome' => 'required',
            'permissions' => 'array',
        ]);

        $perfil->update(['nome' => $data['nome']]);

        $perfil->permissions()->delete();
        $this->syncPermissions($perfil, $data['permissions'] ?? []);

        return redirect()->route('perfis.index')->with('success', 'Perfil atualizado com sucesso.');
    }

    public function destroy(Profile $perfil)
    {
        $perfil->delete();
        return redirect()->route('perfis.index')->with('success', 'Perfil removido com sucesso.');
    }

    private function modules(): array
    {
        return [
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
    }

    private function syncPermissions(Profile $profile, array $permissions): void
    {
        foreach ($permissions as $module => $values) {
            $profile->permissions()->create([
                'modulo' => $module,
                'leitura' => isset($values['leitura']),
                'escrita' => isset($values['escrita']),
                'atualizacao' => isset($values['atualizacao']),
                'exclusao' => isset($values['exclusao']),
            ]);
        }
    }
}
