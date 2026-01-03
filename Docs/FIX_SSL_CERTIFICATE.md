# 🔒 Correction du problème SSL Certificate (Windows)

## 🔴 Erreur rencontrée

```
cURL error 60: SSL certificate problem: unable to get local issuer certificate
```

Cette erreur se produit car PHP/cURL ne trouve pas les certificats SSL pour vérifier les connexions HTTPS (comme avec Google).

## ✅ Solution 1 : Télécharger et configurer le certificat CA (RECOMMANDÉ)

### Étape 1 : Télécharger le certificat CA Bundle

1. Allez sur : https://curl.se/ca/cacert.pem
2. Téléchargez le fichier `cacert.pem`
3. Sauvegardez-le dans un endroit sûr, par exemple :
   ```
   C:\_programme\php-8-4-7\cacert.pem
   ```

### Étape 2 : Configurer PHP

1. Ouvrez votre fichier `php.ini` :
   ```
   C:\_programme\php-8-4-7\php-8.4.7-nts-Win32-vs17-x64\php.ini
   ```

2. Cherchez la ligne (Ctrl+F) :
   ```ini
   ;curl.cainfo =
   ```

3. Décommentez-la et ajoutez le chemin vers le certificat :
   ```ini
   curl.cainfo = "C:\_programme\php-8-4-7\cacert.pem"
   ```

4. Cherchez aussi :
   ```ini
   ;openssl.cafile=
   ```

5. Décommentez-la et ajoutez le même chemin :
   ```ini
   openssl.cafile = "C:\_programme\php-8-4-7\cacert.pem"
   ```

### Étape 3 : Redémarrer le serveur

```bash
# Arrêtez le serveur Laravel (Ctrl+C)
# Puis relancez-le
cd les-mets-chics-backend
php artisan serve
```

### Étape 4 : Tester

1. Allez sur http://localhost:3000/inscription
2. Cliquez sur "Continuer avec Google"
3. ✅ Ça devrait fonctionner !

## ✅ Solution 2 : Désactiver la vérification SSL (DÉVELOPPEMENT UNIQUEMENT)

⚠️ **ATTENTION** : Cette solution est UNIQUEMENT pour le développement local. Ne JAMAIS utiliser en production !

### Option A : Via le fichier .env

Ajoutez dans votre `.env` :
```env
CURL_VERIFY_SSL=false
```

Puis modifiez `config/services.php` :
```php
'google' => [
    'client_id' => env('GOOGLE_CLIENT_ID'),
    'client_secret' => env('GOOGLE_CLIENT_SECRET'),
    'redirect' => env('GOOGLE_REDIRECT_URI', env('APP_URL') . '/api/auth/google/callback'),
    'guzzle' => [
        'verify' => env('CURL_VERIFY_SSL', true),
    ],
],
```

### Option B : Directement dans le contrôleur (temporaire)

Modifiez `GoogleAuthController.php` :
```php
public function redirectToGoogle()
{
    try {
        Log::info('Google OAuth: Redirection initiée');
        
        if (!config('services.google.client_id') || !config('services.google.client_secret')) {
            Log::error('Google OAuth: Configuration manquante');
            $frontendUrl = config('app.frontend_url', 'http://localhost:3000');
            return redirect()->away("{$frontendUrl}/auth/callback?status=error&message=" . urlencode('Configuration Google OAuth manquante'));
        }

        // Désactiver la vérification SSL (DÉVELOPPEMENT UNIQUEMENT)
        return Socialite::driver('google')
            ->stateless()
            ->with(['verify' => false])
            ->redirect();
            
    } catch (\Exception $e) {
        Log::error('Google OAuth: Erreur lors de la redirection', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        
        $frontendUrl = config('app.frontend_url', 'http://localhost:3000');
        return redirect()->away("{$frontendUrl}/auth/callback?status=error&message=" . urlencode('Erreur de configuration Google OAuth'));
    }
}
```

## 🎯 Quelle solution choisir ?

### Pour le développement local
- **Solution 1** (certificat CA) : ✅ Recommandé, plus sûr
- **Solution 2** (désactiver SSL) : ⚠️ Acceptable mais moins sûr

### Pour la production
- **Solution 1** UNIQUEMENT : ✅ Obligatoire
- **Solution 2** : ❌ JAMAIS en production

## 🧪 Vérifier que ça fonctionne

### Test 1 : Vérifier la configuration PHP
```bash
cd les-mets-chics-backend
php -r "echo ini_get('curl.cainfo');"
```

Devrait afficher :
```
C:\_programme\php-8-4-7\cacert.pem
```

### Test 2 : Tester une requête HTTPS
```bash
php -r "echo file_get_contents('https://www.google.com') ? 'OK' : 'ERREUR';"
```

Devrait afficher :
```
OK
```

### Test 3 : Tester Google OAuth
1. Allez sur http://localhost:3000/inscription
2. Cliquez sur "Continuer avec Google"
3. Vous devriez être redirigé vers Google sans erreur

## 📝 Commandes rapides

### Télécharger le certificat (PowerShell)
```powershell
# Créer le dossier si nécessaire
New-Item -ItemType Directory -Force -Path "C:\_programme\php-8-4-7"

# Télécharger le certificat
Invoke-WebRequest -Uri "https://curl.se/ca/cacert.pem" -OutFile "C:\_programme\php-8-4-7\cacert.pem"
```

### Vérifier le fichier php.ini
```bash
php --ini
```

### Voir la configuration actuelle
```bash
php -i | findstr "curl.cainfo"
php -i | findstr "openssl.cafile"
```

## 🔍 Dépannage

### Le certificat ne se télécharge pas
- Téléchargez manuellement depuis : https://curl.se/ca/cacert.pem
- Sauvegardez-le où vous voulez
- Mettez le chemin complet dans `php.ini`

### Le serveur ne redémarre pas
```bash
# Tuez tous les processus PHP
taskkill /F /IM php.exe

# Relancez le serveur
cd les-mets-chics-backend
php artisan serve
```

### L'erreur persiste
1. Vérifiez que le chemin dans `php.ini` est correct
2. Vérifiez que le fichier `cacert.pem` existe
3. Redémarrez le serveur Laravel
4. Videz les caches :
   ```bash
   php artisan config:clear
   php artisan cache:clear
   ```

### Erreur "Unable to find the wrapper https"
Activez l'extension OpenSSL dans `php.ini` :
```ini
extension=openssl
```

## 💡 Pourquoi ce problème sur Windows ?

Sur Windows, PHP n'est pas livré avec un bundle de certificats CA par défaut, contrairement à Linux/Mac. Il faut donc le télécharger et le configurer manuellement.

## 🎯 Résumé rapide

1. **Télécharger** : https://curl.se/ca/cacert.pem
2. **Sauvegarder** : `C:\_programme\php-8-4-7\cacert.pem`
3. **Configurer php.ini** :
   ```ini
   curl.cainfo = "C:\_programme\php-8-4-7\cacert.pem"
   openssl.cafile = "C:\_programme\php-8-4-7\cacert.pem"
   ```
4. **Redémarrer** le serveur Laravel
5. **Tester** Google OAuth

---

**Problème résolu ! 🎉**
