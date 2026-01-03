# Changelog - Authentification Google OAuth & Photos de profil

## [1.1.0] - 2025-11-27

### 🎉 Nouvelles fonctionnalités

#### Authentification Google OAuth
- Ajout de l'authentification via Google OAuth 2.0
- Bouton "Continuer avec Google" sur les pages connexion et inscription
- Création automatique de compte lors de la première connexion Google
- Récupération automatique du nom, email et photo de profil Google
- Gestion intelligente des comptes existants (liaison par email)

#### Photos de profil
- Upload de photo de profil lors de l'inscription par formulaire
- Prévisualisation de la photo avant upload
- Validation des fichiers (types: jpeg, png, jpg, gif | taille max: 2MB)
- Stockage sécurisé dans `storage/app/public/avatars`
- Support des avatars Google (URLs) et locaux (fichiers)
- Composant `UserAvatar` réutilisable avec fallback sur initiales
- Affichage des avatars dans le header de navigation
- Génération automatique d'initiales si pas de photo

#### Workflow de validation amélioré
- Message clair : "Vous recevrez un email une fois votre compte activé"
- Notification par email à l'utilisateur lors de l'activation du compte
- Email de bienvenue avec lien de connexion

### 🔧 Modifications techniques

#### Backend (Laravel)

**Base de données**
- Migration `2025_11_27_130259_add_avatar_to_users_table`
  - Ajout colonne `avatar` (nullable, string)
  - Ajout colonne `google_id` (nullable, unique, string)

**Modèles**
- `User.php` : Ajout de `avatar` et `google_id` dans `$fillable`

**Contrôleurs**
- `AuthController.php` :
  - Méthode `register()` : Support de l'upload d'avatar
  - Méthode `login()` : Retourne l'URL de l'avatar
  - Méthode `user()` : Retourne l'URL de l'avatar
  - Gestion des URLs Google vs fichiers locaux
- `GoogleAuthController.php` (nouveau) :
  - Méthode `redirectToGoogle()` : Redirection vers Google OAuth
  - Méthode `handleGoogleCallback()` : Gestion du retour de Google
  - Création/connexion automatique de compte
  - Récupération de la photo de profil Google

**Routes**
- `GET /api/auth/google` : Redirection vers Google
- `GET /api/auth/google/callback` : Callback Google OAuth

**Configuration**
- `config/services.php` : Configuration Google OAuth
- `.env` : Variables `GOOGLE_CLIENT_ID`, `GOOGLE_CLIENT_SECRET`, `GOOGLE_REDIRECT_URI`

**Packages**
- Installation de `laravel/socialite` v5.23

#### Frontend (Nuxt.js)

**Pages**
- `pages/inscription.vue` :
  - Ajout du champ upload de photo avec prévisualisation
  - Ajout du bouton "Continuer avec Google"
  - Support de FormData pour l'upload
  - Message de validation amélioré
- `pages/connexion.vue` :
  - Ajout du bouton "Continuer avec Google"
- `pages/auth/callback.vue` (nouveau) :
  - Gestion du retour de Google OAuth
  - Affichage des différents statuts (success, pending, error)
  - Redirection automatique après succès

**Composants**
- `components/UserAvatar.vue` (nouveau) :
  - Affichage d'avatar avec image ou initiales
  - 5 tailles disponibles (xs, sm, md, lg, xl)
  - Dégradé de couleur pour le fond
  - Support des URLs Google et fichiers locaux
- `components/Header.vue` :
  - Intégration du composant `UserAvatar`
  - Affichage de l'avatar dans la navigation desktop et mobile

**Stores**
- `stores/auth.js` :
  - Support de FormData dans la méthode `register()`

### 📚 Documentation

**Nouveaux fichiers**
- `README_OAUTH_AVATAR.md` : Guide complet d'utilisation
- `GOOGLE_OAUTH_SETUP.md` : Configuration détaillée Google OAuth
- `IMPLEMENTATION_SUMMARY.md` : Résumé technique de l'implémentation
- `TESTING_CHECKLIST.md` : Liste de tests à effectuer
- `NEXT_STEPS.md` : Prochaines étapes et améliorations
- `API_ROUTES.md` : Documentation complète des routes API
- `CHANGELOG.md` : Ce fichier
- `components/README_AVATAR.md` : Documentation du composant Avatar

### 🔒 Sécurité

- Validation stricte des fichiers uploadés (type, taille)
- Mot de passe aléatoire sécurisé pour les comptes Google
- Vérification du statut du compte avant connexion
- Tokens gérés par Laravel Sanctum
- Tous les nouveaux comptes en statut "pending" (validation admin requise)

### 🎨 Design

- Bouton Google avec logo officiel et couleurs Google
- Upload de photo avec prévisualisation circulaire
- Avatar circulaire avec dégradé de couleur
- Initiales en blanc sur fond coloré
- Messages clairs et informatifs
- Design responsive (mobile-friendly)

### 📧 Emails

**Nouveaux emails**
- Email d'activation de compte (déjà existant, utilisé maintenant)

**Emails modifiés**
- Email de nouvelle inscription : Envoyé pour tous les types d'inscription

### ⚡ Performance

- Stockage local des avatars pour réduire les requêtes externes
- Lien symbolique pour servir les fichiers statiques
- Lazy loading des avatars

### 🐛 Corrections

- Message de validation plus clair lors de l'inscription
- Gestion des erreurs Google OAuth
- Support des comptes avec et sans photo

### 🔄 Changements de comportement

**Avant**
- Inscription uniquement par formulaire
- Pas de photo de profil
- Message : "Votre compte est en attente de validation"

**Après**
- Inscription par formulaire OU Google OAuth
- Photo de profil optionnelle (formulaire) ou automatique (Google)
- Message : "Votre compte est en attente de validation. Vous recevrez un email une fois votre compte activé"
- Email envoyé lors de l'activation du compte

### 📊 Statistiques

- **Fichiers créés** : 8
- **Fichiers modifiés** : 10
- **Lignes de code ajoutées** : ~800
- **Nouvelles routes API** : 2
- **Nouveaux composants** : 2
- **Nouvelles colonnes DB** : 2

### 🚀 Migration

#### Pour les développeurs

1. Exécuter les migrations :
   ```bash
   php artisan migrate
   ```

2. Créer le lien symbolique :
   ```bash
   php artisan storage:link
   ```

3. Configurer Google OAuth dans `.env`

4. Installer les dépendances frontend :
   ```bash
   cd les-mets-chics-frontend
   npm install
   ```

#### Pour les utilisateurs existants

- Les comptes existants ne sont pas affectés
- Les colonnes `avatar` et `google_id` sont nullables
- Pas de migration de données nécessaire

### 🔮 Prochaines versions

**v1.2.0** (Prévu)
- Modification de la photo de profil
- Intégration des avatars dans les commentaires
- Intégration des avatars sur les recettes
- Page de profil utilisateur complète

**v1.3.0** (Prévu)
- Compression automatique des images
- Support de plus de providers OAuth (Facebook, GitHub)
- Crop d'image avant upload
- CDN pour les avatars

### 📝 Notes de version

Cette version introduit l'authentification Google OAuth et les photos de profil. Tous les nouveaux comptes (Google ou formulaire) nécessitent une validation par un administrateur avant de pouvoir se connecter.

### 🙏 Remerciements

- Laravel Socialite pour l'intégration OAuth
- Google Cloud Platform pour l'API OAuth 2.0
- Nuxt.js pour le framework frontend
- Tailwind CSS pour le design

---

**Version** : 1.1.0  
**Date** : 27 novembre 2025  
**Auteur** : Équipe Les Mets Chics
