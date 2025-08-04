<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use App\Models\Perfil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $usuarios = Usuario::with(['pessoa', 'clinics'])->get();
        return view('admin.usuarios.index', compact('usuarios'));
    }


    public function edit(Usuario $usuario)
    {
        $perfis = Perfil::all();
        $clinics = auth()->user()->organization->clinics ?? [];
        return view('admin.usuarios.edit', compact('usuario', 'perfis', 'clinics'));
    }

    public function update(Request $request, Usuario $usuario)
    {
        $data = $request->validate([
            'password' => 'nullable|string|min:8|confirmed',
            'perfis' => 'required|array|min:1',
            'perfis.*.perfil_id' => 'required|exists:perfis,id',
            'perfis.*.clinic_id' => 'required|exists:clinicas,id',
        ]);

        if ($request->filled('password')) {
            $usuario->password = Hash::make($data['password']);
            $usuario->must_change_password = true;
        }

        $usuario->clinics()->detach();
        foreach ($data['perfis'] as $pair) {
            $usuario->clinics()->attach($pair['clinic_id'], ['perfil_id' => $pair['perfil_id']]);
        }

        $usuario->save();

        return redirect()->route('usuarios.index')->with('success', 'Usuário atualizado com sucesso.');
    }
}
