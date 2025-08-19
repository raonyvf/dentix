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
            'permissoes' => 'array',
        ]);

        $perfil = Perfil::create([
            'nome' => $data['nome'],
            'organizacao_id' => auth()->user()->organizacao_id,
        ]);

        $this->syncPermissoes($perfil, $data['permissoes'] ?? []);

        return redirect()->route('perfis.index')->with('success', 'Perfil salvo com sucesso.');
    }

    public function edit(Perfil $perfil)
    {
        $modules = $this->modules();
        $permissoes = $perfil->permissoes->keyBy('modulo');
        return view('admin.perfis.edit', compact('perfil', 'modules', 'permissoes'));
    }

    public function update(Request $request, Perfil $perfil)
    {
        $data = $request->validate([
            'nome' => 'required',
            'permissoes' => 'array',
        ]);

        $perfil->update(['nome' => $data['nome']]);

        $perfil->permissoes()->delete();
        $this->syncPermissoes($perfil, $data['permissoes'] ?? []);

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
            'Clínicas',
            'Cadeiras',
            'Usuários',
        ];
    }

    private function syncPermissoes(Perfil $perfil, array $permissoes): void
    {
        foreach ($permissoes as $module => $values) {
            $perfil->permissoes()->create([
                'modulo' => $module,
                'leitura' => isset($values['leitura']),
                'escrita' => isset($values['escrita']),
                'atualizacao' => isset($values['atualizacao']),
                'exclusao' => isset($values['exclusao']),
            ]);
        }
    }
}
