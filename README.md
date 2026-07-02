# FreeAds

FreeAds est une application Laravel de petites annonces responsive, moderne et entièrement fonctionnelle. Elle permet de créer un compte, se connecter, publier des annonces, rechercher des annonces et consulter les détails d’une annonce.

## Ce qui a été livré

- Système d’authentification complet : inscription, connexion, déconnexion.
- Modèle utilisateur avec champs login, email, mot de passe, téléphone.
- Système de publication d’annonces avec formulaire complet.
- Page d’accueil principale avec grille d’annonces, recherche et filtres.
- Pages de détail, édition et suppression d’annonces.
- Design moderne, responsive et cohérent sur desktop et mobile.
- Footer SEO-friendly et pages d’authentification stylisées.
- Tests de régression pour l’inscription.

## Fonctionnalités principales

### 1. Profil utilisateur
- Création d’un utilisateur via le modèle Eloquent.
- Champs obligatoires ou optionnels : login, email, mot de passe, téléphone.
- Authentification avec email ou pseudo.

### 2. Système d’annonces
- Publication d’une annonce avec :
  - titre
  - catégorie
  - description
  - photo
  - prix
  - localisation
  - état / condition
- Affichage de toutes les annonces sur la page d’accueil.
- Affichage du détail d’une annonce.
- Modification et suppression de ses propres annonces.

### 3. Recherche et filtres
- Barre de recherche par mots-clés.
- Filtres par catégorie, localisation, prix et condition.

### 4. Améliorations UI/UX
- Interface moderne inspirée d’un marché en ligne.
- Layout principal avec header, footer et menu mobile.
- Pages de connexion/inscription refaites complètement.
- Cartes d’annonces avec images cadrées et mise en page propre.

### 5. Qualité et tests
- Test de régression pour valider l’inscription utilisateur.
- Vérification de l’intégration du layout et des assets Vite.

## Structure du projet

- [routes/web.php](routes/web.php) : routes publiques, d’authentification et des annonces.
- [app/Http/Controllers/AdController.php](app/Http/Controllers/AdController.php) : logique CRUD et filtres.
- [app/Models/Ad.php](app/Models/Ad.php) : modèle annonce.
- [app/Models/User.php](app/Models/User.php) : modèle utilisateur.
- [resources/views/welcome.blade.php](resources/views/welcome.blade.php) : page d’accueil principale.
- [resources/views/layouts/app.blade.php](resources/views/layouts/app.blade.php) : layout global.
- [resources/views/auth/login.blade.php](resources/views/auth/login.blade.php) : connexion.
- [resources/views/auth/register.blade.php](resources/views/auth/register.blade.php) : inscription.
- [resources/css/app.css](resources/css/app.css) : styles principaux.
- [resources/js/app.js](resources/js/app.js) : scripts frontend.

## Installation

### Prérequis
- PHP 8.1+
- Composer
- Node.js 18+
- npm
- Base de données MySQL / MariaDB / SQLite

### Étapes

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
npm run build
php artisan serve
```

### Configuration base de données

Dans le fichier [.env](.env), configurez votre connexion :

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=freeads
DB_USERNAME=root
DB_PASSWORD=
```

## Utilisation

- Page d’accueil : /
- Inscription : /inscription
- Connexion : /connexion
- Déconnexion : /deconnexion
- Publier une annonce : /annonces/creer
- Détail d’une annonce : /annonces/{id}

## Données de démonstration

Le seeder crée des utilisateurs et des annonces de démonstration afin de visualiser rapidement le site.

## Vérifications faites

- Vérification du flux d’inscription et de connexion.
- Vérification de l’accès à la page de publication après authentification.
- Vérification du build Vite.
- Test de régression ajouté pour l’inscription.

## Notes importantes

- La page de publication est accessible dès qu’un utilisateur est connecté.
- L’application a été adaptée pour éviter les blocages visibles après inscription ou connexion.
- L’interface a été modernisée pour une meilleure expérience utilisateur.
