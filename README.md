# 🍽️ Les Mets Chics

[![Backend tests](https://github.com/Isho393/Les-Mets-Chics/actions/workflows/backend-tests.yml/badge.svg)](https://github.com/Isho393/Les-Mets-Chics/actions/workflows/backend-tests.yml)

Plateforme de recettes gastronomiques avec backend Laravel et frontend Nuxt 3.

## 📋 Vue d'ensemble

**Les Mets Chics** est une application web complète permettant de partager et découvrir des recettes gastronomiques raffinées. Le projet se compose de deux parties :

- **Backend** : API REST Laravel avec panel d'administration Filament
- **Frontend** : Application Nuxt 3 avec design élégant et responsive

## 🎯 Fonctionnalités principales

### Pour les visiteurs (non connectés)
- ✅ Parcourir toutes les recettes publiées
- ✅ Voir les détails complets (ingrédients, étapes, vidéos)
- ✅ Explorer par catégories
- ✅ Rechercher et filtrer les recettes
- ✅ Lire les commentaires approuvés

### Pour les utilisateurs connectés
- ✅ Liker/Unliker des recettes
- ✅ Commenter les recettes (modération admin)
- ✅ Gérer son profil
- ✅ Voir ses statistiques

### Pour les auteurs
- ✅ Créer et publier des recettes via Filament
- ✅ Uploader images et vidéos
- ✅ Gérer leurs propres recettes

### Pour les administrateurs
- ✅ Panel d'administration Filament complet
- ✅ Gestion des utilisateurs (validation, suspension)
- ✅ Modération des commentaires
- ✅ Gestion des catégories
- ✅ Gestion de toutes les recettes

## 🏗️ Architecture

### Backend (Laravel 12)
```
les-mets-chics-backend/
├── app/
│   ├── Http/Controllers/Api/    # Contrôleurs API
│   ├── Models/                  # Modèles Eloquent
│   ├── Policies/                # Politiques d'autorisation
│   └── Filament/                # Panel admin
├── database/
│   ├── migrations/              # Migrations de BDD
│   └── seeders/                 # Seeders
├── routes/
│   └── api.php                  # Routes API
└── storage/                     # Uploads (images, vidéos)
```

**Stack technique** :
- Laravel 12 + PHP 8.2+
- SQLite (dev) / MySQL (prod)
- Laravel Sanctum (authentification API)
- Filament 4.2 (panel admin)
- L5-Swagger (documentation API)

### Frontend (Nuxt 3)
```
les-mets-chics-frontend/
├── components/                  # Composants réutilisables
├── composables/                 # Composables (useApi)
├── layouts/                     # Layouts
├── middleware/                  # Middlewares de route
├── pages/                       # Pages de l'application
├── stores/                      # Stores Pinia
└── public/                      # Assets statiques
```

**Stack technique** :
- Nuxt 3 + Vue 3
- Tailwind CSS
- Pinia (state management)
- Nuxt Icon
- Google Fonts (Playfair Display + Inter)

## 🚀 Installation

### Prérequis
- PHP 8.2+
- Composer
- Node.js 18+
- npm

### Backend

```bash
cd les-mets-chics-backend

# Installer les dépendances
composer install

# Copier le fichier d'environnement
cp .env.example .env

# Générer la clé d'application
php artisan key:generate

# Créer la base de données SQLite
touch database/database.sqlite

# Exécuter les migrations
php artisan migrate

# Créer un utilisateur admin (optionnel)
php artisan tinker
>>> User::create(['name' => 'Admin', 'email' => 'admin@example.com', 'password' => bcrypt('password'), 'role' => 'admin', 'status' => 'active'])

# Lancer le serveur
php artisan serve
```

Le backend sera accessible sur `http://localhost:8000`
Panel admin : `http://localhost:8000/admin`

### Frontend

```bash
cd les-mets-chics-frontend

# Installer les dépendances
npm install

# Copier le fichier d'environnement
cp .env.example .env

# Lancer le serveur de développement
npm run dev
```

Le frontend sera accessible sur `http://localhost:3000`

## 🧪 Tests & intégration continue

Le backend est couvert par une suite PHPUnit (24 tests) qui tourne automatiquement sur GitHub Actions, sous PHP 8.2 et 8.3, à chaque push et à chaque pull request.

| Fichier | Ce qui est vérifié |
| --- | --- |
| `tests/Feature/AuthTest.php` | Inscription (compte `pending`), connexion, refus des comptes en attente ou suspendus, changement de mot de passe, routes protégées |
| `tests/Feature/RecipeApiTest.php` | Recettes publiées vs brouillons, détail d'une recette, likes, publication et rejet des commentaires, affichage des seuls commentaires approuvés |
| `tests/Feature/StorageSecurityTest.php` | Service des fichiers privés et protection contre la remontée de dossiers (`../`) |
| `tests/Unit/ContentModerationServiceTest.php` | Détection des termes interdits (majuscules, leetspeak) |

```bash
cd les-mets-chics-backend
php artisan test
```

Les tests utilisent une base SQLite en mémoire : aucune configuration de base de données n'est nécessaire.

## 📡 API Endpoints

### Routes publiques
- `GET /api/recipes` - Liste des recettes
- `GET /api/recipes/{slug}` - Détails d'une recette
- `GET /api/categories` - Liste des catégories
- `GET /api/categories/{slug}/recipes` - Recettes par catégorie
- `GET /api/recipes/{slug}/comments` - Commentaires d'une recette

### Authentification
- `POST /api/register` - Inscription
- `POST /api/login` - Connexion
- `POST /api/logout` - Déconnexion (auth)
- `GET /api/user` - Infos utilisateur (auth)

### Routes protégées (authentification requise)
- `POST /api/recipes/{slug}/like` - Toggle like
- `GET /api/recipes/{slug}/like/check` - Vérifier like
- `POST /api/recipes/{slug}/comments` - Ajouter un commentaire
- `POST /api/upload/image` - Upload image
- `POST /api/upload/video` - Upload vidéo

Documentation complète : `http://localhost:8000/api/documentation`

## 🎨 Design

### Palette de couleurs
- **Primary** : Tons beiges/marrons chauds (#bfa094) - Élégance gastronomique
- **Accent** : Tons verts (#22c55e) - Fraîcheur et nature
- **Typographie** : 
  - Titres : Playfair Display (serif)
  - Corps : Inter (sans-serif)

### Principes de design
- Interface épurée et élégante
- Navigation intuitive
- Responsive mobile-first
- Animations subtiles
- Images haute qualité

## 👥 Rôles utilisateurs

### Explorateur (par défaut)
- Statut initial : `pending` (nécessite validation admin)
- Peut liker et commenter après validation

### Auteur
- Peut créer et gérer ses propres recettes
- Accès au panel Filament

### Admin
- Accès complet au panel Filament
- Gestion des utilisateurs
- Modération des commentaires
- Gestion de toutes les recettes

## 📝 Modèle de données

### Users
- name, email, password
- role: `admin` | `auteur` | `explorateur`
- status: `active` | `pending` | `suspended`

### Categories
- name, slug, description

### Recipes
- title, slug, description
- main_image_url, video_url
- prep_time, cook_time, servings
- ingredients (JSON), steps (JSON)
- status: `draft` | `published`
- Relations : user, category

### Comments
- body, is_approved
- Relations : user, recipe

### Likes
- Table pivot : user_id, recipe_id

## 🔒 Sécurité

- Authentification via Laravel Sanctum (tokens)
- Validation des données côté backend
- Politiques d'autorisation (Policies)
- Protection CSRF
- Modération des commentaires
- Validation des uploads (taille, format)

## 📦 Production

### Backend
```bash
composer install --optimize-autoloader --no-dev
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Frontend
```bash
npm run build
npm run preview
```

## 🤝 Contribution

Ce projet est un exemple de plateforme gastronomique complète. N'hésitez pas à l'adapter à vos besoins !

## 📄 Licence

MIT

---

**Bon appétit et bon code ! 🍽️👨‍💻**
