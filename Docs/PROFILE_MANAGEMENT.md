# Gestion du Profil Utilisateur

## Fonctionnalités implémentées

### 1. Modification du profil
- **Route API** : `POST /api/user/profile` (authentification requise)
- **Champs modifiables** :
  - Nom de l'utilisateur
  - Avatar (upload d'image)
- **Validation** :
  - Nom : requis, max 255 caractères
  - Avatar : image (jpeg, png, jpg, gif), max 2MB
- **Gestion de l'avatar** :
  - Suppression automatique de l'ancien avatar local
  - Conservation des avatars Google (URLs externes)
  - Stockage dans `storage/app/public/avatars`

### 2. Changement de mot de passe
- **Route API** : `PUT /api/user/password` (authentification requise)
- **Champs requis** :
  - Mot de passe actuel
  - Nouveau mot de passe (min 8 caractères)
  - Confirmation du nouveau mot de passe
- **Sécurité** :
  - Vérification du mot de passe actuel
  - Validation de la correspondance des mots de passe
  - Hashage sécurisé avec bcrypt

## Interface utilisateur

### Page Profil (`/profil`)
- Affichage des informations utilisateur :
  - Avatar (avec composant UserAvatar)
  - Nom et email
  - Rôle et statut du compte
  - Statistiques (recettes publiées, favoris)
- Boutons d'action :
  - "Modifier mon profil" → Ouvre la modale EditProfileModal
  - "Changer mon mot de passe" → Ouvre la modale ChangePasswordModal

### Modale de modification du profil
- Prévisualisation de l'avatar actuel
- Upload d'un nouvel avatar avec prévisualisation
- Modification du nom
- Messages de succès/erreur
- Validation en temps réel

### Modale de changement de mot de passe
- Champs avec affichage/masquage du mot de passe
- Validation côté client :
  - Correspondance des mots de passe
  - Longueur minimale (8 caractères)
- Messages de succès/erreur
- Fermeture automatique après succès

## Store Pinia

Nouvelles méthodes ajoutées au `authStore` :

```javascript
// Mise à jour du profil
await authStore.updateProfile(formData)

// Changement de mot de passe
await authStore.updatePassword({
  current_password: '...',
  password: '...',
  password_confirmation: '...'
})
```

## Composants créés

1. **EditProfileModal.vue** : Modale de modification du profil
2. **ChangePasswordModal.vue** : Modale de changement de mot de passe

## Améliorations du composant UserAvatar

- Ajout de la taille `xl` (24x24) pour la page profil
- Support des avatars locaux et externes (Google)
- Affichage des initiales si pas d'avatar

## Utilisation

1. Se connecter à l'application
2. Cliquer sur son profil dans le header
3. Sur la page profil, cliquer sur :
   - "Modifier mon profil" pour changer le nom et l'avatar
   - "Changer mon mot de passe" pour modifier le mot de passe

## Sécurité

- Toutes les routes sont protégées par l'authentification Sanctum
- Validation des données côté serveur et client
- Vérification du mot de passe actuel avant changement
- Gestion sécurisée des fichiers uploadés
- Suppression automatique des anciens avatars
