@extends('layouts.app')

@section('title', 'Créer un compte')
@section('meta_description', 'Créez votre compte FreeAds gratuitement et commencez à publier vos annonces.')

@section('content')
<div class="auth-page">
    <div class="auth-container">

        <div class="auth-card">
            <div class="auth-header">
                <div class="auth-logo">
                    <img src="{{ asset('images/logofreeads.png') }}" alt="FreeAds" class="auth-logo-img">
                </div>
                <h1 class="auth-title">Créer un compte</h1>
                <p class="auth-subtitle">Rejoignez des milliers d'utilisateurs — c'est gratuit !</p>
            </div>

            <form method="POST" action="{{ route('register') }}" class="auth-form" novalidate id="register-form">
                @csrf

                <div class="form-group">
                    <label for="login" class="form-label">Pseudo <span class="required">*</span></label>
                    <div class="input-wrap">
                        <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.58-7 8-7s8 3 8 7"/></svg>
                        <input
                            type="text"
                            id="login"
                            name="login"
                            value="{{ old('login') }}"
                            class="form-input {{ $errors->has('login') ? 'input-error' : '' }}"
                            placeholder="votre_pseudo"
                            autocomplete="username"
                            required
                            minlength="3"
                            maxlength="50"
                        >
                    </div>
                    <p class="form-hint">Lettres, chiffres, tirets et underscores uniquement. Min. 3 caractères.</p>
                    @error('login')
                        <p class="form-error" role="alert">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email" class="form-label">Email <span class="required">*</span></label>
                    <div class="input-wrap">
                        <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email') }}"
                            class="form-input {{ $errors->has('email') ? 'input-error' : '' }}"
                            placeholder="votre@email.com"
                            autocomplete="email"
                            required
                        >
                    </div>
                    @error('email')
                        <p class="form-error" role="alert">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="phone_number" class="form-label">Numéro de téléphone <span class="optional">(facultatif)</span></label>
                    <div class="input-wrap">
                        <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12 19.79 19.79 0 0 1 1.65 3.12 2 2 0 0 1 3.62 1h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.6a16 16 0 0 0 6.29 6.29l.96-.86a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                        <input
                            type="tel"
                            id="phone_number"
                            name="phone_number"
                            value="{{ old('phone_number') }}"
                            class="form-input {{ $errors->has('phone_number') ? 'input-error' : '' }}"
                            placeholder="+225 07 00 00 00 00"
                            autocomplete="tel"
                        >
                    </div>
                    @error('phone_number')
                        <p class="form-error" role="alert">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Mot de passe <span class="required">*</span></label>
                    <div class="input-wrap">
                        <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            class="form-input {{ $errors->has('password') ? 'input-error' : '' }}"
                            placeholder="Min. 8 caractères"
                            autocomplete="new-password"
                            required
                            minlength="8"
                        >
                        <button type="button" class="input-toggle-pw" aria-label="Afficher le mot de passe" onclick="togglePassword('password', this)">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                        </button>
                    </div>
                    @error('password')
                        <p class="form-error" role="alert">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password_confirmation" class="form-label">Confirmer le mot de passe <span class="required">*</span></label>
                    <div class="input-wrap">
                        <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                        <input
                            type="password"
                            id="password_confirmation"
                            name="password_confirmation"
                            class="form-input"
                            placeholder="Répétez votre mot de passe"
                            autocomplete="new-password"
                            required
                        >
                        <button type="button" class="input-toggle-pw" aria-label="Afficher le mot de passe" onclick="togglePassword('password_confirmation', this)">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                        </button>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-block btn-lg" id="register-submit">
                    Créer mon compte
                </button>
            </form>

            <div class="auth-footer">
                <p>Déjà inscrit ? <a href="{{ route('login') }}" class="auth-link">Se connecter</a></p>
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
