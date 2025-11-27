# Composant UserAvatar

## Description
Composant réutilisable pour afficher les avatars des utilisateurs avec fallback sur les initiales.

## Utilisation

### Import
Le composant est auto-importé par Nuxt, pas besoin d'import explicite.

### Props

| Prop | Type | Requis | Défaut | Description |
|------|------|--------|--------|-------------|
| `avatar` | String | Non | `null` | URL de l'avatar (Google ou local) |
| `name` | String | Oui | - | Nom de l'utilisateur (pour les initiales) |
| `size` | String | Non | `'md'` | Taille de l'avatar : `xs`, `sm`, `md`, `lg`, `xl` |

### Tailles disponibles

- `xs` : 24px (w-6 h-6)
- `sm` : 32px (w-8 h-8)
- `md` : 40px (w-10 h-10)
- `lg` : 48px (w-12 h-12)
- `xl` : 64px (w-16 h-16)

## Exemples d'utilisation

### Avec avatar
```vue
<UserAvatar 
  avatar="https://lh3.googleusercontent.com/a/..." 
  name="Jean Dupont" 
  size="md" 
/>
```

### Sans avatar (affiche les initiales)
```vue
<UserAvatar 
  name="Jean Dupont" 
  size="lg" 
/>
```

### Dans une liste de commentaires
```vue
<div v-for="comment in comments" :key="comment.id" class="flex gap-3">
  <UserAvatar 
    :avatar="comment.user.avatar" 
    :name="comment.user.name" 
    size="sm" 
  />
  <div>
    <p class="font-semibold">{{ comment.user.name }}</p>
    <p>{{ comment.content }}</p>
  </div>
</div>
```

### Dans le header (déjà implémenté)
```vue
<UserAvatar 
  :avatar="authStore.user?.avatar" 
  :name="authStore.user?.name || 'User'" 
  size="sm" 
/>
```

### Sur une page de profil
```vue
<UserAvatar 
  :avatar="user.avatar" 
  :name="user.name" 
  size="xl" 
/>
```

## Comportement

1. **Si `avatar` est fourni** : Affiche l'image
   - Supporte les URLs Google (commencent par `http`)
   - Supporte les chemins locaux (ex: `/storage/avatars/...`)

2. **Si `avatar` est `null`** : Affiche les initiales
   - Prend la première lettre du prénom et du nom
   - Si un seul mot : prend les 2 premières lettres
   - Affiche en majuscules sur fond dégradé

## Style

- Forme : Cercle parfait (`rounded-full`)
- Fond par défaut : Dégradé `from-primary-400 to-accent-400`
- Texte : Blanc, gras
- Image : `object-cover` pour garder les proportions

## Où l'utiliser ?

- ✅ Header (navigation)
- ✅ Page de profil
- ✅ Liste de commentaires
- ✅ Auteur d'une recette
- ✅ Liste d'utilisateurs
- ✅ Notifications
- ✅ Messages
- ✅ Anywhere you need to display a user!
