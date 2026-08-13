@extends('layouts.app')

@section('title', 'Créer un compte')
@section('meta_description', 'Créez votre compte VORTEX ADS pour publier vos annonces.')

@section('content')
<div class="vortex-auth-page">
    <div class="vortex-auth-shell">
        <div class="vortex-auth-card">
            <div class="vortex-auth-header">
                <div class="vortex-auth-brand" aria-label="VORTEX ADS">
                    <span class="vortex-brand-word">VORTEX</span>
                    <span class="vortex-brand-sub">ADS</span>
                </div>
                <h1>Créez votre compte</h1>
                <p>Rejoignez VORTEX ADS pour vendre plus vite et trouver des offres de qualité.</p>
            </div>

            <form method="POST" action="{{ route('register') }}" class="vortex-auth-form" novalidate>
                @csrf

                <div class="vortex-field">
                    <label for="login">Pseudo</label>
                    <div class="vortex-input-wrap">
                        <input type="text" id="login" name="login" value="{{ old('login') }}" placeholder="votre_pseudo" autocomplete="username" required minlength="3" maxlength="50">
                    </div>
                    @error('login')
                        <span class="vortex-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="vortex-field">
                    <label for="email">Email</label>
                    <div class="vortex-input-wrap">
                        <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="votre@email.com" autocomplete="email" required>
                    </div>
                    @error('email')
                        <span class="vortex-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="vortex-field">
                    <label for="phone_number">Téléphone</label>
                    <div class="vortex-input-wrap">
                        <input type="tel" id="phone_number" name="phone_number" value="{{ old('phone_number') }}" placeholder="+225 07 00 00 00 00" autocomplete="tel">
                    </div>
                    @error('phone_number')
                        <span class="vortex-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="vortex-field">
                    <label for="password">Mot de passe</label>
                    <div class="vortex-input-wrap password-wrap">
                        <input type="password" id="password" name="password" placeholder="Min. 8 caractères" autocomplete="new-password" required minlength="8">
                        <button type="button" class="vortex-toggle-password" aria-label="Afficher le mot de passe" onclick="togglePassword('password', this)">Afficher</button>
                    </div>
                    @error('password')
                        <span class="vortex-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="vortex-field">
                    <label for="password_confirmation">Confirmer le mot de passe</label>
                    <div class="vortex-input-wrap password-wrap">
                        <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Répétez votre mot de passe" autocomplete="new-password" required>
                        <button type="button" class="vortex-toggle-password" aria-label="Afficher le mot de passe" onclick="togglePassword('password_confirmation', this)">Afficher</button>
                    </div>
                </div>

                <button type="submit" class="vortex-btn neon full">Créer mon compte</button>
            </form>

            <div class="vortex-auth-footer">
                <span>Déjà inscrit ?</span>
                <a href="{{ route('login') }}">Se connecter</a>
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
