# Configuration Google OAuth

## Étapes pour configurer Google OAuth

### 1. Créer un projet Google Cloud

1. Allez sur [Google Cloud Console](https://console.cloud.google.com/)
2. Créez un nouveau projet ou sélectionnez un projet existant
3. Activez l'API "Google+ API" ou "Google Identity"

### 2. Créer des identifiants OAuth 2.0

1. Dans le menu, allez dans **APIs & Services** > **Credentials**
2. Cliquez sur **Create Credentials** > **OAuth client ID**
3. Configurez l'écran de consentement OAuth si ce n'est pas déjà fait :
   - Type d'application : **Externe**
   - Nom de l'application : **Les Mets Chics**
   - Email d'assistance utilisateur : votre email
   - Domaine autorisé : `localhost` (pour le développement)
   - Scopes : `email`, `profile`, `openid`

4. Créez l'ID client OAuth :
   - Type d'application : **Application Web**
   - Nom : **Les Mets Chics - Web**
   - Origines JavaScript autorisées :
     - `http://localhost:3000` (frontend)
     - `http://127.0.0.1:3000`
   - URI de redirection autorisés :
     - `http://127.0.0.1:8000/api/auth/google/callback`
     - `http://localhost:8000/api/auth/google/callback`

5. Copiez le **Client ID** et le **Client Secret**

### 3. Configurer les variables d'environnement

Mettez à jour votre fichier `.env` :

```env
GOOGLE_CLIENT_ID=votre-client-id-google.apps.googleusercontent.com
GOOGLE_CLIENT_SECRET=votre-client-secret
GOOGLE_REDIRECT_URI=http://127.0.0.1:8000/api/auth/google/callback
```

### 4. Tester l'authentification

1. Démarrez le backend : `php artisan serve`
2. Démarrez le frontend : `npm run dev`
3. Allez sur la page d'inscription ou de connexion
4. Cliquez sur "Continuer avec Google"
5. Autorisez l'application
6. Vous serez redirigé vers le frontend avec votre compte créé/connecté

## Flux d'authentification

### Inscription/Connexion via Google

1. L'utilisateur clique sur "Continuer avec Google"
2. Redirection vers Google OAuth (`/api/auth/google`)
3. L'utilisateur autorise l'application
4. Google redirige vers `/api/auth/google/callback`
5. Le backend :
   - Vérifie si l'utilisateur existe (par `google_id` ou `email`)
   - Si existe : met à jour `google_id` et `avatar` si nécessaire
   - Si n'existe pas : crée un nouveau compte avec statut `pending`
   - Envoie une notification aux admins
6. Redirection vers le frontend :
   - Si compte actif : `/auth/callback?token=xxx&status=success`
   - Si compte pending : `/auth/callback?status=pending&message=...`
   - Si erreur : `/auth/callback?status=error&message=...`

### Validation du compte

1. Un admin active le compte via le panel Filament
2. L'événement `UserActivated` est déclenché
3. Un email est envoyé à l'utilisateur
4. L'utilisateur peut maintenant se connecter

## Données récupérées de Google

- `name` : Nom complet
- `email` : Adresse email
- `google_id` : ID unique Google
- `avatar` : URL de la photo de profil Google

## Sécurité

- Les comptes créés via Google ont un mot de passe aléatoire hashé
- Le statut par défaut est `pending` (nécessite validation admin)
- Le rôle par défaut est `explorateur`
- Les tokens sont gérés par Laravel Sanctum
