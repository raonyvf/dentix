<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Perfil;
use Illuminate\Http\Request;

class PerfilController extends Controller
{
    public function index()
    {
        $perfis = Perfil::all();
        return view('admin.perfis.index', compact('perfis'));
    }

    public function create()
    {
        $modules = $this->modules();
        return view('admin.perfis.create', compact('modules'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nome' => 'required',
            'permissions' => 'array',
        ]);

        $perfil = Perfil::create([
            'nome' => $data['nome'],
            'organizacao_id' => auth()->user()->organizacao_id,
        ]);

        $this->syncPermissions($perfil, $data['permissions'] ?? []);

        return redirect()->route('perfis.index')->with('success', 'Perfil salvo com sucesso.');
    }

    public function edit(Perfil $perfil)
    {
        $modules = $this->modules();
        $permissions = $perfil->permissions->keyBy('modulo');
        return view('admin.perfis.edit', compact('perfil', 'modules', 'permissions'));
    }

    public function update(Request $request, Perfil $perfil)
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

    public function destroy(Perfil $perfil)
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

    private function syncPermissions(Perfil $perfil, array $permissions): void
    {
        foreach ($permissions as $module => $values) {
            $perfil->permissions()->create([
                'modulo' => $module,
                'leitura' => isset($values['leitura']),
                'escrita' => isset($values['escrita']),
                'atualizacao' => isset($values['atualizacao']),
                'exclusao' => isset($values['exclusao']),
            ]);
        }
    }
}
