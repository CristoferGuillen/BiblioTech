<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Manejar intento de login
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Las credenciales proporcionadas son incorrectas.'],
            ]);
        }

        // Verificar si la cuenta está activa (no eliminada)
        if ($user->trashed()) {
            throw ValidationException::withMessages([
                'email' => ['Esta cuenta ha sido desactivada.'],
            ]);
        }

        // Login del usuario
        Auth::login($user, $request->boolean('remember'));

        $request->session()->regenerate();

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Manejar logout
     */
    public function logout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }

    /**
     * Verificar si el usuario es administrador
     */
    public function isAdmin(): bool
    {
        return Auth::check() && Auth::user()->role === 'admin';
    }

    /**
     * Verificar si el usuario es bibliotecario
     */
    public function isLibrarian(): bool
    {
        return Auth::check() && Auth::user()->role === 'librarian';
    }

    /**
     * Verificar si el usuario es miembro
     */
    public function isMember(): bool
    {
        return Auth::check() && Auth::user()->role === 'member';
    }
}
