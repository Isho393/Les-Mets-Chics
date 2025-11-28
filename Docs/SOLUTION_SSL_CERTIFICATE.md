# ✅ Solution : Erreur SSL Certificate (Windows)

## 🔴 Erreur rencontrée

```
Erreur lors de l'authentification Google: cURL error 60: 
SSL certificate problem: unable to get local issuer certificate
```

## ✅ Solution appliquée (temporaire)

J'ai appliqué une **solution temporaire** pour que vous puissiez tester immédiatement :

### Modifications effectuées

1. **Ajout dans `.env`** :
   ```env
   CURL_VERIFY_SSL=false
   ```

2. **Modification de `config/services.php`** :
   ```php
   'google' => [
       'client_id' => env('GOOGLE_CLIENT_ID'),
       'client_secret' => env('GOOGLE_CLIENT_SECRET'),
       'redirect' => env('GOOGLE_REDIRECT_URI'),
       'guzzle' => [
           'verify' => env('CURL_VERIFY_SSL', true),
       ],
   ],
   ```

3. **Cache vidé** :
   ```bash
   php artisan config:clear
   ```

## 🚀 Tester maintenant

1. **Redémarrez le serveur Laravel** :
   ```bash
   # Arrêtez le serveur (Ctrl+C)
   cd les-mets-chics-backend
   php artisan serve
   ```

2. **Testez Google OAuth** :
   - Allez sur http://localhost:3000/inscription
   - Cliquez sur "Continuer avec Google"
   - ✅ Ça devrait fonctionner !

## ⚠️ Important : Solution permanente recommandée

La solution actuelle désactive la vérification SSL, ce qui est **acceptable en développement** mais **DANGEREUX en production**.

### Solution permanente (recommandée)

#### Option 1 : Script automatique (le plus simple)

```powershell
# Dans PowerShell (en tant qu'administrateur)
cd les-mets-chics-backend
.\install-ssl-certificate.ps1
```

Ce script va :
- ✅ Télécharger le certificat CA Bundle
- ✅ Configurer automatiquement php.ini
- ✅ Tester la configuration
- ✅ Créer une sauvegarde de php.ini

#### Option 2 : Installation manuelle

1. **Télécharger le certificat** :
   - Allez sur : https://curl.se/ca/cacert.pem
   - Sauvegardez-le dans : `C:\_programme\php-8-4-7\cacert.pem`

2. **Modifier php.ini** :
   - Ouvrez : `C:\_programme\php-8-4-7\php-8.4.7-nts-Win32-vs17-x64\php.ini`
   - Cherchez et modifiez :
     ```ini
     curl.cainfo = "C:\_programme\php-8-4-7\cacert.pem"
     openssl.cafile = "C:\_programme\php-8-4-7\cacert.pem"
     ```

3. **Supprimer la ligne temporaire** :
   - Dans `.env`, supprimez ou commentez :
     ```env
     # CURL_VERIFY_SSL=false
     ```

4. **Redémarrer le serveur** :
   ```bash
   php artisan config:clear
   php artisan serve
   ```

## 🧪 Vérifier que tout fonctionne

### Test 1 : Configuration PHP
```bash
php -r "echo ini_get('curl.cainfo');"
```
Devrait afficher le chemin du certificat.

### Test 2 : Connexion HTTPS
```bash
php -r "echo file_get_contents('https://www.google.com') ? 'OK' : 'ERREUR';"
```
Devrait afficher `OK`.

### Test 3 : Google OAuth
1. Allez sur http://localhost:3000/inscription
2. Cliquez sur "Continuer avec Google"
3. Devrait fonctionner sans erreur SSL

## 📋 Checklist

### Solution temporaire (actuelle)
- [x] `CURL_VERIFY_SSL=false` ajouté dans `.env`
- [x] `config/services.php` modifié
- [x] Cache vidé
- [x] Serveur redémarré
- [ ] **Tester Google OAuth**

### Solution permanente (à faire)
- [ ] Télécharger `cacert.pem`
- [ ] Configurer `php.ini`
- [ ] Supprimer `CURL_VERIFY_SSL=false` du `.env`
- [ ] Redémarrer le serveur
- [ ] Tester Google OAuth

## 🎯 Résumé

### Maintenant (solution temporaire)
```env
# Dans .env
CURL_VERIFY_SSL=false
```
✅ Fonctionne pour le développement
⚠️ Ne pas utiliser en production

### Plus tard (solution permanente)
```bash
# Exécuter le script
.\install-ssl-certificate.ps1
```
✅ Sécurisé
✅ Fonctionne en développement et production

## 📚 Documentation complète

Pour plus de détails, consultez :
- **[FIX_SSL_CERTIFICATE.md](FIX_SSL_CERTIFICATE.md)** - Guide complet
- Script : `les-mets-chics-backend/install-ssl-certificate.ps1`

## 💡 Pourquoi ce problème ?

Sur Windows, PHP n'inclut pas de certificats SSL par défaut. Il faut les télécharger et les configurer manuellement. C'est un problème courant sur Windows, mais facile à résoudre.

---

**Vous pouvez maintenant tester Google OAuth ! 🎉**

Une fois que ça fonctionne, pensez à installer la solution permanente pour plus de sécurité.
