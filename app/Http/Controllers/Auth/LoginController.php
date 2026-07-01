<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LoginController extends Controller
{
    /**
     * Affiche le formulaire de connexion.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Traite la tentative de connexion.
     * Authentifie avec email ou login + mot de passe.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email'    => ['required', 'string'],
            'password' => ['required', 'string'],
        ], [
            'email.required'    => 'L\'email ou le pseudo est obligatoire.',
            'password.required' => 'Le mot de passe est obligatoire.',
        ]);

        // Tentative de connexion avec email ou login
        $credentials = filter_var($request->email, FILTER_VALIDATE_EMAIL)
            ? ['email' => $request->email, 'password' => $request->password]
            : ['login' => $request->email, 'password' => $request->password];

        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()
                ->withErrors(['email' => 'Identifiants incorrects. Vérifiez votre email/pseudo et mot de passe.'])
                ->withInput($request->only('email'));
        }

        $request->session()->regenerate();

        return redirect()->intended(route('home'))
            ->with('success', 'Bienvenue ' . Auth::user()->login . ' !');
    }

    /**
     * Déconnecte l'utilisateur.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')
            ->with('success', 'Vous avez été déconnecté.');
    }
}
