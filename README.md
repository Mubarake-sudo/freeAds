# VORTEX — Petites annonces premium en Laravel 11

VORTEX est une plateforme de petites annonces premium pensée pour un rendu sombre, premium et moderne. Elle permet à un utilisateur de publier, consulter, filtrer et gérer des annonces en toute simplicité.

Ce projet est conçu pour fonctionner en local puis être déployé facilement sur Railway, avec une base MySQL et un front Laravel + Vite.

## 1. Prérequis

Avant de démarrer, vérifiez que vous avez bien installé :

- PHP 8.2+
- Composer
- Node.js 18+
- npm
- MySQL 8+
- Git

## 2. Installation locale complète

### 2.1 Cloner le projet

```bash
git clone <url-du-repo>
cd freeAds
```

### 2.2 Installer les dépendances PHP

```bash
composer install
```

### 2.3 Installer les dépendances frontend

```bash
npm install
```

### 2.4 Créer le fichier .env

```bash
cp .env.example .env
```

Puis configurez votre base de données dans [.env](.env) :

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

### 2.6 Créer la base MySQL

Dans MySQL ou MariaDB :

```sql
CREATE DATABASE vortex;
```

### 2.7 Lancer les migrations et les seeders

```bash
php artisan migrate --seed
```

Cela va créer :

- la table `users`
- la table `ads`
- les comptes de démonstration
- plusieurs annonces d’exemple

### 2.8 Démarrer le serveur Laravel

```bash
php artisan serve
```

Ouvrez ensuite :

```txt
http://127.0.0.1:8000
```

### 2.9 Compiler le frontend

Pendant le développement :

```bash
npm run dev
```

En production :

```bash
npm run build
```

---

## 3. Vérifier le nombre d’inscrits dans la base

### Option A — via SQL

```sql
SELECT COUNT(*) AS total_users FROM users;
```

### Option B — via Laravel

```bash
php artisan tinker --execute="echo App\\Models\\User::count();"
```

### Option C — compter par rôle

```sql
SELECT role, COUNT(*) AS total FROM users GROUP BY role;
```

---

## 4. Hacher les mots de passe correctement

Laravel utilise le hachage automatique via la propriété `password` castée en `hashed` dans [app/Models/User.php](app/Models/User.php).

### Exemple de hash manuel

```bash
php artisan tinker --execute="echo Illuminate\\Support\\Facades\\Hash::make('password123');"
```

### Vérifier un mot de passe

```bash
php artisan tinker --execute="echo Illuminate\\Support\\Facades\\Hash::check('password123', App\\Models\\User::first()->password) ? 'OK' : 'KO';"
```

> Important : ne jamais stocker les mots de passe en clair dans la base de données.

---

## 5. Architecture utilisateur : client vs admin

Le modèle `User` contient un champ `role` avec deux valeurs possibles :

- `client`
- `admin`

### 5.1 Rôle client
Un client peut :

- s’inscrire
- se connecter
- publier une annonce
- modifier ou supprimer ses propres annonces
- gérer son profil

### 5.2 Rôle admin
Un admin peut :

- consulter toutes les annonces
- gérer les contenus
- superviser la plateforme

### 5.3 Promouvoir un utilisateur en admin

#### Option SQL

```sql
UPDATE users SET role = 'admin' WHERE email = 'admin@vortex.com';
```

#### Option Laravel / seeder

```php
User::create([
    'login' => 'admin',
    'name' => 'Admin VORTEX',
    'email' => 'admin@vortex.com',
    'password' => Hash::make('password123'),
    'role' => 'admin',
]);
```

---

## 6. Publier et gérer une annonce

### 6.1 Processus client

1. L’utilisateur s’inscrit.
2. Il vérifie son email si la vérification est activée.
3. Il se connecte.
4. Il clique sur `Poster une annonce`.
5. Il remplit le formulaire.
6. La photo est téléchargée et stockée dans le dossier `storage/app/public/ads`.
7. L’annonce est enregistrée dans la table `ads`.

### 6.2 Structure de la table `ads`

| Colonne | Exemple |
|---|---|
| user_id | 3 |
| title | Nintendo Switch OLED |
| category | Jeux vidéo |
| description | Console ... |
| price | 250000 |
| location | Abidjan |
| condition | new |
| photo | ads/abc123.png |

---

## 7. Déploiement sur Railway (méthode simple et rapide)

Railway est un bon choix pour un projet Laravel de petite ou moyenne taille, car il est simple à configurer et très accessible aux débutants.

### Étape 1 — Préparer le dépôt GitHub

1. Créez un dépôt GitHub pour le projet.
2. Poussez le code local :

```bash
git add .
git commit -m "Initial commit"
git push origin main# add platform php to composer.json (merge into existing "config")
# then:
composer update --with-all-dependencies
git add composer.lock composer.json
git commit -m "Regenerate lockfile for PHP 8.2"
git push
```

### Étape 2 — Créer un compte Railway

1. Allez sur https://railway.app
2. Connectez-vous avec GitHub
3. Cliquez sur `New Project`
4. Choisissez `Deploy from GitHub repo`
5. Sélectionnez votre dépôt

### Étape 3 — Ajouter une base MySQL

1. Dans Railway, cliquez sur `New` puis `Database`
2. Choisissez `MySQL`
3. Attendez la création de la base
4. Notez les variables d’environnement fournies automatiquement :
   - `MYSQLHOST`
   - `MYSQLPORT`
   - `MYSQLDATABASE`
   - `MYSQLUSER`
   - `MYSQLPASSWORD`

### Étape 4 — Configurer les variables d’environnement Laravel

Dans Railway, ouvrez votre service application puis `Variables` et ajoutez :

```env
APP_NAME=VORTEX
APP_ENV=production
APP_DEBUG=false
APP_URL=https://<votre-domaine-railway>.up.railway.app

DB_CONNECTION=mysql
DB_HOST=<MYSQLHOST>
DB_PORT=3306
DB_DATABASE=<MYSQLDATABASE>
DB_USERNAME=<MYSQLUSER>
DB_PASSWORD=<MYSQLPASSWORD>

CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_CONNECTION=sync
```

### Étape 5 — Définir la commande de démarrage

Dans Railway, dans le service de l’application, ajoutez :

```bash
php artisan serve --host 0.0.0.0 --port $PORT
```

### Étape 6 — Installer les dépendances et compiler le frontend

Railway va automatiquement installer Composer et Node.js pour le build. Si nécessaire, configurez la commande de build (ou laissez le comportement par défaut du framework) :

```bash
composer install --no-interaction --prefer-dist --optimize-autoloader
npm install
npm run build
```

### Étape 7 — Exécuter les migrations sur Railway

Dans le terminal Railway ou via la console de commande :

```bash
php artisan migrate --seed
```

### Étape 8 — Vérifier le site

Une fois le déploiement terminé, ouvrez l’URL fournie par Railway.

Votre site est alors accessible en ligne avec la base MySQL connectée.

> Remarque : pour un déploiement plus robuste, il est conseillé d’ajouter Plusieurs variables d’environnement et de configurer un domaine personnalisé plus tard.

---

## 8. Contact vendeur / acheteur

Le projet inclut désormais le contact avec le vendeur via :

- appel téléphonique si le vendeur a sa `phone_number`
- e-mail si le téléphone n’est pas renseigné

Cela est géré dans [resources/views/ads/show.blade.php](resources/views/ads/show.blade.php).

---

## 9. Points clés du code

### Modèle utilisateur
- [app/Models/User.php](app/Models/User.php)

### Modèle annonce
- [app/Models/Ad.php](app/Models/Ad.php)

### Contrôleur annonces
- [app/Http/Controllers/AdController.php](app/Http/Controllers/AdController.php)

### Contrôleur utilisateur
- [app/Http/Controllers/UserController.php](app/Http/Controllers/UserController.php)

### Routes du site
- [routes/web.php](routes/web.php)

### Page d’accueil
- [resources/views/welcome.blade.php](resources/views/welcome.blade.php)

### Layout principal
- [resources/views/layouts/app.blade.php](resources/views/layouts/app.blade.php)

---

## 10. Commandes utiles

```bash
php artisan migrate
php artisan migrate:fresh --seed
php artisan route:list
php artisan test
php artisan serve
npm run dev
npm run build
```

---

## 11. Bonnes pratiques

- Ne jamais stocker les mots de passe en clair.
- Toujours valider les entrées utilisateur.
- Vérifier les images avant de les enregistrer.
- Privilégier des messages flash courts et lisibles.
- Tester régulièrement le site après chaque changement visuel ou fonctionnel.

---

## 12. Résumé rapide

Cette application est prête pour :

- le développement local
- la démonstration client
- l’hébergement simple sur Railway
- la publication d’annonces premium en dark mode

Si vous voulez aller plus loin, vous pouvez ensuite ajouter :

- une vraie messagerie interne
- filtres avancés par ville et budget
- gestion admin backend
- paiement ou réservation de produits
- Vérifier les emails pour sécuriser la publication d’annonces.
- Utiliser toujours les `fillable` sur les modèles Eloquent.
- Vérifier les validations côté serveur avant l’insertion en base.
- Rester cohérent entre le rôle, le profil et les permissions.

---

## Déployer sur Render

Voici une méthode simple pour déployer ce projet sur Render en utilisant un `Dockerfile`.

### 1) Préparer le dépôt

1. Poussez votre code sur GitHub si ce n'est pas déjà fait.

### 2) Ajouter le `Dockerfile`

Ce dépôt inclut un `Dockerfile` prêt pour Render. Le fichier installe les dépendances PHP, compile les assets front-end (si `package.json` existe), exécute les migrations puis démarre le serveur Laravel sur le port attendu par Render.

### 3) Créer un service Web sur Render

1. Connectez-vous sur https://render.com et créez un nouveau `Web Service`.
2. Choisissez «Docker» (Render détectera automatiquement le `Dockerfile`).
3. Sélectionnez votre dépôt GitHub et la branche à déployer.

### 4) Variables d'environnement

Ajoutez les variables d'environnement nécessaires dans la section `Environment` du service :

```
APP_ENV=production
APP_DEBUG=false
APP_KEY= (laisser vide pour que Laravel utilise la clé générée ou fournissez-en une)
DB_CONNECTION=mysql
DB_HOST=<MYSQLHOST>
DB_PORT=3306
DB_DATABASE=<MYSQLDATABASE>
DB_USERNAME=<MYSQLUSER>
DB_PASSWORD=<MYSQLPASSWORD>
PORT=8080
```

Render fournit également des services de base de données que vous pouvez créer et connecter ici.

### 5) Commande de build et de démarrage

Avec un `Dockerfile` le build et le démarrage sont gérés par l'image. Aucune commande personnalisée n'est nécessaire dans Render, mais vous pouvez définir des `Health Checks` si vous le souhaitez.

### 6) Exécuter les migrations (optionnel)

Les migrations sont exécutées automatiquement par le `CMD` du `Dockerfile`. Si vous préférez exécuter manuellement :

1. Ouvrez la `Shell` du service Render.
2. Lancez :

```
php artisan migrate --force
```

### 7) Notes et recommandations

- Pour un déploiement de production, préférez utiliser un serveur HTTP (nginx / php-fpm) ou `frankenphp` configuré avec Caddy pour la performance et la sécurité. Le `Dockerfile` fourni est destiné à un déploiement simple et fonctionnel.
- Assurez-vous que `APP_KEY` est défini en production et gardé secret.
- Après un premier déploiement, vérifiez les logs Render pour détecter des erreurs liées aux dépendances ou à la base de données.

Si tu veux, je peux :
- ajuster le `Dockerfile` pour utiliser une configuration `php-fpm + nginx` ou
- configurer un workflow Render plus avancé (health checks, services séparés).


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
