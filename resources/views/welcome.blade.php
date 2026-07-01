@extends('layouts.app')

@section('title', 'Accueil')
@section('meta_description', 'Trouvez les meilleures petites annonces : électronique, immobilier, automobile, vêtements et plus encore.')

@section('content')

{{-- ===== HERO SECTION ===== --}}
<section class="hero" aria-label="Section principale">
    <div class="container">
        <div class="hero-content">
            <h1 class="hero-title">Achetez & Vendez <span class="hero-highlight">Gratuitement</span></h1>
            <p class="hero-subtitle">Des milliers d'annonces partout en Côte d'Ivoire. Trouvez ce que vous cherchez ou publiez votre annonce en 2 minutes.</p>
            <div class="hero-actions">
                @guest
                    <a href="{{ route('register') }}" class="btn btn-primary btn-lg" id="hero-register">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                        Publier gratuitement
                    </a>
                    <a href="{{ route('login') }}" class="btn btn-outline btn-lg" id="hero-login">Se connecter</a>
                @else
                    <a href="{{ route('ads.create') }}" class="btn btn-primary btn-lg" id="hero-post">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                        Publier une annonce
                    </a>
                @endguest
            </div>
        </div>
        <div class="hero-stats">
            <div class="hero-stat">
                <span class="stat-number">{{ \App\Models\Ad::count() }}</span>
                <span class="stat-label">Annonces actives</span>
            </div>
            <div class="hero-stat">
                <span class="stat-number">{{ \App\Models\User::count() }}</span>
                <span class="stat-label">Membres</span>
            </div>
            <div class="hero-stat">
                <span class="stat-number">{{ count(\App\Models\Ad::CATEGORIES) }}</span>
                <span class="stat-label">Catégories</span>
            </div>
        </div>
    </div>
</section>

{{-- ===== MAIN CONTENT ===== --}}
<div class="page-container">
    <div class="container">
        <div class="content-grid">

            {{-- ===== SIDEBAR FILTRES ===== --}}
            <aside class="filters-sidebar" aria-label="Filtres">
                <form action="{{ route('home') }}" method="GET" id="filter-form">
                    @if(request('search'))
                        <input type="hidden" name="search" value="{{ request('search') }}">
                    @endif

                    <div class="filter-card">
                        <div class="filter-header">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
                            <h2>Filtrer par</h2>
                        </div>

                        {{-- Catégorie --}}
                        <div class="filter-group">
                            <label class="filter-label" for="filter-category">Catégorie</label>
                            <select name="category" id="filter-category" class="filter-select" onchange="this.form.submit()">
                                <option value="">Toutes les catégories</option>
                                @foreach(\App\Models\Ad::CATEGORIES as $cat)
                                    <option value="{{ $cat }}" {{ request('category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Localisation --}}
                        <div class="filter-group">
                            <label class="filter-label" for="filter-location">Localisation</label>
                            <input type="text" name="location" id="filter-location" value="{{ request('location') }}" placeholder="Ex: Abidjan" class="filter-input">
                        </div>

                        {{-- Prix --}}
                        <div class="filter-group">
                            <label class="filter-label">Gamme de prix (FCFA)</label>
                            <div class="price-range-inputs">
                                <input type="number" name="price_min" value="{{ request('price_min') }}" placeholder="Min" class="filter-input filter-price" min="0" id="filter-price-min">
                                <span class="price-separator">—</span>
                                <input type="number" name="price_max" value="{{ request('price_max') }}" placeholder="Max" class="filter-input filter-price" min="0" id="filter-price-max">
                            </div>
                        </div>

                        {{-- État --}}
                        <div class="filter-group">
                            <label class="filter-label">État</label>
                            <div class="condition-pills">
                                @foreach(\App\Models\Ad::CONDITIONS as $value => $label)
                                    <label class="condition-pill {{ request('condition') === $value ? 'active' : '' }}">
                                        <input type="radio" name="condition" value="{{ $value }}" {{ request('condition') === $value ? 'checked' : '' }} onchange="this.form.submit()">
                                        {{ $label }}
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <div class="filter-actions">
                            <button type="submit" class="btn btn-primary btn-block" id="apply-filters">Appliquer</button>
                            @if(request()->hasAny(['search', 'category', 'location', 'price_min', 'price_max', 'condition']))
                                <a href="{{ route('home') }}" class="btn btn-outline btn-block" id="clear-filters">Effacer les filtres</a>
                            @endif
                        </div>
                    </div>
                </form>
            </aside>

            {{-- ===== SECTION ANNONCES ===== --}}
            <section class="ads-section" aria-label="Annonces">

                {{-- En-tête résultats --}}
                <div class="results-header">
                    <div class="results-info">
                        <h2 class="results-title">
                            @if(request('search'))
                                Résultats pour "<strong>{{ request('search') }}</strong>"
                            @elseif(request('category'))
                                {{ request('category') }}
                            @else
                                Toutes les annonces
                            @endif
                        </h2>
                        <span class="results-count">{{ $ads->total() }} annonce{{ $ads->total() > 1 ? 's' : '' }}</span>
                    </div>

                    {{-- Filtres actifs --}}
                    @if(array_filter($filters))
                        <div class="active-filters">
                            @foreach($filters as $key => $value)
                                @if($value)
                                    <span class="filter-tag">
                                        {{ $value }}
                                        <a href="{{ route('home', array_merge(request()->except($key))) }}" aria-label="Supprimer ce filtre">×</a>
                                    </span>
                                @endif
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- Liste des annonces --}}
                @if($ads->isEmpty())
                    <div class="empty-state" id="empty-ads">
                        <div class="empty-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                        </div>
                        <h3>Aucune annonce trouvée</h3>
                        <p>Essayez de modifier vos filtres ou votre recherche.</p>
                        <a href="{{ route('home') }}" class="btn btn-primary" id="empty-reset">Voir toutes les annonces</a>
                    </div>
                @else
                    <div class="ads-grid">
                        @foreach($ads as $ad)
                            <article class="ad-card" id="ad-{{ $ad->id }}">
                                <a href="{{ route('ads.show', $ad) }}" class="ad-card-link" aria-label="{{ $ad->title }}">
                                    <div class="ad-image-wrap">
                                        <img
                                            src="{{ $ad->photo_url }}"
                                            alt="{{ $ad->title }}"
                                            class="ad-image"
                                            loading="lazy"
                                        >
                                        <span class="ad-condition-badge ad-condition-{{ $ad->condition }}">{{ $ad->condition_label }}</span>
                                    </div>
                                    <div class="ad-body">
                                        <div class="ad-meta">
                                            <span class="ad-category-tag">{{ $ad->category }}</span>
                                            <time class="ad-date" datetime="{{ $ad->created_at->toDateString() }}">
                                                {{ $ad->created_at->diffForHumans() }}
                                            </time>
                                        </div>
                                        <h3 class="ad-title">{{ Str::limit($ad->title, 60) }}</h3>
                                        <p class="ad-desc">{{ Str::limit($ad->description, 100) }}</p>
                                        <div class="ad-footer">
                                            <span class="ad-price">{{ number_format($ad->price, 0, ',', ' ') }} FCFA</span>
                                            <span class="ad-location">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                                                {{ $ad->location }}
                                            </span>
                                        </div>
                                        <div class="ad-seller-info">
                                            <span class="ad-seller-avatar">{{ strtoupper(substr($ad->user->login, 0, 1)) }}</span>
                                            <span class="ad-seller-name">{{ $ad->user->login }}</span>
                                        </div>
                                    </div>
                                </a>
                            </article>
                        @endforeach
                    </div>

                    {{-- Pagination --}}
                    @if($ads->hasPages())
                        <div class="pagination-wrap">
                            {{ $ads->links('partials.pagination') }}
                        </div>
                    @endif
                @endif

            </section>
        </div>
    </div>
</div>
@endsection