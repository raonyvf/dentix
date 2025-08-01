<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with(['person', 'clinics'])->get();
        return view('admin.users.index', compact('users'));
    }


    public function edit(User $usuario)
    {
        $profiles = Profile::all();
        $clinics = auth()->user()->organization->clinics ?? [];
        return view('admin.users.edit', compact('usuario', 'profiles', 'clinics'));
    }

    public function update(Request $request, User $usuario)
    {
        $data = $request->validate([
            'password' => 'nullable|string|min:8|confirmed',
            'profiles' => 'required|array|min:1',
            'profiles.*.profile_id' => 'required|exists:profiles,id',
            'profiles.*.clinic_id' => 'required|exists:clinics,id',
        ]);

        if ($request->filled('password')) {
            $usuario->password = Hash::make($data['password']);
            $usuario->must_change_password = true;
        }

        $usuario->clinics()->detach();
        foreach ($data['profiles'] as $pair) {
            $usuario->clinics()->attach($pair['clinic_id'], ['profile_id' => $pair['profile_id']]);
        }

        $usuario->save();

        return redirect()->route('usuarios.index')->with('success', 'Usuário atualizado com sucesso.');
    }
}
