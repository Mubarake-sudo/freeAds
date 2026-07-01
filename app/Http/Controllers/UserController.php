<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Affiche le profil de l'utilisateur connecté avec ses annonces.
     */
    public function show(): View
    {
        $user = Auth::user()->load('ads');
        return view('users.profile', compact('user'));
    }

    /**
     * Affiche le formulaire de modification du profil.
     */
    public function edit(): View
    {
        $user = Auth::user();
        return view('users.edit', compact('user'));
    }

    /**
     * Met à jour le profil utilisateur.
     */
    public function update(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $request->validate([
            'login'        => ['required', 'string', 'min:3', 'max:50', 'unique:users,login,' . $user->id, 'regex:/^[a-zA-Z0-9_\-]+$/'],
            'phone_number' => ['nullable', 'string', 'max:20'],
        ], [
            'login.required' => 'Le pseudo est obligatoire.',
            'login.unique'   => 'Ce pseudo est déjà utilisé.',
            'login.regex'    => 'Le pseudo ne peut contenir que des lettres, chiffres, tirets et underscores.',
        ]);

        $user->update([
            'login'        => $request->login,
            'phone_number' => $request->phone_number,
        ]);

        return redirect()->route('profile')
            ->with('success', 'Profil mis à jour avec succès !');
    }

    /**
     * Supprime le compte utilisateur et toutes ses annonces.
     * Demande confirmation avec le mot de passe.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ], [
            'password.required'         => 'Le mot de passe est requis pour supprimer le compte.',
            'password.current_password' => 'Le mot de passe est incorrect.',
        ]);

        $user = Auth::user();

        // Suppression des photos des annonces
        foreach ($user->ads as $ad) {
            if ($ad->photo) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($ad->photo);
            }
        }

        Auth::logout();
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')
            ->with('success', 'Votre compte a été supprimé.');
    }
}
