<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminUserController extends Controller
{
    public function index()
    {
        $admins = Usuario::whereHas('perfis', function ($q) {
            $q->whereIn('nome', ['Administrador', 'Super Administrador']);
        })->with(['organization', 'pessoa'])->get();

        return view('backend.usuarios-admin.index', compact('admins'));
    }

    public function edit(Usuario $usuario)
    {
        return view('backend.usuarios-admin.edit', compact('usuario'));
    }

    public function update(Request $request, Usuario $usuario)
    {
        $data = $request->validate([
            'password' => 'required|confirmed|min:8',
        ]);

        $usuario->password = Hash::make($data['password']);
        $usuario->must_change_password = true;
        $usuario->save();

        return redirect()->route('usuarios-admin.index')->with('success', 'Senha atualizada com sucesso.');
    }
}
