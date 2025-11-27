# 🚀 Guide de démarrage rapide - OAuth & Avatars

## ⏱️ 5 minutes pour tout configurer

### 1️⃣ Configuration Google OAuth (2 min)

#### Créer le projet Google
1. Ouvrez https://console.cloud.google.com/
2. Cliquez sur "Nouveau projet"
3. Nom : **Les Mets Chics**
4. Cliquez sur "Créer"

#### Activer l'API
1. Menu **APIs & Services** > **Library**
2. Recherchez "Google+ API"
3. Cliquez sur "Activer"

#### Créer les identifiants
1. Menu **APIs & Services** > **Credentials**
2. Cliquez sur **Create Credentials** > **OAuth client ID**
3. Si demandé, configurez l'écran de consentement :
   - Type : **Externe**
   - Nom : **Les Mets Chics**
   - Email : votre email
   - Cliquez sur "Enregistrer et continuer" (3 fois)
4. Revenez à **Credentials** > **Create Credentials** > **OAuth client ID**
5. Type d'application : **Application Web**
6. Nom : **Les Mets Chics Web**
7. Origines JavaScript autorisées :
   ```
   http://localhost:3000
   http://127.0.0.1:3000
   ```
8. URI de redirection autorisés :
   ```
   http://127.0.0.1:8000/api/auth/google/callback
   http://localhost:8000/api/auth/google/callback
   ```
9. Cliquez sur **Créer**
10. **COPIEZ** le Client ID et le Client Secret

### 2️⃣ Configuration Backend (1 min)

#### Mettre à jour .env
Ouvrez `les-mets-chics-backend/.env` et ajoutez :

```env
GOOGLE_CLIENT_ID=votre-client-id.apps.googleusercontent.com
GOOGLE_CLIENT_SECRET=votre-client-secret
GOOGLE_REDIRECT_URI=http://127.0.0.1:8000/api/auth/google/callback
```

#### Exécuter les migrations
```bash
cd les-mets-chics-backend
php artisan migrate
php artisan storage:link
```

### 3️⃣ Lancer l'application (1 min)

#### Terminal 1 - Backend
```bash
cd les-mets-chics-backend
php artisan serve
```

#### Terminal 2 - Frontend
```bash
cd les-mets-chics-frontend
npm run dev
```

### 4️⃣ Tester (1 min)

1. Ouvrez http://localhost:3000
2. Cliquez sur **Inscription**
3. Cliquez sur **Continuer avec Google**
4. Autorisez l'application
5. ✅ Vous devriez voir "Compte en attente de validation"

## 🎯 Vérification rapide

### ✅ Backend OK si :
- Serveur démarre sur http://127.0.0.1:8000
- Pas d'erreur dans le terminal
- Route `/api/auth/google` accessible

### ✅ Frontend OK si :
- Serveur démarre sur http://localhost:3000
- Page d'accueil s'affiche
- Bouton Google visible sur `/inscription`

### ✅ Google OAuth OK si :
- Clic sur "Continuer avec Google" redirige vers Google
- Après autorisation, retour sur votre site
- Message de confirmation affiché

## 🐛 Problèmes courants

### "redirect_uri_mismatch"
**Solution** : Vérifiez que l'URI dans Google Cloud Console correspond exactement à celle dans `.env`

### "Client ID not found"
**Solution** : Vérifiez que vous avez bien copié le Client ID dans `.env`

### "The [public/storage] link already exists"
**Solution** : C'est normal, ignorez ce message

### Bouton Google ne fait rien
**Solution** : Vérifiez la console du navigateur (F12) pour voir les erreurs

## 📝 Commandes utiles

```bash
# Voir les routes
php artisan route:list --path=auth

# Voir les événements
php artisan event:list

# Nettoyer le cache
php artisan config:clear
php artisan cache:clear

# Voir les logs
tail -f storage/logs/laravel.log
```

## 🎨 Test rapide des avatars

### Avec photo
1. Allez sur `/inscription`
2. Cliquez sur "Choisir une photo"
3. Sélectionnez une image
4. ✅ Prévisualisation s'affiche
5. Remplissez le formulaire
6. Cliquez sur "S'inscrire"

### Sans photo
1. Allez sur `/inscription`
2. Remplissez le formulaire sans photo
3. Cliquez sur "S'inscrire"
4. Activez le compte via Filament
5. Connectez-vous
6. ✅ Initiales affichées dans le header

## 🔐 Activer un compte

1. Allez sur http://127.0.0.1:8000/admin
2. Connectez-vous (créez un admin si nécessaire)
3. Menu **Utilisateurs**
4. Trouvez le compte "pending"
5. Changez le statut à "active"
6. Sauvegardez
7. ✅ Email envoyé à l'utilisateur

## 📧 Vérifier les emails

### Mailtrap
1. Allez sur https://mailtrap.io/
2. Connectez-vous
3. Inbox > **Les Mets Chics**
4. ✅ Vous devriez voir les emails

### Logs
```bash
tail -f storage/logs/laravel.log | grep "mail"
```

## 🎉 C'est tout !

Vous avez maintenant :
- ✅ Authentification Google OAuth fonctionnelle
- ✅ Upload de photos de profil
- ✅ Affichage des avatars
- ✅ Notifications par email

## 📚 Pour aller plus loin

- `README_OAUTH_AVATAR.md` - Documentation complète
- `TESTING_CHECKLIST.md` - Tests détaillés
- `NEXT_STEPS.md` - Améliorations futures
- `API_ROUTES.md` - Documentation API

## 💡 Astuce

Pour tester rapidement sans Google OAuth, utilisez simplement le formulaire d'inscription classique avec une photo !

---

**Besoin d'aide ?** Consultez les logs :
- Backend : `storage/logs/laravel.log`
- Frontend : Console du navigateur (F12)
