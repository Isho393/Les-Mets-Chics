# Prochaines étapes - Intégration des avatars

## 🎯 Objectif
Afficher les avatars des utilisateurs partout dans l'application où leur nom apparaît.

## 📍 Emplacements à mettre à jour

### 1. Commentaires (Priorité haute)
**Fichier** : `les-mets-chics-frontend/components/CommentSection.vue` ou similaire

**Avant** :
```vue
<div v-for="comment in comments" :key="comment.id">
  <p class="font-bold">{{ comment.user.name }}</p>
  <p>{{ comment.content }}</p>
</div>
```

**Après** :
```vue
<div v-for="comment in comments" :key="comment.id" class="flex gap-3">
  <UserAvatar 
    :avatar="comment.user.avatar" 
    :name="comment.user.name" 
    size="sm" 
  />
  <div>
    <p class="font-bold">{{ comment.user.name }}</p>
    <p>{{ comment.content }}</p>
  </div>
</div>
```

### 2. Auteur de recette (Priorité haute)
**Fichier** : `les-mets-chics-frontend/pages/recettes/[slug].vue`

**Ajouter** :
```vue
<div class="flex items-center gap-3 mb-4">
  <UserAvatar 
    :avatar="recipe.user.avatar" 
    :name="recipe.user.name" 
    size="md" 
  />
  <div>
    <p class="text-sm text-gray-500">Créé par</p>
    <p class="font-semibold">{{ recipe.user.name }}</p>
  </div>
</div>
```

### 3. Page profil (Priorité moyenne)
**Fichier** : `les-mets-chics-frontend/pages/profil.vue`

**Ajouter** :
```vue
<div class="text-center mb-6">
  <UserAvatar 
    :avatar="authStore.user?.avatar" 
    :name="authStore.user?.name || 'User'" 
    size="xl" 
  />
  <h1 class="text-2xl font-bold mt-4">{{ authStore.user?.name }}</h1>
  <p class="text-gray-600">{{ authStore.user?.email }}</p>
</div>
```

### 4. Liste de recettes (Priorité basse)
**Fichier** : `les-mets-chics-frontend/components/RecipeCard.vue`

**Ajouter** :
```vue
<div class="flex items-center gap-2 mt-2">
  <UserAvatar 
    :avatar="recipe.user.avatar" 
    :name="recipe.user.name" 
    size="xs" 
  />
  <span class="text-sm text-gray-600">{{ recipe.user.name }}</span>
</div>
```

## 🔧 Modifications backend nécessaires

### 1. Inclure l'avatar dans les réponses API

**RecipeController** :
```php
// Dans la méthode show() et index()
return response()->json([
    'recipe' => [
        // ... autres champs
        'user' => [
            'id' => $recipe->user->id,
            'name' => $recipe->user->name,
            'avatar' => $this->getAvatarUrl($recipe->user->avatar),
        ]
    ]
]);

private function getAvatarUrl($avatar)
{
    if (!$avatar) return null;
    
    if (str_starts_with($avatar, 'http')) {
        return $avatar; // URL Google
    }
    
    return asset('storage/' . $avatar); // Fichier local
}
```

**CommentController** :
```php
// Dans la méthode index()
return response()->json([
    'comments' => $comments->map(function ($comment) {
        return [
            'id' => $comment->id,
            'content' => $comment->content,
            'created_at' => $comment->created_at,
            'user' => [
                'id' => $comment->user->id,
                'name' => $comment->user->name,
                'avatar' => $this->getAvatarUrl($comment->user->avatar),
            ]
        ];
    })
]);
```

## 📝 Méthode helper réutilisable

Créer un trait pour éviter la duplication :

**Fichier** : `app/Traits/HasAvatarUrl.php`
```php
<?php

namespace App\Traits;

trait HasAvatarUrl
{
    public function getAvatarUrlAttribute()
    {
        if (!$this->avatar) {
            return null;
        }
        
        if (str_starts_with($this->avatar, 'http')) {
            return $this->avatar; // URL Google
        }
        
        return asset('storage/' . $this->avatar); // Fichier local
    }
}
```

**Dans le modèle User** :
```php
use App\Traits\HasAvatarUrl;

class User extends Authenticatable
{
    use HasAvatarUrl;
    // ...
}
```

**Utilisation** :
```php
return response()->json([
    'user' => [
        'id' => $user->id,
        'name' => $user->name,
        'avatar' => $user->avatar_url, // Utilise l'accessor
    ]
]);
```

## 🎨 Personnalisation du composant Avatar

### Variantes de couleurs
Modifier `UserAvatar.vue` pour avoir différentes couleurs :

```vue
<script setup>
const props = defineProps({
  // ... autres props
  color: {
    type: String,
    default: 'primary', // primary, accent, blue, green, etc.
  }
})

const colorClasses = computed(() => {
  const colors = {
    primary: 'from-primary-400 to-accent-400',
    blue: 'from-blue-400 to-blue-600',
    green: 'from-green-400 to-green-600',
    purple: 'from-purple-400 to-purple-600',
  }
  return colors[props.color] || colors.primary
})
</script>

<template>
  <div :class="['bg-gradient-to-br', colorClasses]">
    <!-- ... -->
  </div>
</template>
```

### Badge de statut
Ajouter un badge pour indiquer le rôle :

```vue
<div class="relative">
  <UserAvatar :avatar="user.avatar" :name="user.name" />
  <div 
    v-if="user.role === 'admin'" 
    class="absolute -bottom-1 -right-1 w-5 h-5 bg-yellow-400 rounded-full flex items-center justify-center"
  >
    <Icon name="mdi:crown" class="text-white text-xs" />
  </div>
</div>
```

## 🚀 Déploiement

### Configuration production

**Backend** :
1. Configurer les variables Google OAuth pour la production
2. Mettre à jour `GOOGLE_REDIRECT_URI` avec l'URL de production
3. Ajouter l'URL de production dans Google Cloud Console

**Frontend** :
1. Mettre à jour `NUXT_PUBLIC_API_BASE` pour pointer vers l'API de production
2. Vérifier que les images sont bien servies (CORS, etc.)

### Optimisation des images

Considérer l'utilisation d'un service CDN pour les avatars :
- Cloudinary
- AWS S3 + CloudFront
- Imgix

## 📚 Documentation

- [x] README_AVATAR.md créé
- [x] GOOGLE_OAUTH_SETUP.md créé
- [x] IMPLEMENTATION_SUMMARY.md créé
- [x] TESTING_CHECKLIST.md créé
- [ ] Mettre à jour le README principal du projet
- [ ] Documenter l'API (Swagger) avec les nouveaux champs avatar

## ✅ Checklist finale

- [x] Migration base de données
- [x] Modèles mis à jour
- [x] Contrôleurs créés/modifiés
- [x] Routes ajoutées
- [x] Composant Avatar créé
- [x] Pages inscription/connexion mises à jour
- [x] Page callback créée
- [x] Header mis à jour
- [ ] Commentaires mis à jour
- [ ] Recettes mises à jour
- [ ] Page profil mise à jour
- [ ] Tests effectués
- [ ] Documentation complétée
- [ ] Google OAuth configuré
- [ ] Déployé en production
