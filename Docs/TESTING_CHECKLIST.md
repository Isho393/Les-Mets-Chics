# Checklist de test - Authentification Google OAuth & Photos de profil

## ⚙️ Configuration préalable

### Backend
- [ ] Migration exécutée : `php artisan migrate`
- [ ] Lien symbolique créé : `php artisan storage:link`
- [ ] Variables Google OAuth configurées dans `.env`
- [ ] Serveur backend démarré : `php artisan serve`

### Frontend
- [ ] Dépendances installées : `npm install`
- [ ] Serveur frontend démarré : `npm run dev`

### Google Cloud Console
- [ ] Projet créé sur Google Cloud Console
- [ ] API Google+ activée
- [ ] Identifiants OAuth 2.0 créés
- [ ] URIs de redirection configurés
- [ ] Client ID et Secret copiés dans `.env`

## 🧪 Tests fonctionnels

### 1. Inscription avec formulaire (sans photo)
- [ ] Aller sur `/inscription`
- [ ] Remplir le formulaire sans photo
- [ ] Cliquer sur "S'inscrire"
- [ ] ✅ Message de succès affiché
- [ ] ✅ Message indique "Vous recevrez un email une fois votre compte activé"
- [ ] ✅ Email envoyé aux admins
- [ ] ✅ Compte créé avec statut "pending" dans la base de données

### 2. Inscription avec formulaire (avec photo)
- [ ] Aller sur `/inscription`
- [ ] Cliquer sur "Choisir une photo"
- [ ] Sélectionner une image
- [ ] ✅ Prévisualisation affichée
- [ ] Remplir le reste du formulaire
- [ ] Cliquer sur "S'inscrire"
- [ ] ✅ Message de succès affiché
- [ ] ✅ Photo uploadée dans `storage/app/public/avatars`
- [ ] ✅ Compte créé avec avatar dans la base de données

### 3. Inscription avec Google
- [ ] Aller sur `/inscription`
- [ ] Cliquer sur "Continuer avec Google"
- [ ] ✅ Redirection vers Google
- [ ] Autoriser l'application
- [ ] ✅ Redirection vers `/auth/callback?status=pending`
- [ ] ✅ Message "Compte en attente de validation"
- [ ] ✅ Photo de profil Google récupérée
- [ ] ✅ Email envoyé aux admins
- [ ] ✅ Compte créé avec statut "pending" et google_id

### 4. Connexion avec formulaire (compte pending)
- [ ] Aller sur `/connexion`
- [ ] Entrer email et mot de passe d'un compte pending
- [ ] Cliquer sur "Se connecter"
- [ ] ✅ Message d'erreur : "Votre compte est en attente de validation"
- [ ] ✅ Pas de connexion

### 5. Activation de compte par admin
- [ ] Se connecter au panel Filament (`/admin`)
- [ ] Aller dans "Utilisateurs"
- [ ] Trouver un compte avec statut "pending"
- [ ] Changer le statut à "active"
- [ ] Sauvegarder
- [ ] ✅ Email envoyé à l'utilisateur
- [ ] ✅ Vérifier la réception de l'email d'activation

### 6. Connexion avec formulaire (compte actif)
- [ ] Aller sur `/connexion`
- [ ] Entrer email et mot de passe d'un compte actif
- [ ] Cliquer sur "Se connecter"
- [ ] ✅ Connexion réussie
- [ ] ✅ Redirection vers la page d'accueil
- [ ] ✅ Avatar affiché dans le header
- [ ] ✅ Nom affiché dans le header

### 7. Connexion avec Google (compte existant)
- [ ] Se déconnecter
- [ ] Aller sur `/connexion`
- [ ] Cliquer sur "Continuer avec Google"
- [ ] Autoriser l'application
- [ ] ✅ Connexion réussie (si compte actif)
- [ ] ✅ Redirection vers la page d'accueil
- [ ] ✅ Avatar Google affiché dans le header

### 8. Affichage des avatars
- [ ] Connecté, vérifier le header
- [ ] ✅ Avatar affiché (image ou initiales)
- [ ] ✅ Taille correcte (sm)
- [ ] ✅ Forme circulaire
- [ ] Si pas d'avatar : ✅ Initiales affichées sur fond coloré

### 9. Test des initiales
- [ ] Créer un compte sans photo
- [ ] Se connecter
- [ ] ✅ Initiales affichées (première lettre prénom + nom)
- [ ] ✅ Fond dégradé de couleur
- [ ] ✅ Texte blanc et gras

### 10. Test mobile
- [ ] Ouvrir sur mobile ou réduire la fenêtre
- [ ] ✅ Menu hamburger fonctionne
- [ ] ✅ Boutons Google visibles et cliquables
- [ ] ✅ Upload de photo fonctionne
- [ ] ✅ Avatar affiché dans le menu mobile

## 🔍 Vérifications base de données

### Table users
```sql
SELECT id, name, email, avatar, google_id, role, status FROM users;
```

Vérifier :
- [ ] Colonne `avatar` existe
- [ ] Colonne `google_id` existe
- [ ] Avatars locaux : chemin `avatars/xxx.jpg`
- [ ] Avatars Google : URL complète `https://lh3.googleusercontent.com/...`
- [ ] Statut "pending" pour nouveaux comptes
- [ ] Statut "active" après activation admin

## 📧 Vérifications emails

### Mailtrap (ou autre service SMTP)
- [ ] Email "Nouvelle inscription" reçu par les admins
- [ ] Email "Compte activé" reçu par l'utilisateur
- [ ] Contenu des emails correct
- [ ] Liens fonctionnels dans les emails

## 🐛 Tests d'erreurs

### Upload de fichier invalide
- [ ] Essayer d'uploader un fichier > 2MB
- [ ] ✅ Message d'erreur affiché
- [ ] Essayer d'uploader un PDF
- [ ] ✅ Message d'erreur affiché

### Google OAuth sans configuration
- [ ] Retirer les variables Google du `.env`
- [ ] Cliquer sur "Continuer avec Google"
- [ ] ✅ Erreur gérée proprement

### Compte suspendu
- [ ] Suspendre un compte via Filament
- [ ] Essayer de se connecter
- [ ] ✅ Message : "Votre compte a été suspendu"

## 📊 Résultats

| Test | Statut | Notes |
|------|--------|-------|
| Inscription formulaire sans photo | ⬜ | |
| Inscription formulaire avec photo | ⬜ | |
| Inscription Google | ⬜ | |
| Connexion compte pending | ⬜ | |
| Activation compte | ⬜ | |
| Connexion compte actif | ⬜ | |
| Connexion Google | ⬜ | |
| Affichage avatars | ⬜ | |
| Affichage initiales | ⬜ | |
| Responsive mobile | ⬜ | |

## 🎯 Critères de succès

- ✅ Tous les tests passent
- ✅ Aucune erreur dans la console
- ✅ Emails envoyés correctement
- ✅ Avatars affichés partout
- ✅ Expérience utilisateur fluide
- ✅ Messages clairs et informatifs
