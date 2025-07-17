<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
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
            'phone' => 'nullable',
            'profile_id' => 'required|exists:profiles,id',
            'clinic_id' => 'required|exists:clinics,id',
            'photo' => 'nullable|image',
        ]);

        $password = Str::random(10);

        $user = new User();
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->phone = $data['phone'] ?? null;
        $user->organization_id = auth()->user()->organization_id;
        $user->password = Hash::make($password);

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('users', 'public');
            $user->photo_path = $path;
        }

        $user->save();
        $user->clinics()->attach($data['clinic_id'], ['profile_id' => $data['profile_id']]);

        Password::sendResetLink(['email' => $user->email]);

        return redirect()->route('usuarios.index')->with('success', 'Usuário salvo com sucesso.');
    }
}
