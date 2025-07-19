<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminUserController extends Controller
{
    public function index()
    {
        $admins = User::whereHas('profiles', function ($q) {
            $q->whereIn('nome', ['Administrador', 'Super Administrador']);
        })->with('organization')->get();

        return view('backend.admin-users.index', compact('admins'));
    }

    public function edit(User $usuario)
    {
        return view('backend.admin-users.edit', compact('usuario'));
    }

    public function update(Request $request, User $usuario)
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
