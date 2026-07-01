<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class RegisterController extends Controller
{
    /**
     * Affiche le formulaire d'inscription.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Traite l'inscription d'un nouvel utilisateur.
     * Valide les données, crée le compte, envoie l'email de vérification.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'login'        => ['required', 'string', 'min:3', 'max:50', 'unique:users,login', 'regex:/^[a-zA-Z0-9_\-]+$/'],
            'email'        => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'phone_number' => ['nullable', 'string', 'max:20'],
            'password'     => ['required', 'confirmed', Password::min(8)],
        ], [
            'login.required'   => 'Le pseudo est obligatoire.',
            'login.unique'     => 'Ce pseudo est déjà utilisé.',
            'login.regex'      => 'Le pseudo ne peut contenir que des lettres, chiffres, tirets et underscores.',
            'email.required'   => 'L\'email est obligatoire.',
            'email.unique'     => 'Cet email est déjà utilisé.',
            'password.min'     => 'Le mot de passe doit contenir au moins 8 caractères.',
            'password.confirmed'=> 'Les mots de passe ne correspondent pas.',
        ]);

        $user = User::create([
            'login'        => $request->login,
            'email'        => $request->email,
            'phone_number' => $request->phone_number,
            'password'     => Hash::make($request->password),
        ]);

        // Déclenche l'envoi de l'email de vérification
        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('verification.notice')
            ->with('success', 'Compte créé ! Vérifiez votre email pour activer votre compte.');
    }
}
