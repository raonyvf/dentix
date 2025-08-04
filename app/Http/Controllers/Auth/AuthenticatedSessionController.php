<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    public function create()
    {
        return view('auth.login');
    }

    public function store(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();
            if (
                $user->organization &&
                $user->organization->status !== 'ativo' &&
                ! $user->isSuperAdmin()
            ) {
                Auth::logout();
                return back()->withErrors([
                    'email' => __('Organização desativada.')
                ]);
            }
            if ($user->perfis()->where('nome', 'Paciente')->exists()) {
                return redirect()->intended('/portal');
            }

            if ($user->isSuperAdmin()) {
                return redirect()->intended('/backend');
            }

            return redirect()->intended(RouteServiceProvider::HOME);
        }

        return back()->withErrors([
            'email' => 'Senha errada ou usuário não existente.',
        ]);
    }

    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
