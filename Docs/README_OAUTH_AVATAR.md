# 🎉 Authentification Google OAuth & Photos de profil - Implémentation complète

## 📦 Ce qui a été implémenté

### ✅ Authentification Google OAuth
- Connexion et inscription via Google
- Récupération automatique des informations (nom, email, photo)
- Gestion des comptes existants et nouveaux
- Redirection intelligente après authentification

### ✅ Photos de profil
- Upload de photo lors de l'inscription par formulaire
- Prévisualisation avant upload
- Stockage sécurisé dans `storage/app/public/avatars`
- Support des photos Google et locales
- Composant Avatar réutilisable avec fallback sur initiales

### ✅ Workflow de validation
- Tous les nouveaux comptes (Google ou formulaire) en statut "pending"
- Notification par email aux admins lors d'une inscription
- Notification par email à l'utilisateur lors de l'activation
- Messages clairs sur le statut du compte

## 🚀 Démarrage rapide

### 1. Configuration Google OAuth (OBLIGATOIRE)

#### Créer un projet Google Cloud
1. Allez sur https://console.cloud.google.com/
2. Créez un nouveau projet "Les Mets Chics"
3. Activez l'API "Google+ API"

#### Créer des identifiants OAuth 2.0
1. Menu **APIs & Services** > **Credentials**
2. **Create Credentials** > **OAuth client ID**
3. Configurez l'écran de consentement :
   - Type : Externe
   - Nom : Les Mets Chics
   - Scopes : email, profile, openid
4. Créez l'ID client :
   - Type : Application Web
   - Origines autorisées : `http://localhost:3000`, `http://127.0.0.1:3000`
   - URIs de redirection : `http://127.0.0.1:8000/api/auth/google/callback`

#### Configurer les variables d'environnement
Mettez à jour `les-mets-chics-backend/.env` :
```env
GOOGLE_CLIENT_ID=votre-client-id.apps.googleusercontent.com
GOOGLE_CLIENT_SECRET=votre-client-secret
GOOGLE_REDIRECT_URI=http://127.0.0.1:8000/api/auth/google/callback
```

### 2. Lancer l'application

#### Backend
```bash
cd les-mets-chics-backend
php artisan migrate
php artisan storage:link
php artisan serve
```

#### Frontend
```bash
cd les-mets-chics-frontend
npm install
npm run dev
```

### 3. Tester

1. Ouvrez http://localhost:3000
2. Allez sur "Inscription"
3. Testez les deux méthodes :
   - Formulaire avec/sans photo
   - Bouton "Continuer avec Google"

## 📁 Structure des fichiers

### Backend
```
les-mets-chics-backend/
├── app/
│   ├── Http/Controllers/Api/
│   │   ├── AuthController.php (modifié - upload avatar)
│   │   └── GoogleAuthController.php (nouveau)
│   ├── Models/
│   │   └── User.php (modifié - avatar, google_id)
│   ├── Events/
│   │   ├── UserRegistered.php
│   │   └── UserActivated.php
│   ├── Listeners/
│   │   ├── SendNewUserNotificationToAdmins.php
│   │   └── SendAccountActivatedNotification.php
│   └── Observers/
│       └── UserObserver.php
├── database/migrations/
│   └── 2025_11_27_130259_add_avatar_to_users_table.php
├── config/
│   └── services.php (modifié - config Google)
├── routes/
│   └── api.php (modifié - routes Google)
└── .env (modifié - variables Google)
```

### Frontend
```
les-mets-chics-frontend/
├── pages/
│   ├── inscription.vue (modifié - upload + Google)
│   ├── connexion.vue (modifié - bouton Google)
│   └── auth/
│       └── callback.vue (nouveau)
├── components/
│   ├── UserAvatar.vue (nouveau)
│   └── Header.vue (modifié - affichage avatar)
└── stores/
    └── auth.js (modifié - support FormData)
```

## 🎨 Utilisation du composant Avatar

### Basique
```vue
<UserAvatar 
  :avatar="user.avatar" 
  :name="user.name" 
  size="md" 
/>
```

### Tailles disponibles
- `xs` : 24px (commentaires, listes)
- `sm` : 32px (header, navigation)
- `md` : 40px (par défaut)
- `lg` : 48px (profil utilisateur)
- `xl` : 64px (page profil)

### Exemples d'intégration

#### Dans les commentaires
```vue
<div class="flex gap-3">
  <UserAvatar :avatar="comment.user.avatar" :name="comment.user.name" size="sm" />
  <div>
    <p class="font-bold">{{ comment.user.name }}</p>
    <p>{{ comment.content }}</p>
  </div>
</div>
```

#### Sur une recette
```vue
<div class="flex items-center gap-3">
  <UserAvatar :avatar="recipe.user.avatar" :name="recipe.user.name" size="md" />
  <div>
    <p class="text-sm text-gray-500">Créé par</p>
    <p class="font-semibold">{{ recipe.user.name }}</p>
  </div>
</div>
```

## 🔐 Sécurité

- ✅ Validation des images (types, taille max 2MB)
- ✅ Stockage sécurisé avec Laravel Storage
- ✅ Tokens gérés par Laravel Sanctum
- ✅ Vérification du statut avant connexion
- ✅ Mot de passe aléatoire pour comptes Google
- ✅ Validation admin obligatoire

## 📧 Emails

### Nouvelle inscription
- **Destinataire** : Tous les admins
- **Déclencheur** : Création d'un compte (formulaire ou Google)
- **Contenu** : Nom, email, date d'inscription

### Compte activé
- **Destinataire** : L'utilisateur
- **Déclencheur** : Admin change le statut de "pending" à "active"
- **Contenu** : Message de bienvenue + lien de connexion

## 🐛 Dépannage

### "The [public/storage] link already exists"
C'est normal, le lien symbolique existe déjà.

### Erreur Google OAuth
1. Vérifiez que les variables sont bien dans `.env`
2. Vérifiez les URIs de redirection dans Google Cloud Console
3. Vérifiez que l'API Google+ est activée

### Images ne s'affichent pas
1. Vérifiez que `php artisan storage:link` a été exécuté
2. Vérifiez les permissions du dossier `storage`
3. Vérifiez l'URL dans la réponse API

### Emails non reçus
1. Vérifiez la configuration SMTP dans `.env`
2. Vérifiez Mailtrap ou votre service d'email
3. Vérifiez les logs : `storage/logs/laravel.log`

## 📚 Documentation complète

- `GOOGLE_OAUTH_SETUP.md` - Configuration détaillée Google OAuth
- `IMPLEMENTATION_SUMMARY.md` - Résumé technique de l'implémentation
- `TESTING_CHECKLIST.md` - Liste de tests à effectuer
- `NEXT_STEPS.md` - Prochaines étapes et améliorations
- `components/README_AVATAR.md` - Documentation du composant Avatar

## 🎯 Prochaines étapes suggérées

1. **Configurer Google OAuth** (priorité haute)
2. **Tester toutes les fonctionnalités** (voir TESTING_CHECKLIST.md)
3. **Intégrer les avatars dans les commentaires** (voir NEXT_STEPS.md)
4. **Intégrer les avatars sur les recettes** (voir NEXT_STEPS.md)
5. **Créer une page de profil utilisateur**
6. **Permettre la modification de l'avatar**
7. **Optimiser les images** (compression, CDN)

## 💡 Conseils

- Les avatars Google sont des URLs complètes (commencent par `http`)
- Les avatars locaux sont des chemins relatifs (`avatars/xxx.jpg`)
- Le composant Avatar gère automatiquement les deux cas
- Les initiales sont générées automatiquement si pas d'avatar
- Tous les nouveaux comptes nécessitent une validation admin

## 🤝 Support

Pour toute question ou problème :
1. Consultez les fichiers de documentation
2. Vérifiez les logs Laravel : `storage/logs/laravel.log`
3. Vérifiez la console du navigateur (F12)
4. Vérifiez que toutes les migrations sont exécutées

---

**Bon développement ! 🚀**
