@extends('layouts.app')

@section('title', 'Mon Profil — ' . $user->login)

@section('content')
@php
    $userName = $user->login ?? $user->name ?? $user->email;
    $initial = strtoupper(substr($userName, 0, 1));
    $adsCount = $user->ads->count();
    $newAds = $user->ads->where('condition', 'new')->count();
    $usedAds = $user->ads->where('condition', 'used')->count();
@endphp

<div class="page-container">
    <div class="container">
        <div class="profile-page">
            <div class="profile-top-grid">
                <section class="profile-card">
                    <div class="profile-card-head">
                        <div class="profile-avatar">{{ $initial }}</div>
                        <div class="profile-details">
                            <span class="profile-tag">Profil utilisateur</span>
                            <h1 class="profile-title">{{ $userName }}</h1>
                            <p class="profile-email">{{ $user->email }}</p>
                            @if($user->phone_number)
                                <p class="profile-contact">{{ $user->phone_number }}</p>
                            @endif
                            <p class="profile-meta">Membre depuis {{ $user->created_at->format('d/m/Y') }}</p>
                        </div>
                    </div>

                    @if(!$user->hasVerifiedEmail())
                        <div class="profile-notice">
                            <svg class="icon-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                            <div>
                                <strong>Email non vérifié.</strong>
                                <p>Vérifiez votre adresse pour publier et gérer vos annonces en toute confiance.</p>
                            </div>
                        </div>
                    @endif

                    <div class="profile-card-actions">
                        <a href="{{ route('profile.edit') }}" class="btn btn-outline btn-pill">Modifier le profil</a>
                        <a href="{{ route('ads.create') }}" class="btn btn-primary btn-pill">Publier une annonce</a>
                    </div>
                </section>

                <aside class="profile-summary-card">
                    <div class="stats-grid">
                        <div class="stat-card">
                            <span class="stat-value">{{ $adsCount }}</span>
                            <span class="stat-label">Annonces publiées</span>
                        </div>
                        <div class="stat-card">
                            <span class="stat-value">{{ $newAds }}</span>
                            <span class="stat-label">Neuves</span>
                        </div>
                        <div class="stat-card">
                            <span class="stat-value">{{ $usedAds }}</span>
                            <span class="stat-label">Occasions</span>
                        </div>
                    </div>
                </aside>
            </div>

            <section class="profile-ads-section">
                <div class="section-header">
                    <div>
                        <p class="section-pretitle">Mes annonces</p>
                        <h2>Gérez vos annonces</h2>
                    </div>
                    <a href="{{ route('ads.create') }}" class="btn btn-primary btn-pill">Publier une annonce</a>
                </div>

                @if($user->ads->isEmpty())
                    <div class="empty-state empty-state-profile">
                        <div class="empty-icon">
                            <svg class="icon-xl" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                        </div>
                        <h3>Aucune annonce publiée</h3>
                        <p>Publiez votre première annonce gratuitement et atteignez des acheteurs locaux.</p>
                        <a href="{{ route('ads.create') }}" class="btn btn-primary btn-pill">Publier une annonce</a>
                    </div>
                @else
                    <div class="my-ads-grid">
                        @foreach($user->ads->sortByDesc('created_at') as $ad)
                            <article class="my-ad-card" id="my-ad-{{ $ad->id }}">
                                <div class="my-ad-image">
                                    <img src="{{ $ad->photo_url }}" alt="{{ $ad->title }}" loading="lazy">
                                </div>
                                <div class="my-ad-content">
                                    <div class="my-ad-badges">
                                        <span class="ad-category-tag">{{ $ad->category }}</span>
                                        <span class="ad-condition-badge ad-condition-{{ $ad->condition }}">{{ $ad->condition_label }}</span>
                                    </div>
                                    <h3 class="my-ad-title"><a href="{{ route('ads.show', $ad) }}">{{ $ad->title }}</a></h3>
                                    <div class="my-ad-meta">
                                        <span class="ad-price">{{ number_format($ad->price, 0, ',', ' ') }} FCFA</span>
                                        <span class="ad-location">
                                            <svg class="icon-xs" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                                            {{ $ad->location }}
                                        </span>
                                    </div>
                                    <div class="my-ad-actions">
                                        <a href="{{ route('ads.edit', $ad) }}" class="btn btn-outline btn-sm">Modifier</a>
                                        <form method="POST" action="{{ route('ads.destroy', $ad) }}" onsubmit="return confirm('Supprimer cette annonce ?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">Supprimer</button>
                                        </form>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>
                @endif
            </section>

            <section class="danger-card">
                <div class="danger-card-inner">
                    <div>
                        <h2>Zone de danger</h2>
                        <p>Supprimer votre compte entraînera la suppression permanente de toutes vos annonces et de vos données.</p>
                    </div>
                    <button type="button" class="btn btn-danger btn-pill btn-danger-limited" onclick="document.getElementById('delete-account-modal').style.display='flex'">Supprimer mon compte</button>
                </div>
            </section>
        </div>
    </div>
</div>

{{-- Modal suppression compte --}}
<div id="delete-account-modal" class="modal-overlay" style="display:none">
    <div class="modal-box" role="dialog" aria-modal="true" aria-labelledby="modal-title">
        <h3 id="modal-title" class="modal-title">Supprimer mon compte</h3>
        <p class="modal-text">Cette action est irréversible. Toutes vos annonces seront supprimées définitivement.</p>
        <form method="POST" action="{{ route('profile.destroy') }}" id="delete-account-form">
            @csrf
            @method('DELETE')
            <div class="form-group">
                <label for="delete-password" class="form-label">Confirmez avec votre mot de passe :</label>
                <input type="password" id="delete-password" name="password" class="form-input {{ $errors->has('password') ? 'input-error' : '' }}" required placeholder="Votre mot de passe">
                @error('password') <p class="form-error">{{ $message }}</p> @enderror
            </div>
            <div class="modal-actions">
                <button type="button" class="btn btn-outline" onclick="document.getElementById('delete-account-modal').style.display='none'">Annuler</button>
                <button type="submit" class="btn btn-danger" id="confirm-delete-account">Supprimer définitivement</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Ouvre le modal si erreur de mot de passe (après soumission)
@if($errors->has('password'))
    document.getElementById('delete-account-modal').style.display = 'flex';
@endif

// Ferme le modal en cliquant hors de la boîte
document.getElementById('delete-account-modal')?.addEventListener('click', function(e) {
    if (e.target === this) this.style.display = 'none';
});
</script>
@endpush
