# Les Mets Chics - Frontend

Frontend élégant pour l'application de recettes gastronomiques "Les Mets Chics".

## Technologies

- **Nuxt 3** - Framework Vue.js
- **Tailwind CSS** - Styling
- **Pinia** - State management
- **Nuxt Icon** - Icônes

## Installation

```bash
npm install
```

## Configuration

Créez un fichier `.env` à partir de `.env.example` :

```bash
cp .env.example .env
```

Configurez l'URL de l'API backend :

```
NUXT_PUBLIC_API_BASE=http://localhost:8000/api
```

## Développement

```bash
npm run dev
```

L'application sera disponible sur `http://localhost:3000`

## Build

```bash
npm run build
npm run preview
```

## Structure du projet

```
├── components/          # Composants réutilisables
│   ├── Header.vue
│   ├── Footer.vue
│   └── RecipeCard.vue
├── composables/         # Composables Vue
│   └── useApi.js
├── layouts/            # Layouts Nuxt
│   └── default.vue
├── middleware/         # Middlewares de route
│   └── auth.js
├── pages/              # Pages de l'application
│   ├── index.vue
│   ├── connexion.vue
│   ├── inscription.vue
│   ├── profil.vue
│   ├── recettes/
│   │   ├── index.vue
│   │   └── [slug].vue
│   └── categories/
│       ├── index.vue
│       └── [slug].vue
├── stores/             # Stores Pinia
│   └── auth.js
└── nuxt.config.ts      # Configuration Nuxt
```

## Fonctionnalités

### Pages publiques (accessibles sans connexion)
- ✅ Page d'accueil avec hero et recettes featured
- ✅ Liste complète des recettes avec recherche et filtres
- ✅ Détails complets d'une recette (ingrédients, étapes, vidéo)
- ✅ Liste des catégories
- ✅ Recettes par catégorie
- ✅ Visualisation des commentaires
- ✅ Page "À propos"
- ✅ Banner d'invitation pour les visiteurs non connectés

### Authentification
- ✅ Inscription (compte en attente de validation admin)
- ✅ Connexion avec gestion de session
- ✅ Déconnexion
- ✅ Profil utilisateur avec statistiques

### Fonctionnalités interactives (nécessitent une connexion)
- ✅ Like/Unlike des recettes
- ✅ Ajout de commentaires (avec modération admin)
- ✅ Gestion du profil utilisateur

### Design & UX
- ✅ Navigation responsive avec menu mobile
- ✅ Design élégant et moderne
- ✅ Animations et transitions fluides
- ✅ Palette de couleurs raffinée
- ✅ Typographie gastronomique (Playfair Display + Inter)

## Design

Le design utilise une palette de couleurs raffinée :
- **Primary** : Tons beiges/marrons chauds
- **Accent** : Tons verts pour les actions
- **Typographie** : Playfair Display (serif) + Inter (sans-serif)

## API

L'application communique avec le backend Laravel via l'API REST.
Voir le composable `useApi.js` pour tous les endpoints disponibles.
