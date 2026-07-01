@extends('layouts.app')

@section('title', 'Connexion')
@section('meta_description', 'Connectez-vous à votre compte FreeAds.')

@section('content')
<div class="auth-page">
    <div class="auth-container">

        <div class="auth-card">
            <div class="auth-header">
                <div class="auth-logo">
                    <img src="{{ asset('images/logofreeads.png') }}" alt="FreeAds" class="auth-logo-img">
                </div>
                <h1 class="auth-title">Bon retour !</h1>
                <p class="auth-subtitle">Connectez-vous pour accéder à votre compte</p>
            </div>

            <form method="POST" action="{{ route('login') }}" class="auth-form" novalidate id="login-form">
                @csrf

                <div class="form-group">
                    <label for="email" class="form-label">Email ou Pseudo</label>
                    <div class="input-wrap">
                        <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.58-7 8-7s8 3 8 7"/></svg>
                        <input
                            type="text"
                            id="email"
                            name="email"
                            value="{{ old('email') }}"
                            class="form-input {{ $errors->has('email') ? 'input-error' : '' }}"
                            placeholder="votre@email.com ou pseudo"
                            autocomplete="email"
                            required
                        >
                    </div>
                    @error('email')
                        <p class="form-error" role="alert">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <div class="form-label-row">
                        <label for="password" class="form-label">Mot de passe</label>
                    </div>
                    <div class="input-wrap">
                        <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            class="form-input {{ $errors->has('password') ? 'input-error' : '' }}"
                            placeholder="••••••••"
                            autocomplete="current-password"
                            required
                        >
                        <button type="button" class="input-toggle-pw" aria-label="Afficher le mot de passe" onclick="togglePassword('password', this)">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                        </button>
                    </div>
                    @error('password')
                        <p class="form-error" role="alert">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-check">
                    <input type="checkbox" id="remember" name="remember" class="form-checkbox">
                    <label for="remember" class="form-check-label">Se souvenir de moi</label>
                </div>

                <button type="submit" class="btn btn-primary btn-block btn-lg" id="login-submit">
                    Se connecter
                </button>
            </form>

            <div class="auth-footer">
                <p>Pas encore de compte ? <a href="{{ route('register') }}" class="auth-link">S'inscrire gratuitement</a></p>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
function togglePassword(fieldId, btn) {
    const field = document.getElementById(fieldId);
    const isHidden = field.type === 'password';
    field.type = isHidden ? 'text' : 'password';
    btn.setAttribute('aria-label', isHidden ? 'Masquer le mot de passe' : 'Afficher le mot de passe');
}
</script>
@endpush
