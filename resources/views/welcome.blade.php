@extends('layouts.app')

@section('title', 'Accueil')
@section('meta_description', 'VORTEX ADS — Trouvez des biens ou services de qualité dans votre ville.')

@section('content')
<div class="vortex-page">
    <div class="vortex-hero">
        <div class="hero-text-block">
            <div class="hero-kicker">Marché premium / Abidjan</div>
            <h1>VORTEX <span>ADS</span></h1>
            <p>Une plateforme de petites annonces premium pensée pour acheter, vendre et découvrir des produits de qualité, rapidement et avec confiance.</p>
            <div class="hero-actions">
                <a href="{{ route('home') }}#annonces" class="vortex-btn neon">Découvrir</a>
                <a href="{{ route('ads.create') }}" class="vortex-btn ghost">Poster</a>
            </div>
        </div>

        <div class="hero-visual" aria-hidden="true">
            <div class="portrait-frame"></div>
        </div>
    </div>

    <form action="{{ route('home') }}" method="GET" class="vortex-search-wrap" role="search">
        <div class="vortex-search-row">
            <div class="search-box">
                <span class="search-icon">⌕</span>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Que recherchez-vous ?" aria-label="Recherche">
            </div>
            <button type="submit" class="vortex-btn neon compact">Rechercher</button>
        </div>
    </form>

    <div class="vortex-layout">
        <aside class="vortex-sidebar">
            <form action="{{ route('home') }}" method="GET" class="vortex-filter-form">
                <div class="sidebar-header">Filtrer par</div>

                <label for="category">Catégorie</label>
                <select id="category" name="category">
                    <option value="">Toutes</option>
                    @foreach(
                        App\Models\Ad::CATEGORIES as $option
                    )
                        <option value="{{ $option }}" {{ request('category') === $option ? 'selected' : '' }}>{{ $option }}</option>
                    @endforeach
                </select>

                <label for="location">Ville</label>
                <input id="location" type="text" name="location" value="{{ request('location') }}" placeholder="Abidjan">

                <label>Prix</label>
                <div class="price-inputs">
                    <input type="number" name="price_min" value="{{ request('price_min') }}" placeholder="0" min="0">
                    <input type="number" name="price_max" value="{{ request('price_max') }}" placeholder="1200" min="0">
                </div>

                <label for="condition">État</label>
                <select id="condition" name="condition">
                    <option value="">Tous</option>
                    <option value="new" {{ request('condition') === 'new' ? 'selected' : '' }}>Neuf</option>
                    <option value="good" {{ request('condition') === 'good' ? 'selected' : '' }}>Bon état</option>
                    <option value="used" {{ request('condition') === 'used' ? 'selected' : '' }}>Occasion</option>
                </select>

                <div class="filter-actions">
                    <button type="submit" class="vortex-btn neon small">Appliquer</button>
                    <a href="{{ route('home') }}" class="vortex-link reset-link">Réinitialiser</a>
                </div>
            </form>
        </aside>

        <section class="vortex-ads" id="annonces">
            @foreach($ads as $ad)
                <article class="vortex-card">
                    <a href="{{ route('ads.show', $ad) }}" class="vortex-card-link">
                        <div class="vortex-card-image">
                            <img src="{{ $ad->photo_url }}" alt="{{ $ad->title }}">
                            <span class="vortex-price">{{ number_format((float) $ad->price, 0, ',', ' ') }} FCFA</span>
                        </div>
                        <div class="vortex-card-copy">
                            <div class="meta-row">
                                <span>{{ strtoupper($ad->category) }}</span>
                                <span>PAR {{ strtoupper($ad->user->login ?? 'vendeur') }}</span>
                            </div>
                            <h3>{{ $ad->title }}</h3>
                        </div>
                    </a>
                </article>
            @endforeach
        </section>
    </div>

    @if($ads->hasPages())
    <div class="vortex-pagination">
        {{ $ads->links('pagination::simple-tailwind') }}
    </div>
    @endif
</div>
@endsection