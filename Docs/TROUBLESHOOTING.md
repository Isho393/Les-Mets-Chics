# 🔧 Guide de dépannage

## Erreur "Erreur lors de l'authentification Google"

### Symptôme
Après avoir cliqué sur "Continuer avec Google", vous voyez le message :
```
Erreur
Erreur lors de l'authentification Google
```

### Causes possibles et solutions

#### 1. Cache de configuration non vidé
**Solution** :
```bash
cd les-mets-chics-backend
php artisan config:clear
php artisan cache:clear
```

#### 2. Variables Google OAuth non configurées
**Vérification** :
```bash
cd les-mets-chics-backend
php artisan tinker --execute="echo config('services.google.client_id');"
```

Si vide ou `null`, vérifiez votre fichier `.env` :
```env
GOOGLE_CLIENT_ID=votre-client-id.apps.googleusercontent.com
GOOGLE_CLIENT_SECRET=votre-client-secret
GOOGLE_REDIRECT_URI=http://127.0.0.1:8000/api/auth/google/callback
```

**Solution** : Configurez Google OAuth (voir `QUICK_START.md`)

#### 3. URL du frontend incorrecte
**Vérification** :
Le fichier `les-mets-chics-frontend/.env` doit pointer vers la bonne URL :
```env
NUXT_PUBLIC_API_BASE=http://127.0.0.1:8000/api
```

**Solution** : Mettez à jour le fichier et redémarrez le serveur frontend

#### 4. URIs de redirection Google incorrects
**Vérification** :
Dans Google Cloud Console, vérifiez que les URIs de redirection incluent :
```
http://127.0.0.1:8000/api/auth/google/callback
http://localhost:8000/api/auth/google/callback
```

**Solution** : Ajoutez les deux URIs dans Google Cloud Console

#### 5. Serveur backend non démarré
**Vérification** :
Ouvrez http://127.0.0.1:8000/api/auth/google dans votre navigateur

**Solution** : Démarrez le serveur backend :
```bash
cd les-mets-chics-backend
php artisan serve
```

#### 6. CORS non configuré
**Vérification** :
Ouvrez la console du navigateur (F12) et cherchez des erreurs CORS

**Solution** : Vérifiez que `FRONTEND_URL` est bien configuré dans `.env` :
```env
FRONTEND_URL=http://localhost:3000
```

## Autres erreurs courantes

### "redirect_uri_mismatch"
**Cause** : L'URI de redirection ne correspond pas à celle configurée dans Google Cloud Console

**Solution** :
1. Vérifiez l'URI dans `.env` : `GOOGLE_REDIRECT_URI`
2. Vérifiez l'URI dans Google Cloud Console
3. Assurez-vous qu'elles sont identiques (attention à `localhost` vs `127.0.0.1`)

### "Client ID not found"
**Cause** : Le Client ID n'est pas configuré ou est incorrect

**Solution** :
1. Vérifiez que `GOOGLE_CLIENT_ID` est dans `.env`
2. Vérifiez qu'il n'y a pas d'espaces ou de retours à la ligne
3. Exécutez `php artisan config:clear`

### "The [public/storage] link already exists"
**Cause** : Le lien symbolique existe déjà

**Solution** : C'est normal, ignorez ce message

### Images ne s'affichent pas
**Cause** : Le lien symbolique n'est pas créé

**Solution** :
```bash
cd les-mets-chics-backend
php artisan storage:link
```

### Emails non reçus
**Cause** : Configuration SMTP incorrecte

**Solution** :
1. Vérifiez les variables MAIL dans `.env`
2. Vérifiez Mailtrap ou votre service d'email
3. Consultez les logs : `storage/logs/laravel.log`

## Commandes de diagnostic

### Vérifier la configuration
```bash
php artisan config:show services.google
```

### Vérifier les routes
```bash
php artisan route:list --path=auth
```

### Vérifier les événements
```bash
php artisan event:list
```

### Voir les logs en temps réel
```bash
tail -f storage/logs/laravel.log
```

### Tester la connexion à la base de données
```bash
php artisan tinker --execute="DB::connection()->getPdo();"
```

## Logs utiles

### Backend
```bash
cd les-mets-chics-backend
Get-Content storage/logs/laravel.log -Tail 50
```

### Frontend
Ouvrez la console du navigateur (F12) et consultez l'onglet "Console"

## Réinitialisation complète

Si rien ne fonctionne, réinitialisez tout :

```bash
# Backend
cd les-mets-chics-backend
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
composer dump-autoload

# Frontend
cd les-mets-chics-frontend
rm -rf .nuxt
rm -rf node_modules/.cache
npm run dev
```

## Support

Si le problème persiste :
1. Vérifiez les logs : `storage/logs/laravel.log`
2. Vérifiez la console du navigateur (F12)
3. Consultez la documentation Google OAuth
4. Vérifiez que toutes les migrations sont exécutées : `php artisan migrate:status`

## Checklist de vérification rapide

- [ ] Backend démarré sur http://127.0.0.1:8000
- [ ] Frontend démarré sur http://localhost:3000
- [ ] Variables Google configurées dans `.env`
- [ ] Cache vidé : `php artisan config:clear`
- [ ] URIs de redirection configurés dans Google Cloud Console
- [ ] Migrations exécutées : `php artisan migrate`
- [ ] Lien symbolique créé : `php artisan storage:link`
- [ ] URL frontend correcte dans `.env` : `NUXT_PUBLIC_API_BASE=http://127.0.0.1:8000/api`

---

**Astuce** : Pour tester sans Google OAuth, utilisez simplement le formulaire d'inscription classique avec une photo !
