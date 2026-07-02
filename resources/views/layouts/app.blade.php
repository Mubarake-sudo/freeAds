<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'FreeAds') — Petites Annonces Gratuites</title>
    <meta name="description" content="@yield('meta_description', 'FreeAds — La plateforme de petites annonces gratuites. Achetez, vendez et trouvez tout ce dont vous avez besoin.')">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body>

    {{-- ===== NAVBAR ===== --}}
    <header class="navbar" role="banner">
        <div class="container navbar-inner">
            <a href="{{ route('home') }}" class="navbar-brand">
                <img src="{{ asset('images/logofreeads.png') }}" alt="Logo FreeAds" class="navbar-logo">
                <span class="navbar-brand-text">FreeAds</span>
            </a>

            <form action="{{ route('home') }}" method="GET" class="navbar-search" role="search">
                @if(request('category'))     <input type="hidden" name="category" value="{{ request('category') }}"> @endif
                @if(request('location'))     <input type="hidden" name="location" value="{{ request('location') }}"> @endif
                @if(request('price_min'))    <input type="hidden" name="price_min" value="{{ request('price_min') }}"> @endif
                @if(request('price_max'))    <input type="hidden" name="price_max" value="{{ request('price_max') }}"> @endif
                @if(request('condition'))    <input type="hidden" name="condition" value="{{ request('condition') }}"> @endif
                <div class="search-wrap">
                    <svg class="search-icon-nav" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Que recherchez-vous ?" class="search-input-nav" aria-label="Recherche">
                    <button type="submit" class="search-btn-nav">Rechercher</button>
                </div>
            </form>

            <div class="navbar-actions" aria-label="Navigation principale">
                @auth
                    @php
                        $userName = auth()->user()->login ?? auth()->user()->name ?? auth()->user()->email;
                        $initial = strtoupper(substr($userName, 0, 1));
                    @endphp
                    <a href="{{ route('ads.create') }}" class="btn btn-primary btn-pill btn-nav">
                        <svg class="icon-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                        Publier
                    </a>
                    <div class="nav-user-menu">
                        <button type="button" class="nav-user-btn" id="user-menu-btn" aria-haspopup="true" aria-expanded="false">
                            <span class="user-avatar">{{ $initial }}</span>
                            <span class="user-name">{{ $userName }}</span>
                            <svg class="icon-xs nav-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
                        </button>
                        <div class="nav-dropdown" id="user-dropdown" role="menu" aria-label="Menu utilisateur">
                            <a href="{{ route('profile') }}" class="nav-dropdown-item" role="menuitem">
                                <svg class="icon-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.58-7 8-7s8 3 8 7"/></svg>
                                Mon profil
                            </a>
                            <a href="{{ route('ads.create') }}" class="nav-dropdown-item" role="menuitem">
                                <svg class="icon-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                                Publier une annonce
                            </a>
                            <div class="nav-dropdown-divider"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="nav-dropdown-item nav-dropdown-danger" role="menuitem">
                                    <svg class="icon-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                                    Déconnexion
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <div class="navbar-guest-actions">
                        <a href="{{ route('login') }}" class="btn btn-outline btn-pill">Se connecter</a>
                        <a href="{{ route('register') }}" class="btn btn-primary btn-pill">S'inscrire</a>
                    </div>
                @endauth
                <button class="navbar-burger" id="navbar-burger" aria-label="Ouvrir le menu mobile" aria-expanded="false">
                    <span></span><span></span><span></span>
                </button>
            </div>
        </div>

        <div class="navbar-mobile" id="navbar-mobile" aria-hidden="true">
            <form action="{{ route('home') }}" method="GET" class="mobile-search">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Rechercher..." class="mobile-search-input">
                <button type="submit" class="btn btn-primary btn-mobile-search">OK</button>
            </form>
            @auth
                <a href="{{ route('profile') }}" class="mobile-nav-link">Mon Profil</a>
                <a href="{{ route('ads.create') }}" class="mobile-nav-link">Publier une annonce</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="mobile-nav-link mobile-nav-danger">Déconnexion</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="mobile-nav-link">Se connecter</a>
                <a href="{{ route('register') }}" class="mobile-nav-link mobile-nav-primary">S'inscrire gratuitement</a>
            @endauth
        </div>
    </header>

    {{-- ===== MESSAGES FLASH ===== --}}
    @if(session('success'))
        <div class="alert alert-success" role="alert" id="flash-success">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
            {{ session('success') }}
            <button class="alert-close" onclick="this.parentElement.remove()" aria-label="Fermer">×</button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-error" role="alert" id="flash-error">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            {{ session('error') }}
            <button class="alert-close" onclick="this.parentElement.remove()" aria-label="Fermer">×</button>
        </div>
    @endif

    {{-- ===== CONTENU PRINCIPAL ===== --}}
    <main id="main-content">
        @yield('content')
    </main>

    {{-- ===== FOOTER ===== --}}
    <footer class="footer">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-brand">
                    <img src="{{ asset('images/logofreeads.png') }}" alt="FreeAds" class="footer-logo">
                    <p>La plateforme de petites annonces gratuites. Achetez, vendez, échangez en toute simplicité.</p>
                </div>
                <div class="footer-links">
                    <h4>Annonces</h4>
                    <ul>
                        <li><a href="{{ route('home') }}">Toutes les annonces</a></li>
                        @auth
                        <li><a href="{{ route('ads.create') }}">Publier une annonce</a></li>
                        <li><a href="{{ route('profile') }}">Mes annonces</a></li>
                        @else
                        <li><a href="{{ route('register') }}">S'inscrire gratuitement</a></li>
                        @endauth
                    </ul>
                </div>
                <div class="footer-links">
                    <h4>Catégories</h4>
                    <ul>
                        @foreach(\App\Models\Ad::CATEGORIES as $cat)
                        <li><a href="{{ route('home', ['category' => $cat]) }}">{{ $cat }}</a></li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>© {{ date('Y') }} FreeAds — Tous droits réservés.</p>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
