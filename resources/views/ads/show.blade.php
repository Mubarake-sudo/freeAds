@extends('layouts.app')

@section('title', $ad->title)
@section('meta_description', Str::limit($ad->description, 160))

@section('content')
<div class="page-container">
    <div class="container">

        <nav class="breadcrumb" aria-label="Fil d'Ariane">
            <a href="{{ route('home') }}">Accueil</a>
            <span>›</span>
            <a href="{{ route('home', ['category' => $ad->category]) }}">{{ $ad->category }}</a>
            <span>›</span>
            <span>{{ Str::limit($ad->title, 40) }}</span>
        </nav>

        <div class="ad-detail-layout">

            {{-- Photo --}}
            <div class="ad-detail-image-col">
                <div class="ad-detail-image-wrap">
                    <img
                        src="{{ $ad->photo_url }}"
                        alt="{{ $ad->title }}"
                        class="ad-detail-image"
                    >
                    <span class="ad-condition-badge ad-condition-{{ $ad->condition }} ad-condition-lg">{{ $ad->condition_label }}</span>
                </div>
            </div>

            {{-- Infos --}}
            <div class="ad-detail-info-col">
                <div class="ad-detail-card">
                    <div class="ad-detail-header">
                        <span class="ad-category-tag">{{ $ad->category }}</span>
                        <time class="ad-date" datetime="{{ $ad->created_at->toDateString() }}">
                            Publié {{ $ad->created_at->diffForHumans() }}
                        </time>
                    </div>

                    <h1 class="ad-detail-title">{{ $ad->title }}</h1>
                    <div class="ad-detail-price">{{ number_format($ad->price, 0, ',', ' ') }} FCFA</div>

                    <div class="ad-detail-location">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                        {{ $ad->location }}
                    </div>

                    {{-- Actions propriétaire --}}
                    @auth
                        @if(auth()->id() === $ad->user_id)
                            <div class="ad-owner-actions">
                                <a href="{{ route('ads.edit', $ad) }}" class="btn btn-outline" id="edit-ad-{{ $ad->id }}">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                    Modifier
                                </a>
                                <form method="POST" action="{{ route('ads.destroy', $ad) }}" onsubmit="return confirm('Supprimer cette annonce définitivement ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" id="delete-ad-{{ $ad->id }}">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4h6v2"/></svg>
                                        Supprimer
                                    </button>
                                </form>
                            </div>
                        @endif
                    @endauth

                    {{-- Vendeur --}}
                    <div class="seller-card">
                        <div class="seller-avatar">{{ strtoupper(substr($ad->user->login, 0, 1)) }}</div>
                        <div class="seller-info">
                            <span class="seller-name">{{ $ad->user->login }}</span>
                            <span class="seller-since">Membre depuis {{ $ad->user->created_at->format('M Y') }}</span>
                            <span class="seller-ads-count">{{ $ad->user->ads()->count() }} annonce(s)</span>
                        </div>
                    </div>

                    @if($ad->user->phone_number)
                        <div class="contact-section">
                            <h3 class="contact-title">Contacter le vendeur</h3>
                            <a href="tel:{{ $ad->user->phone_number }}" class="btn btn-success btn-block btn-lg" id="contact-seller-{{ $ad->id }}">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12 19.79 19.79 0 0 1 1.65 3.12 2 2 0 0 1 3.62 1h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.6a16 16 0 0 0 6.29 6.29l.96-.86a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                                {{ $ad->user->phone_number }}
                            </a>
                        </div>
                    @else
                        @guest
                        <div class="contact-section">
                            <p class="contact-hint">
                                <a href="{{ route('login') }}" class="auth-link">Connectez-vous</a> pour voir les coordonnées du vendeur.
                            </p>
                        </div>
                        @endguest
                    @endif
                </div>
            </div>
        </div>

        {{-- Description --}}
        <div class="ad-description-card">
            <h2 class="ad-desc-title">Description</h2>
            <div class="ad-desc-content">
                {!! nl2br(e($ad->description)) !!}
            </div>
        </div>

        {{-- Annonces similaires --}}
        @php
            $similar = \App\Models\Ad::where('category', $ad->category)
                ->where('id', '!=', $ad->id)
                ->latest()
                ->take(4)
                ->get();
        @endphp

        @if($similar->isNotEmpty())
        <div class="similar-ads">
            <h2 class="section-title">Annonces similaires</h2>
            <div class="ads-row">
                @foreach($similar as $s)
                    <article class="ad-card-sm" id="similar-{{ $s->id }}">
                        <a href="{{ route('ads.show', $s) }}" class="ad-card-link">
                            <div class="ad-image-wrap">
                                <img src="{{ $s->photo_url }}" alt="{{ $s->title }}" class="ad-image" loading="lazy">
                            </div>
                            <div class="ad-body">
                                <h3 class="ad-title">{{ Str::limit($s->title, 50) }}</h3>
                                <div class="ad-footer">
                                    <span class="ad-price">{{ number_format($s->price, 0, ',', ' ') }} FCFA</span>
                                    <span class="ad-location">{{ $s->location }}</span>
                                </div>
                            </div>
                        </a>
                    </article>
                @endforeach
            </div>
        </div>
        @endif

    </div>
</div>
@endsection
