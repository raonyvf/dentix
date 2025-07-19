<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $profiles = Profile::all();
        $clinics = auth()->user()->organization->clinics ?? [];
        return view('admin.users.create', compact('profiles', 'clinics'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'first_name' => 'required',
            'middle_name' => 'nullable',
            'last_name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'nullable|string|min:8|confirmed',
            'phone' => 'nullable',
            'logradouro' => 'nullable',
            'numero' => 'nullable',
            'complemento' => 'nullable',
            'bairro' => 'nullable',
            'cep' => 'nullable',
            'cidade' => 'nullable',
            'estado' => 'nullable',
            'cpf' => 'nullable',
            'dentista' => 'nullable|boolean',
            'cro' => 'required_if:dentista,1|nullable',
            'profiles' => 'required|array|min:1',
            'profiles.*.profile_id' => 'required|exists:profiles,id',
            'profiles.*.clinic_id' => 'required|exists:clinics,id',
            'photo' => 'nullable|image',
        ]);

        $password = $data['password'] ?? Str::random(10);

        $user = new User();
        $user->first_name = $data['first_name'];
        $user->middle_name = $data['middle_name'] ?? null;
        $user->last_name = $data['last_name'];
        $user->name = trim($data['first_name'] . ' ' . ($data['middle_name'] ?? '') . ' ' . $data['last_name']);
        $user->email = $data['email'];
        $user->phone = $data['phone'] ?? null;
        $user->logradouro = $data['logradouro'] ?? null;
        $user->numero = $data['numero'] ?? null;
        $user->complemento = $data['complemento'] ?? null;
        $user->bairro = $data['bairro'] ?? null;
        $user->cep = $data['cep'] ?? null;
        $user->cidade = $data['cidade'] ?? null;
        $user->estado = $data['estado'] ?? null;
        $user->cpf = $data['cpf'] ?? null;
        $user->dentista = $data['dentista'] ?? false;
        $user->cro = $data['cro'] ?? null;
        $user->organization_id = auth()->user()->organization_id;
        $user->password = Hash::make($password);
        $user->must_change_password = true;

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('users', 'public');
            $user->photo_path = $path;
        }

        $user->save();

        foreach ($data['profiles'] as $pair) {
            $user->clinics()->attach($pair['clinic_id'], ['profile_id' => $pair['profile_id']]);
        }

        return redirect()->route('usuarios.index')->with('success', 'Usuário salvo com sucesso.');
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
            'first_name' => 'required',
            'middle_name' => 'nullable',
            'last_name' => 'required',
            'email' => 'required|email|unique:users,email,' . $usuario->id,
            'phone' => 'nullable',
            'password' => 'nullable|string|min:8|confirmed',
            'logradouro' => 'nullable',
            'numero' => 'nullable',
            'complemento' => 'nullable',
            'bairro' => 'nullable',
            'cep' => 'nullable',
            'cidade' => 'nullable',
            'estado' => 'nullable',
            'cpf' => 'nullable',
            'dentista' => 'nullable|boolean',
            'cro' => 'required_if:dentista,1|nullable',
            'profiles' => 'required|array|min:1',
            'profiles.*.profile_id' => 'required|exists:profiles,id',
            'profiles.*.clinic_id' => 'required|exists:clinics,id',
            'photo' => 'nullable|image',
        ]);

        $usuario->first_name = $data['first_name'];
        $usuario->middle_name = $data['middle_name'] ?? null;
        $usuario->last_name = $data['last_name'];
        $usuario->name = trim($data['first_name'] . ' ' . ($data['middle_name'] ?? '') . ' ' . $data['last_name']);
        $usuario->email = $data['email'];
        $usuario->phone = $data['phone'] ?? null;
        $usuario->logradouro = $data['logradouro'] ?? null;
        $usuario->numero = $data['numero'] ?? null;
        $usuario->complemento = $data['complemento'] ?? null;
        $usuario->bairro = $data['bairro'] ?? null;
        $usuario->cep = $data['cep'] ?? null;
        $usuario->cidade = $data['cidade'] ?? null;
        $usuario->estado = $data['estado'] ?? null;
        $usuario->cpf = $data['cpf'] ?? null;
        $usuario->dentista = $data['dentista'] ?? false;
        $usuario->cro = $data['cro'] ?? null;

        if ($request->filled('password')) {
            $usuario->password = Hash::make($data['password']);
            $usuario->must_change_password = true;
        }

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('users', 'public');
            $usuario->photo_path = $path;
        }

        $usuario->save();

        $usuario->clinics()->detach();
        foreach ($data['profiles'] as $pair) {
            $usuario->clinics()->attach($pair['clinic_id'], ['profile_id' => $pair['profile_id']]);
        }

        return redirect()->route('usuarios.index')->with('success', 'Usuário atualizado com sucesso.');
    }
}
