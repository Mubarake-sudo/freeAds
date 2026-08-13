<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'VORTEX ADS') — Petites annonces premium</title>
    <meta name="description" content="@yield('meta_description', 'VORTEX ADS — Petites annonces premium, achats et ventes en ligne.')">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="vortex-body">
    <header class="vortex-header" role="banner">
        <div class="vortex-topbar">
            <div class="vortex-left-nav">
                <a href="{{ route('home') }}" class="vortex-brand" aria-label="VORTEX ADS accueil">
                    <span class="vortex-brand-word">VORTEX</span>
                    <span class="vortex-brand-sub">ADS</span>
                </a>
                <nav class="vortex-nav" aria-label="Navigation principale">
                    <a href="{{ route('home') }}">Accueil</a>
                    <a href="{{ route('home', ['category' => 'Vêtements']) }}">Catégories</a>
                    <a href="{{ route('home') }}">À propos</a>
                    <a href="{{ route('home') }}">Contact</a>
                </nav>
            </div>

            <div class="vortex-header-actions">
                @auth
                    <a href="{{ route('ads.create') }}" class="vortex-btn neon">Poster une annonce</a>
                    <a href="{{ route('profile') }}" class="vortex-link">Mon profil</a>
                    <form method="POST" action="{{ route('logout') }}" class="inline-form">
                        @csrf
                        <button type="submit" class="vortex-link danger">Déconnexion</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="vortex-link">Connexion</a>
                    <a href="{{ route('register') }}" class="vortex-btn neon">Poster une annonce</a>
                @endauth
            </div>
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
    <footer class="footer vortex-footer">
        <div class="container footer-inner">
            <div class="footer-brand">
                <div class="vortex-brand footer-brand-mark" aria-label="VORTEX ADS">
                    <span class="vortex-brand-word">VORTEX</span>
                    <span class="vortex-brand-sub">ADS</span>
                </div>
                <p>Le marché premium pour acheter, vendre et découvrir des produits qui ont une vraie valeur.</p>
            </div>
            <div class="footer-links">
                <h4>Annonces</h4>
                <ul>
                    <li><a href="{{ route('home') }}">Toutes les annonces</a></li>
                    @auth
                    <li><a href="{{ route('ads.create') }}">Publier une annonce</a></li>
                    <li><a href="{{ route('profile') }}">Mon profil</a></li>
                    @else
                    <li><a href="{{ route('register') }}">Créer un compte</a></li>
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
            <p>© {{ date('Y') }} VORTEX ADS — Tous droits réservés.</p>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
