<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Session;

use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;


class AuthenticatedSessionController extends Controller
{
    public function create()
    {
        return view('auth.login');
    }

    public function store(Request $request)
    {
        $this->ensureIsNotRateLimited($request);

        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {

            RateLimiter::clear($this->throttleKey($request));
            $request->session()->regenerate();

            $user = Auth::user();

            $previousSession = $user->current_session_id;
            $request->session()->regenerate();
            $currentSession = $request->session()->getId();

            if ($previousSession && $previousSession !== $currentSession) {
                Session::getHandler()->destroy($previousSession);
            }

            $user->startSession($currentSession);
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

        RateLimiter::hit($this->throttleKey($request), 600);

        return back()->withErrors([
            'email' => 'Senha errada ou usuário não existente.',
        ]);
    }

    public function destroy(Request $request)
    {
        $user = $request->user();

        Auth::guard('web')->logout();

        if ($user) {
            $user->clearSession();
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    protected function throttleKey(Request $request): string
    {
        return Str::lower($request->input('email')).'|'.$request->ip();
    }

    protected function ensureIsNotRateLimited(Request $request): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey($request), 5)) {
            return;
        }

        $seconds = RateLimiter::availableIn($this->throttleKey($request));

        throw ValidationException::withMessages([
            'email' => __('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }
}
