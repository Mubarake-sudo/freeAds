<?php

use App\Http\Controllers\AdController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\UserController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Routes publiques
|--------------------------------------------------------------------------
*/

// Homepage — liste des annonces avec recherche et filtres
Route::get('/', [AdController::class, 'index'])->name('home');

// Détail d'une annonce (accessible à tous)
Route::get('/annonces/{ad}', [AdController::class, 'show'])->name('ads.show');

/*
|--------------------------------------------------------------------------
| Routes d'authentification (invités uniquement)
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    // Inscription
    Route::get('/inscription', [RegisterController::class, 'create'])->name('register');
    Route::post('/inscription', [RegisterController::class, 'store']);

    // Connexion
    Route::get('/connexion', [LoginController::class, 'create'])->name('login');
    Route::post('/connexion', [LoginController::class, 'store']);
});

// Déconnexion (utilisateur connecté)
Route::post('/deconnexion', [LoginController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

/*
|--------------------------------------------------------------------------
| Vérification email
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::get('/email/verify', function () {
        return view('auth.verify-email');
    })->name('verification.notice');

    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();
        return redirect()->route('home')->with('success', 'Email vérifié avec succès ! Vous pouvez maintenant publier des annonces.');
    })->middleware('signed')->name('verification.verify');

    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('success', 'Un nouvel email de vérification a été envoyé.');
    })->middleware('throttle:6,1')->name('verification.send');
});

/*
|--------------------------------------------------------------------------
| Routes protégées (authentifié + email vérifié)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified'])->group(function () {
    // Annonces — CRUD
    Route::get('/annonces/creer', [AdController::class, 'create'])->name('ads.create');
    Route::post('/annonces', [AdController::class, 'store'])->name('ads.store');
    Route::get('/annonces/{ad}/modifier', [AdController::class, 'edit'])->name('ads.edit');
    Route::put('/annonces/{ad}', [AdController::class, 'update'])->name('ads.update');
    Route::delete('/annonces/{ad}', [AdController::class, 'destroy'])->name('ads.destroy');

    // Profil utilisateur
    Route::get('/profil', [UserController::class, 'show'])->name('profile');
    Route::get('/profil/modifier', [UserController::class, 'edit'])->name('profile.edit');
    Route::put('/profil', [UserController::class, 'update'])->name('profile.update');
    Route::delete('/profil', [UserController::class, 'destroy'])->name('profile.destroy');
});
