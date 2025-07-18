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
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'nullable|min:8',
            'phone' => 'nullable',
            'endereco' => 'nullable',
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
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->phone = $data['phone'] ?? null;
        $user->endereco = $data['endereco'] ?? null;
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
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $usuario->id,
            'phone' => 'nullable',
            'endereco' => 'nullable',
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

        $usuario->name = $data['name'];
        $usuario->email = $data['email'];
        $usuario->phone = $data['phone'] ?? null;
        $usuario->endereco = $data['endereco'] ?? null;
        $usuario->cep = $data['cep'] ?? null;
        $usuario->cidade = $data['cidade'] ?? null;
        $usuario->estado = $data['estado'] ?? null;
        $usuario->cpf = $data['cpf'] ?? null;
        $usuario->dentista = $data['dentista'] ?? false;
        $usuario->cro = $data['cro'] ?? null;

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
