@extends('layouts.app')

@section('title', 'Connexion')
@section('meta_description', 'Connectez-vous à votre compte VORTEX ADS.')

@section('content')
<div class="vortex-auth-page">
    <div class="vortex-auth-shell">
        <div class="vortex-auth-card">
            <div class="vortex-auth-header">
                <div class="vortex-auth-brand" aria-label="VORTEX ADS">
                    <span class="vortex-brand-word">VORTEX</span>
                    <span class="vortex-brand-sub">ADS</span>
                </div>
                <h1>Bon retour</h1>
                <p>Connectez-vous pour consulter vos annonces et publier votre prochain bien.</p>
            </div>

            <form method="POST" action="{{ route('login') }}" class="vortex-auth-form" novalidate>
                @csrf

                <div class="vortex-field">
                    <label for="email">Email ou pseudo</label>
                    <div class="vortex-input-wrap">
                        <input type="text" id="email" name="email" value="{{ old('email') }}" placeholder="votre@email.com ou pseudo" autocomplete="email" required>
                    </div>
                    @error('email')
                        <span class="vortex-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="vortex-field">
                    <label for="password">Mot de passe</label>
                    <div class="vortex-input-wrap password-wrap">
                        <input type="password" id="password" name="password" placeholder="••••••••" autocomplete="current-password" required>
                        <button type="button" class="vortex-toggle-password" aria-label="Afficher le mot de passe" onclick="togglePassword('password', this)">Afficher</button>
                    </div>
                    @error('password')
                        <span class="vortex-error">{{ $message }}</span>
                    @enderror
                </div>

                <label class="vortex-check-row">
                    <input type="checkbox" name="remember" id="remember">
                    <span>Se souvenir de moi</span>
                </label>

                <button type="submit" class="vortex-btn neon full">Se connecter</button>
            </form>

            <div class="vortex-auth-footer">
                <span>Pas encore de compte ?</span>
                <a href="{{ route('register') }}">Créer un compte</a>
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
    btn.textContent = isHidden ? 'Masquer' : 'Afficher';
    btn.setAttribute('aria-label', isHidden ? 'Masquer le mot de passe' : 'Afficher le mot de passe');
}
</script>
@endpush
