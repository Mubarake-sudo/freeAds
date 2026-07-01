@extends('layouts.app')

@section('title', 'Mon Profil — ' . $user->login)

@section('content')
<div class="page-container">
    <div class="container">

        <div class="profile-layout">

            {{-- Sidebar profil --}}
            <aside class="profile-sidebar">
                <div class="profile-card">
                    <div class="profile-avatar">{{ strtoupper(substr($user->login, 0, 1)) }}</div>
                    <h2 class="profile-name">{{ $user->login }}</h2>
                    <p class="profile-email">{{ $user->email }}</p>
                    @if($user->phone_number)
                        <p class="profile-phone">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12 19.79 19.79 0 0 1 1.65 3.12 2 2 0 0 1 3.62 1h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.6a16 16 0 0 0 6.29 6.29l.96-.86a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                            {{ $user->phone_number }}
                        </p>
                    @endif
                    <p class="profile-since">Membre depuis {{ $user->created_at->format('d/m/Y') }}</p>

                    @if(!$user->hasVerifiedEmail())
                        <div class="alert alert-warning mt-2">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                            Email non vérifié.
                            <form method="POST" action="{{ route('verification.send') }}" class="inline">
                                @csrf
                                <button type="submit" class="link-btn">Renvoyer l'email</button>
                            </form>
                        </div>
                    @endif

                    <div class="profile-actions">
                        <a href="{{ route('profile.edit') }}" class="btn btn-outline btn-block" id="edit-profile">Modifier le profil</a>
                    </div>

                    <div class="profile-stats">
                        <div class="profile-stat">
                            <span class="stat-num">{{ $user->ads->count() }}</span>
                            <span class="stat-lbl">Annonces</span>
                        </div>
                        <div class="profile-stat">
                            <span class="stat-num">{{ $user->ads->where('condition', 'new')->count() }}</span>
                            <span class="stat-lbl">Neufs</span>
                        </div>
                    </div>
                </div>

                {{-- Suppression du compte --}}
                <div class="danger-zone">
                    <h3>Zone de danger</h3>
                    <button type="button" class="btn btn-danger btn-block btn-sm" onclick="document.getElementById('delete-account-modal').style.display='flex'" id="delete-account-btn">
                        Supprimer mon compte
                    </button>
                </div>
            </aside>

            {{-- Annonces de l'utilisateur --}}
            <div class="profile-main">
                <div class="profile-main-header">
                    <h2>Mes annonces</h2>
                    <a href="{{ route('ads.create') }}" class="btn btn-primary" id="profile-post-ad">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                        Publier
                    </a>
                </div>

                @if($user->ads->isEmpty())
                    <div class="empty-state">
                        <div class="empty-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                        </div>
                        <h3>Aucune annonce publiée</h3>
                        <p>Publiez votre première annonce gratuitement !</p>
                        <a href="{{ route('ads.create') }}" class="btn btn-primary">Publier une annonce</a>
                    </div>
                @else
                    <div class="my-ads-list">
                        @foreach($user->ads->sortByDesc('created_at') as $ad)
                            <div class="my-ad-card" id="my-ad-{{ $ad->id }}">
                                <div class="my-ad-image">
                                    <img src="{{ $ad->photo_url }}" alt="{{ $ad->title }}" loading="lazy">
                                </div>
                                <div class="my-ad-content">
                                    <div class="my-ad-badges">
                                        <span class="ad-category-tag">{{ $ad->category }}</span>
                                        <span class="ad-condition-badge ad-condition-{{ $ad->condition }}">{{ $ad->condition_label }}</span>
                                    </div>
                                    <h3 class="my-ad-title">
                                        <a href="{{ route('ads.show', $ad) }}">{{ $ad->title }}</a>
                                    </h3>
                                    <div class="my-ad-meta">
                                        <span class="ad-price">{{ number_format($ad->price, 0, ',', ' ') }} FCFA</span>
                                        <span class="ad-location">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                                            {{ $ad->location }}
                                        </span>
                                        <time class="ad-date">{{ $ad->created_at->diffForHumans() }}</time>
                                    </div>
                                </div>
                                <div class="my-ad-actions">
                                    <a href="{{ route('ads.edit', $ad) }}" class="btn btn-outline btn-sm" id="edit-my-ad-{{ $ad->id }}">Modifier</a>
                                    <form method="POST" action="{{ route('ads.destroy', $ad) }}" onsubmit="return confirm('Supprimer cette annonce ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" id="delete-my-ad-{{ $ad->id }}">Supprimer</button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
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
