# Réinitialisation du Mot de Passe

## Vue d'ensemble

Système complet de réinitialisation de mot de passe avec envoi d'email sécurisé et validation par token.

## Fonctionnalités

### 1. Demande de réinitialisation
- **Page** : `/mot-de-passe-oublie`
- **Route API** : `POST /api/password/forgot`
- **Processus** :
  1. L'utilisateur entre son email
  2. Un token unique est généré et stocké dans la base de données
  3. Un email avec un lien de réinitialisation est envoyé
  4. Le lien est valable 60 minutes

### 2. Réinitialisation du mot de passe
- **Page** : `/reinitialiser-mot-de-passe?token=xxx&email=xxx`
- **Route API** : `POST /api/password/reset`
- **Processus** :
  1. L'utilisateur clique sur le lien reçu par email
  2. Il entre son nouveau mot de passe (min 8 caractères)
  3. Le token est vérifié et validé
  4. Le mot de passe est mis à jour
  5. Tous les tokens d'accès existants sont révoqués
  6. Redirection automatique vers la page de connexion

## Sécurité

### Protection des données
- Les tokens sont hashés avec bcrypt avant stockage
- Les tokens expirent après 60 minutes
- Les anciens tokens sont supprimés lors d'une nouvelle demande
- Message générique pour ne pas révéler si un email existe

### Validation
- Email requis et valide
- Mot de passe minimum 8 caractères
- Confirmation du mot de passe obligatoire
- Vérification de l'expiration du token
- Révocation de tous les tokens d'accès après réinitialisation

## Base de données

### Table `password_reset_tokens`
```sql
- email (primary key)
- token (hashed)
- created_at
```

## Email

### Template
- Design responsive et élégant
- Logo et branding Les Mets Chics
- Bouton CTA clair
- Lien de secours en texte brut
- Avertissement sur la validité (60 minutes)
- Message de sécurité

### Configuration
Variables d'environnement requises dans `.env` :
```env
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_FROM_ADDRESS="noreply@lesmetschics.com"
MAIL_FROM_NAME="Les Mets Chics"
FRONTEND_URL=http://localhost:3000
```

## Interface utilisateur

### Page "Mot de passe oublié"
- Formulaire simple avec un champ email
- Messages de succès/erreur clairs
- Lien de retour vers la connexion
- Design cohérent avec le reste de l'application

### Page "Réinitialiser mot de passe"
- Affichage de l'email (readonly)
- Champs de mot de passe avec affichage/masquage
- Validation en temps réel
- Messages de succès/erreur
- Redirection automatique après succès
- Lien pour demander un nouveau token si expiré

## Flux utilisateur

1. **Oubli du mot de passe**
   - Clic sur "Mot de passe oublié ?" sur la page de connexion
   - Redirection vers `/mot-de-passe-oublie`

2. **Demande de réinitialisation**
   - Saisie de l'email
   - Soumission du formulaire
   - Message de confirmation (même si l'email n'existe pas)

3. **Réception de l'email**
   - Email reçu avec le lien de réinitialisation
   - Lien valable 60 minutes

4. **Réinitialisation**
   - Clic sur le lien dans l'email
   - Redirection vers `/reinitialiser-mot-de-passe`
   - Saisie du nouveau mot de passe
   - Confirmation du mot de passe
   - Soumission

5. **Connexion**
   - Message de succès
   - Redirection automatique vers `/connexion`
   - Connexion avec le nouveau mot de passe

## Tests

### Test manuel
1. Aller sur `/connexion`
2. Cliquer sur "Mot de passe oublié ?"
3. Entrer un email valide
4. Vérifier la réception de l'email (Mailtrap)
5. Cliquer sur le lien dans l'email
6. Entrer un nouveau mot de passe
7. Se connecter avec le nouveau mot de passe

### Cas d'erreur à tester
- Email inexistant (doit afficher le même message)
- Token expiré (après 60 minutes)
- Token invalide
- Mots de passe ne correspondent pas
- Mot de passe trop court (< 8 caractères)

## API Endpoints

### POST /api/password/forgot
**Body:**
```json
{
  "email": "user@example.com"
}
```

**Response (200):**
```json
{
  "message": "Si cet email existe dans notre système, vous recevrez un lien de réinitialisation."
}
```

### POST /api/password/reset
**Body:**
```json
{
  "email": "user@example.com",
  "token": "random_token_string",
  "password": "newpassword123",
  "password_confirmation": "newpassword123"
}
```

**Response (200):**
```json
{
  "message": "Votre mot de passe a été réinitialisé avec succès. Vous pouvez maintenant vous connecter."
}
```

**Response (422) - Erreur:**
```json
{
  "message": "Token de réinitialisation invalide ou expiré."
}
```

## Fichiers créés

### Backend
- `app/Http/Controllers/Api/PasswordResetController.php`
- `app/Mail/PasswordResetMail.php`
- `resources/views/emails/password-reset.blade.php`
- Routes ajoutées dans `routes/api.php`

### Frontend
- `pages/mot-de-passe-oublie.vue`
- `pages/reinitialiser-mot-de-passe.vue`

## Améliorations futures possibles

- Limitation du nombre de demandes par IP (rate limiting)
- Historique des réinitialisations
- Notification par email lors d'un changement de mot de passe
- Authentification à deux facteurs (2FA)
- Questions de sécurité supplémentaires
