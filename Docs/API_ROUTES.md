# Routes API - Les Mets Chics

## 🔐 Authentification

### Inscription (formulaire)
```
POST /api/register
Content-Type: multipart/form-data

Body:
- name: string (required)
- email: string (required, unique)
- password: string (required, min:8)
- password_confirmation: string (required)
- avatar: file (optional, image, max:2MB)

Response 201:
{
  "message": "Inscription réussie. Votre compte est en attente de validation. Vous recevrez un email une fois votre compte activé.",
  "user": {
    "name": "Jean Dupont",
    "email": "jean@example.com",
    "status": "pending",
    "avatar": "http://localhost:8000/storage/avatars/xxx.jpg"
  }
}
```

### Connexion (formulaire)
```
POST /api/login
Content-Type: application/json

Body:
{
  "email": "jean@example.com",
  "password": "password123"
}

Response 200:
{
  "message": "Connexion réussie.",
  "token": "1|abc123...",
  "user": {
    "id": 1,
    "name": "Jean Dupont",
    "email": "jean@example.com",
    "role": "explorateur",
    "status": "active",
    "avatar": "http://localhost:8000/storage/avatars/xxx.jpg"
  }
}

Response 403 (compte pending):
{
  "message": "Votre compte est en attente de validation par un administrateur."
}

Response 403 (compte suspendu):
{
  "message": "Votre compte a été suspendu."
}
```

### Déconnexion
```
POST /api/logout
Authorization: Bearer {token}

Response 200:
{
  "message": "Déconnexion réussie."
}
```

### Informations utilisateur
```
GET /api/user
Authorization: Bearer {token}

Response 200:
{
  "user": {
    "id": 1,
    "name": "Jean Dupont",
    "email": "jean@example.com",
    "role": "explorateur",
    "status": "active",
    "avatar": "http://localhost:8000/storage/avatars/xxx.jpg"
  }
}
```

## 🔑 Google OAuth

### Redirection vers Google
```
GET /api/auth/google

Response: Redirection vers Google OAuth
```

### Callback Google
```
GET /api/auth/google/callback?code={authorization_code}

Response: Redirection vers le frontend
- Succès (compte actif): /auth/callback?token={token}&status=success
- Pending (nouveau compte): /auth/callback?status=pending&message=...
- Erreur: /auth/callback?status=error&message=...
```

## 📚 Recettes

### Liste des recettes
```
GET /api/recipes

Response 200:
{
  "recipes": [
    {
      "id": 1,
      "title": "Boeuf Bourguignon",
      "slug": "boeuf-bourguignon",
      "description": "...",
      "image": "...",
      "user": {
        "id": 1,
        "name": "Chef Jean",
        "avatar": "..."
      }
    }
  ]
}
```

### Détails d'une recette
```
GET /api/recipes/{slug}

Response 200:
{
  "recipe": {
    "id": 1,
    "title": "Boeuf Bourguignon",
    "slug": "boeuf-bourguignon",
    "description": "...",
    "ingredients": "...",
    "instructions": "...",
    "image": "...",
    "video": "...",
    "user": {
      "id": 1,
      "name": "Chef Jean",
      "avatar": "..."
    }
  }
}
```

## 🏷️ Catégories

### Liste des catégories
```
GET /api/categories

Response 200:
{
  "categories": [
    {
      "id": 1,
      "name": "Entrées",
      "slug": "entrees"
    }
  ]
}
```

### Recettes par catégorie
```
GET /api/categories/{slug}/recipes

Response 200:
{
  "recipes": [...]
}
```

## 💬 Commentaires

### Liste des commentaires d'une recette
```
GET /api/recipes/{slug}/comments

Response 200:
{
  "comments": [
    {
      "id": 1,
      "content": "Excellente recette !",
      "created_at": "2025-11-27T10:00:00Z",
      "user": {
        "id": 1,
        "name": "Jean Dupont",
        "avatar": "http://localhost:8000/storage/avatars/xxx.jpg"
      }
    }
  ]
}
```

### Ajouter un commentaire
```
POST /api/recipes/{slug}/comments
Authorization: Bearer {token}
Content-Type: application/json

Body:
{
  "content": "Excellente recette !"
}

Response 201:
{
  "message": "Commentaire ajouté avec succès.",
  "comment": {
    "id": 1,
    "content": "Excellente recette !",
    "created_at": "2025-11-27T10:00:00Z",
    "user": {
      "id": 1,
      "name": "Jean Dupont",
      "avatar": "..."
    }
  }
}
```

## ❤️ Likes

### Liker/Unliker une recette
```
POST /api/recipes/{slug}/like
Authorization: Bearer {token}

Response 200:
{
  "message": "Recette likée avec succès.",
  "liked": true,
  "likes_count": 42
}
```

### Vérifier si l'utilisateur a liké
```
GET /api/recipes/{slug}/like/check
Authorization: Bearer {token}

Response 200:
{
  "liked": true
}
```

## 📤 Upload de fichiers

### Upload d'image
```
POST /api/upload/image
Authorization: Bearer {token}
Content-Type: multipart/form-data

Body:
- image: file (required, image, max:5MB)

Response 200:
{
  "url": "http://localhost:8000/storage/images/xxx.jpg"
}
```

### Upload de vidéo
```
POST /api/upload/video
Authorization: Bearer {token}
Content-Type: multipart/form-data

Body:
- video: file (required, video, max:50MB)

Response 200:
{
  "url": "http://localhost:8000/storage/videos/xxx.mp4"
}
```

## 📖 Documentation Swagger

### Accéder à la documentation interactive
```
GET /api/documentation
```

Ouvre l'interface Swagger UI avec toutes les routes documentées.

## 🔒 Authentification

### Routes publiques (pas d'authentification)
- `GET /api/recipes`
- `GET /api/recipes/{slug}`
- `GET /api/categories`
- `GET /api/categories/{slug}/recipes`
- `GET /api/recipes/{slug}/comments`
- `POST /api/register`
- `POST /api/login`
- `GET /api/auth/google`
- `GET /api/auth/google/callback`

### Routes protégées (authentification requise)
- `POST /api/logout`
- `GET /api/user`
- `POST /api/recipes/{slug}/like`
- `GET /api/recipes/{slug}/like/check`
- `POST /api/recipes/{slug}/comments`
- `POST /api/upload/image`
- `POST /api/upload/video`

## 🌐 Headers requis

### Pour les routes protégées
```
Authorization: Bearer {token}
```

### Pour les uploads de fichiers
```
Content-Type: multipart/form-data
Authorization: Bearer {token}
```

### Pour les requêtes JSON
```
Content-Type: application/json
Accept: application/json
```

## 🚨 Codes de réponse

- `200` : Succès
- `201` : Créé avec succès
- `401` : Non authentifié
- `403` : Accès refusé (compte pending ou suspendu)
- `404` : Ressource non trouvée
- `422` : Erreur de validation
- `500` : Erreur serveur

## 📝 Notes importantes

### Avatars
- Les avatars Google sont des URLs complètes : `https://lh3.googleusercontent.com/...`
- Les avatars locaux sont des URLs complètes : `http://localhost:8000/storage/avatars/...`
- Si `avatar` est `null`, afficher les initiales côté frontend

### Statuts utilisateur
- `pending` : En attente de validation admin
- `active` : Compte actif, peut se connecter
- `suspended` : Compte suspendu, ne peut pas se connecter

### Rôles utilisateur
- `explorateur` : Utilisateur standard (par défaut)
- `admin` : Administrateur (accès au panel Filament)

### Tokens
- Les tokens sont gérés par Laravel Sanctum
- Durée de vie : illimitée (jusqu'à déconnexion)
- Stockage : localStorage côté frontend
- Format : `{id}|{token}`
