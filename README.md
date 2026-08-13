# VORTEX — Petites annonces premium en Laravel 11

VORTEX est une plateforme de petites annonces premium, pensée pour un rendu dark mode très haut de gamme inspiré du streetwear / fashion minimal. La plateforme permet à un client de publier une annonce, de filtrer les résultats, de consulter le détail d’un produit et de gérer son profil.

## 1. Prérequis

Avant de commencer, assurez-vous d’avoir installé sur votre machine :

- PHP 8.2+
- Composer
- Node.js 18+
- npm
- MySQL 8+
- Git

## 2. Installation rapide

### 2.1 Cloner le projet

```bash
git clone <url-du-projet>
cd vortex
```

### 2.2 Installer les dépendances PHP

```bash
composer install
```

### 2.3 Installer les dépendances frontend

```bash
npm install
```

### 2.4 Créer le fichier d’environnement

```bash
cp .env.example .env
```

Ensuite, configurez votre base MySQL dans le fichier [.env](.env) :

```env
APP_NAME=VORTEX
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=vortex
DB_USERNAME=root
DB_PASSWORD=
```

### 2.5 Générer la clé Laravel

```bash
php artisan key:generate
```

### 2.6 Créer la base de données MySQL

Dans MySQL :

```sql
CREATE DATABASE vortex;
```

### 2.7 Lancer les migrations et les seeders

```bash
php artisan migrate --seed
```

Cela crée :

- la table `users`
- la table `ads`
- les comptes de démonstration
- 8 annonces d’exemple en français

### 2.8 Lancer le serveur Laravel

```bash
php artisan serve
```

Ensuite ouvrez :

```txt
http://127.0.0.1:8000
```

### 2.9 Compiler le CSS/JS frontend

```bash
npm run dev
```

Ou pour la version build de production :

```bash
npm run build
```

## 3. Architecture utilisateur : client vs admin

Le modèle `User` contient un champ `role` avec deux valeurs possibles :

- `client`
- `admin`

### 3.1 Rôle client
Un client peut :

- s’inscrire
- se connecter
- créer une annonce
- modifier ou supprimer ses propres annonces
- consulter son profil

### 3.2 Rôle admin
Un admin peut :

- gérer toutes les annonces
- voir toutes les données utilisateurs
- modifier ou supprimer des contenus si nécessaire
- gérer la plateforme en backend

### 3.3 Promouvoir un utilisateur en admin

#### Option A — via MySQL directement

```sql
UPDATE users SET role = 'admin' WHERE email = 'admin@vortex.com';
```

#### Option B — via un seeder

```php
User::create([
    'login' => 'admin',
    'name' => 'Admin VORTEX',
    'email' => 'admin@vortex.com',
    'password' => Hash::make('password123'),
    'role' => 'admin',
]);
```

Dans cette application, l’admin de démonstration est déjà créé par [database/seeders/DatabaseSeeder.php](database/seeders/DatabaseSeeder.php).

## 4. Comment fonctionne la publication d’une annonce

### 4.1 En tant que client

1. L’utilisateur s’inscrit via la page d’inscription.
2. Il valide son email si le système de vérification est activé.
3. Il se connecte.
4. Il clique sur le bouton `Poster une annonce`.
5. Il remplit le formulaire :
   - titre
   - catégorie
   - description
   - prix
   - ville / localisation
   - état du produit
   - photo
6. Laravel valide les données puis les sauvegarde dans la table `ads`.

### 4.2 Mapping avec MySQL

Le formulaire crée une ligne dans la table `ads` comme ceci :

| Colonne | Exemple |
|---|---|
| user_id | 3 |
| title | Nintendo Switch OLED |
| category | Jeux vidéo |
| description | Console ... |
| price | 250000 |
| location | Abidjan |
| condition | new |
| photo | url image |

La clé étrangère `user_id` relie l’annonce à l’utilisateur qui l’a créée.

## 5. Architecture de la base de données

### 5.1 Table `users`

La table `users` contient les données de compte :

- `id`
- `login`
- `name`
- `email`
- `email_verified_at`
- `password`
- `phone_number`
- `role`
- `remember_token`
- `created_at`
- `updated_at`

### 5.2 Table `ads`

La table `ads` contient les annonces :

- `id`
- `user_id`
- `title`
- `category`
- `description`
- `price`
- `location`
- `condition`
- `photo`
- `created_at`
- `updated_at`

## 6. Points clés du code

### Modèle utilisateur
- [app/Models/User.php](app/Models/User.php)

### Modèle annonce
- [app/Models/Ad.php](app/Models/Ad.php)

### Contrôleur annonces
- [app/Http/Controllers/AdController.php](app/Http/Controllers/AdController.php)

### Routes du site
- [routes/web.php](routes/web.php)

### Page d’accueil
- [resources/views/welcome.blade.php](resources/views/welcome.blade.php)

### Layout principal
- [resources/views/layouts/app.blade.php](resources/views/layouts/app.blade.php)

## 7. Commandes utiles

```bash
php artisan migrate
php artisan migrate:fresh --seed
php artisan route:list
php artisan make:model Ad -m
php artisan make:controller AdController
php artisan serve
npm run dev
```

## 8. Bonnes pratiques du projet

- Ne jamais stocker les mots de passe en clair.
- Vérifier les emails pour sécuriser la publication d’annonces.
- Utiliser toujours les `fillable` sur les modèles Eloquent.
- Vérifier les validations côté serveur avant l’insertion en base.
- Rester cohérent entre le rôle, le profil et les permissions.

## 9. Résumé

VORTEX est une plateforme de petites annonces premium avec :

- design dark mode premium
- contenu en français
- gestion utilisateur claire
- système d’annonces complet
- filtres et recherche avancés
- architecture Laravel 11 propre et évolutive

## 10. Support

Pour toute modification, démarrez par :

```bash
php artisan route:list
php artisan tinker
```

et vérifiez votre base de données MySQL pour confirmer les valeurs `role`, `user_id` et `category`.
