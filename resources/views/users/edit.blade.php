@extends('layouts.app')

@section('title', 'Modifier mon profil')

@section('content')
<div class="page-container">
    <div class="container">
        <div class="form-page-layout form-page-sm">

            <div class="form-page-header">
                <nav class="breadcrumb" aria-label="Fil d'Ariane">
                    <a href="{{ route('home') }}">Accueil</a>
                    <span>›</span>
                    <a href="{{ route('profile') }}">Mon Profil</a>
                    <span>›</span>
                    <span>Modifier</span>
                </nav>
                <h1 class="page-title">Modifier mon profil</h1>
            </div>

            <div class="form-card">
                <form method="POST" action="{{ route('profile.update') }}" class="auth-form" id="edit-profile-form" novalidate>
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label for="login" class="form-label">Pseudo <span class="required">*</span></label>
                        <div class="input-wrap">
                            <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.58-7 8-7s8 3 8 7"/></svg>
                            <input type="text" id="login" name="login" value="{{ old('login', $user->login) }}" class="form-input {{ $errors->has('login') ? 'input-error' : '' }}" required minlength="3" maxlength="50">
                        </div>
                        @error('login') <p class="form-error">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-group">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" id="email" value="{{ $user->email }}" class="form-input" disabled>
                        <p class="form-hint">L'email ne peut pas être modifié pour des raisons de sécurité.</p>
                    </div>

                    <div class="form-group">
                        <label for="phone_number" class="form-label">Numéro de téléphone</label>
                        <div class="input-wrap">
                            <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12 19.79 19.79 0 0 1 1.65 3.12 2 2 0 0 1 3.62 1h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.6a16 16 0 0 0 6.29 6.29l.96-.86a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                            <input type="tel" id="phone_number" name="phone_number" value="{{ old('phone_number', $user->phone_number) }}" class="form-input {{ $errors->has('phone_number') ? 'input-error' : '' }}" placeholder="+225 07 00 00 00 00" maxlength="20">
                        </div>
                        @error('phone_number') <p class="form-error">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-actions">
                        <a href="{{ route('profile') }}" class="btn btn-outline" id="cancel-profile-edit">Annuler</a>
                        <button type="submit" class="btn btn-primary" id="save-profile">Enregistrer</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
@endsection
