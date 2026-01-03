# 🔍 Guide de diagnostic Google OAuth

## ✅ Améliorations apportées

### 1. Contrôleur amélioré avec logs détaillés
Le `GoogleAuthController` a été amélioré avec :
- ✅ Logs détaillés à chaque étape
- ✅ Gestion d'erreur spécifique par type d'exception
- ✅ Messages d'erreur clairs pour l'utilisateur
- ✅ Vérification de la configuration avant redirection
- ✅ Route de test `/api/auth/google/test`

### 2. Types d'erreurs gérées
- **InvalidStateException** : Session expirée ou problème CSRF
- **ClientException** : Erreur de communication avec Google (credentials invalides)
- **Exception générale** : Autres erreurs avec message détaillé

### 3. Route de test ajoutée
Nouvelle route : `GET /api/auth/google/test`
- Vérifie la présence des credentials
- Affiche la configuration actuelle
- Retourne un statut OK ou ERREUR

## 🧪 Comment tester

### Étape 1 : Tester la configuration
```bash
# Ouvrez dans votre navigateur ou utilisez curl
http://127.0.0.1:8000/api/auth/google/test
```

Vous devriez voir :
```json
{
  "message": "Configuration Google OAuth",
  "config": {
    "client_id": "Configuré ✓",
    "client_secret": "Configuré ✓",
    "redirect_uri": "http://127.0.0.1:8000/api/auth/google/callback",
    "frontend_url": "http://localhost:3000"
  },
  "status": "OK"
}
```

### Étape 2 : Vérifier les logs
Les logs sont maintenant très détaillés. Consultez-les :
```bash
cd les-mets-chics-backend
tail -f storage/logs/laravel.log
```

### Étape 3 : Tester l'authentification
1. Allez sur http://localhost:3000/inscription
2. Cliquez sur "Continuer avec Google"
3. Surveillez les logs en temps réel

## 📋 Checklist de diagnostic

### ✅ Configuration Backend
- [ ] `GOOGLE_CLIENT_ID` est défini dans `.env`
- [ ] `GOOGLE_CLIENT_SECRET` est défini dans `.env`
- [ ] `GOOGLE_REDIRECT_URI` est `http://127.0.0.1:8000/api/auth/google/callback`
- [ ] `FRONTEND_URL` est `http://localhost:3000`
- [ ] `php artisan config:clear` exécuté
- [ ] `php artisan cache:clear` exécuté

### ✅ Configuration Google Cloud Console
- [ ] Projet créé sur https://console.cloud.google.com/
- [ ] API "Google+ API" ou "Google Identity" activée
- [ ] Écran de consentement OAuth configuré
- [ ] Type d'application : **Application Web**
- [ ] **Origines JavaScript autorisées** :
  - `http://localhost:3000`
  - `http://127.0.0.1:3000`
- [ ] **URI de redirection autorisés** :
  - `http://127.0.0.1:8000/api/auth/google/callback`
  - `http://localhost:8000/api/auth/google/callback` (optionnel)

### ✅ Configuration Frontend
- [ ] `NUXT_PUBLIC_API_BASE` est `http://127.0.0.1:8000/api`
- [ ] Frontend redémarre après modification du `.env`

## 🔴 Erreurs courantes et solutions

### Erreur : "Configuration Google OAuth manquante"
**Cause** : Les variables `GOOGLE_CLIENT_ID` ou `GOOGLE_CLIENT_SECRET` ne sont pas définies.

**Solution** :
1. Vérifiez votre fichier `.env`
2. Exécutez `php artisan config:clear`
3. Testez avec `/api/auth/google/test`

### Erreur : "Session expirée. Veuillez réessayer."
**Cause** : `InvalidStateException` - Le state CSRF ne correspond pas.

**Solution** :
1. Videz le cache du navigateur
2. Réessayez l'authentification
3. Vérifiez que vous n'avez pas plusieurs onglets ouverts

### Erreur : "Erreur de communication avec Google"
**Cause** : Les credentials Google sont invalides ou l'URI de redirection ne correspond pas.

**Solution** :
1. Vérifiez que `GOOGLE_CLIENT_ID` et `GOOGLE_CLIENT_SECRET` sont corrects
2. Vérifiez dans Google Cloud Console que l'URI de redirection est **exactement** :
   ```
   http://127.0.0.1:8000/api/auth/google/callback
   ```
3. Vérifiez que l'API Google+ est activée

### Erreur : "redirect_uri_mismatch"
**Cause** : L'URI de redirection ne correspond pas à celle configurée dans Google Cloud Console.

**Solution** :
1. Allez sur https://console.cloud.google.com/
2. APIs & Services > Credentials
3. Cliquez sur votre OAuth 2.0 Client ID
4. Ajoutez **exactement** : `http://127.0.0.1:8000/api/auth/google/callback`
5. Sauvegardez et réessayez

### Erreur : "Access blocked: This app's request is invalid"
**Cause** : L'écran de consentement OAuth n'est pas configuré correctement.

**Solution** :
1. Allez sur https://console.cloud.google.com/
2. APIs & Services > OAuth consent screen
3. Configurez :
   - Type : Externe
   - Nom de l'application : Les Mets Chics
   - Email d'assistance : votre email
   - Scopes : email, profile, openid
4. Sauvegardez et publiez l'application

## 📊 Interpréter les logs

### Log normal (succès)
```
[INFO] Google OAuth: Redirection initiée
[INFO] Google OAuth: Callback reçu
[INFO] Google OAuth: Utilisateur récupéré (google_id: xxx, email: xxx)
[INFO] Google OAuth: Nouvel utilisateur créé (user_id: xxx)
```

### Log avec erreur de configuration
```
[ERROR] Google OAuth: Configuration manquante
  - client_id: manquant
  - client_secret: présent
```

### Log avec erreur Google
```
[ERROR] Google OAuth: Erreur de communication avec Google
  - error: Client error: 401
  - response: Invalid credentials
```

## 🚀 Test complet étape par étape

### 1. Vérifier la configuration
```bash
# Dans le navigateur
http://127.0.0.1:8000/api/auth/google/test
```

### 2. Tester la redirection
```bash
# Dans le navigateur
http://127.0.0.1:8000/api/auth/google
```
Vous devriez être redirigé vers Google.

### 3. Surveiller les logs
```bash
# Terminal 1 : Backend
cd les-mets-chics-backend
php artisan serve

# Terminal 2 : Logs
cd les-mets-chics-backend
tail -f storage/logs/laravel.log

# Terminal 3 : Frontend
cd les-mets-chics-frontend
npm run dev
```

### 4. Tester l'inscription
1. Allez sur http://localhost:3000/inscription
2. Cliquez sur "Continuer avec Google"
3. Autorisez l'application
4. Vérifiez les logs pour voir le flux complet

## 🔧 Commandes utiles

### Vider les caches
```bash
cd les-mets-chics-backend
php artisan config:clear
php artisan cache:clear
php artisan route:clear
```

### Voir les routes
```bash
php artisan route:list --path=auth
```

### Tester la configuration en ligne de commande
```bash
php artisan tinker
>>> config('services.google.client_id')
>>> config('services.google.client_secret')
>>> config('services.google.redirect')
```

### Voir les derniers logs
```bash
# Windows PowerShell
Get-Content storage/logs/laravel.log -Tail 50

# Windows CMD
type storage\logs\laravel.log | more
```

## 📝 Informations importantes

### Différence localhost vs 127.0.0.1
- **Backend** : Utilise `127.0.0.1:8000`
- **Frontend** : Utilise `localhost:3000`
- **Google OAuth** : Redirige vers `127.0.0.1:8000`

C'est normal et géré par la configuration CORS.

### Stateless mode
Le contrôleur utilise `->stateless()` car c'est une API sans sessions.
C'est la bonne approche pour une architecture frontend/backend séparée.

### Sécurité
- Les mots de passe des comptes Google sont aléatoires et hashés
- Les tokens sont gérés par Laravel Sanctum
- Tous les nouveaux comptes sont en statut "pending"

## 🎯 Prochaines étapes

Une fois que Google OAuth fonctionne :
1. Testez l'inscription avec formulaire + photo
2. Testez la connexion classique
3. Testez l'activation d'un compte par un admin
4. Vérifiez la réception des emails

---

**Besoin d'aide ?** Consultez les logs et ce guide de diagnostic !
