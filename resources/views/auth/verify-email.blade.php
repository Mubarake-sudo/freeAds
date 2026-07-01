@extends('layouts.app')

@section('title', 'Vérifiez votre email')

@section('content')
<div class="auth-page">
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <div class="verify-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                </div>
                <h1 class="auth-title">Vérifiez votre email</h1>
                <p class="auth-subtitle">
                    Un email de confirmation a été envoyé à <strong>{{ auth()->user()->email }}</strong>.<br>
                    Cliquez sur le lien dans l'email pour activer votre compte.
                </p>
            </div>

            @if(session('success'))
                <div class="alert alert-success">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                    {{ session('success') }}
                </div>
            @endif

            <div class="verify-actions">
                <p class="verify-hint">Vous n'avez pas reçu l'email ?</p>
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <button type="submit" class="btn btn-primary btn-block" id="resend-verification">
                        Renvoyer l'email de vérification
                    </button>
                </form>
                <form method="POST" action="{{ route('logout') }}" class="mt-2">
                    @csrf
                    <button type="submit" class="btn btn-outline btn-block">Déconnexion</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
