<template>
  <header class="bg-white shadow-sm border-b border-cream-200 sticky top-0 z-50 backdrop-blur-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between items-center h-16">
        
        <!-- Logo -->
        <NuxtLink to="/" class="flex items-center space-x-3 group">
          <div class="w-10 h-10 bg-gradient-to-br from-bordeaux-600 to-bordeaux-800 rounded-full flex items-center justify-center group-hover:scale-110 transition-transform">
            <Icon name="lucide:chef-hat" class="text-white text-xl" />
          </div>
          <span class="font-display font-bold text-xl text-bordeaux-900 hidden sm:block">
            Les Mets Chics
          </span>
        </NuxtLink>

        <!-- Navigation Desktop -->
        <nav class="hidden md:flex items-center space-x-6">
          <template v-for="item in visibleMenuItems" :key="item.path || item.name">
            <NuxtLink 
              v-if="item.path && !item.external"
              :to="item.path"
              class="nav-link"
              :class="{ 'nav-link-active': isActiveRoute(item.path) }"
            >
              <Icon :name="item.icon" class="text-lg mr-2" />
              {{ item.name }}
            </NuxtLink>
            <a 
              v-else-if="item.external"
              :href="getExternalUrl(item.path)"
              target="_blank"
              class="nav-link"
            >
              <Icon :name="item.icon" class="text-lg mr-2" />
              {{ item.name }}
            </a>
          </template>
        </nav>

        <!-- Actions Droite -->
        <div class="flex items-center space-x-3">
          
          <!-- Recherche -->
          <button 
            @click="toggleSearch"
            class="p-2 text-charcoal-600 hover:text-bordeaux-600 transition-colors rounded-lg hover:bg-cream-50"
            title="Rechercher (Ctrl+K)"
          >
            <Icon name="lucide:search" class="text-xl" />
          </button>

          <!-- Notifications (si connecté) -->
          <div v-if="isAuthenticated" class="relative">
            <button class="p-2 text-charcoal-600 hover:text-bordeaux-600 transition-colors rounded-lg hover:bg-cream-50">
              <Icon name="lucide:bell" class="text-xl" />
              <span v-if="totalNotifications > 0" 
                    class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">
                {{ totalNotifications }}
              </span>
            </button>
          </div>

          <!-- Menu Utilisateur Connecté -->
          <div v-if="isAuthenticated" class="relative">
            <button 
              @click="toggleUserMenu"
              class="flex items-center space-x-2 p-2 rounded-lg hover:bg-cream-50 transition-colors"
            >
              <UserAvatar :user="user" size="sm" />
              <div class="hidden sm:block text-left">
                <p class="text-sm font-medium text-charcoal-900">{{ user?.name }}</p>
                <p class="text-xs text-charcoal-500">{{ getUserRoleLabel(user?.role) }}</p>
              </div>
              <Icon name="lucide:chevron-down" class="text-sm text-charcoal-500" />
            </button>
            
            <!-- Dropdown Menu -->
            <div v-if="userMenuOpen" 
                 class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-premium border border-cream-200 py-2 z-50">
              <template v-for="item in userMenuItems" :key="item.name">
                <div v-if="item.divider" class="border-t border-cream-200 my-2"></div>
                <NuxtLink 
                  v-else-if="item.path && !item.external"
                  :to="item.path"
                  class="dropdown-item"
                  @click="userMenuOpen = false"
                >
                  <Icon :name="item.icon" class="text-lg" :class="item.iconClass" />
                  <div>
                    <div class="font-medium">{{ item.name }}</div>
                    <div v-if="item.description" class="text-xs text-charcoal-500">{{ item.description }}</div>
                  </div>
                </NuxtLink>
                <a 
                  v-else-if="item.external"
                  :href="getExternalUrl(item.path)"
                  target="_blank"
                  class="dropdown-item"
                  @click="userMenuOpen = false"
                >
                  <Icon :name="item.icon" class="text-lg" :class="item.iconClass" />
                  <div>
                    <div class="font-medium">{{ item.name }}</div>
                    <div v-if="item.description" class="text-xs text-charcoal-500">{{ item.description }}</div>
                  </div>
                </a>
                <button 
                  v-else-if="item.action === 'logout'"
                  @click="handleLogout"
                  class="dropdown-item w-full text-left text-red-600 hover:bg-red-50"
                >
                  <Icon :name="item.icon" class="text-lg" />
                  <div>
                    <div class="font-medium">{{ item.name }}</div>
                    <div v-if="item.description" class="text-xs text-red-500">{{ item.description }}</div>
                  </div>
                </button>
              </template>
            </div>
          </div>

          <!-- Boutons Connexion/Inscription (si non connecté) -->
          <div v-else class="flex items-center space-x-3">
            <NuxtLink 
              to="/connexion"
              class="btn-outline"
            >
              <Icon name="lucide:log-in" class="mr-2" />
              Connexion
            </NuxtLink>
            <NuxtLink 
              to="/inscription"
              class="btn-primary"
            >
              <Icon name="lucide:sparkles" class="mr-2" />
              Inscription
            </NuxtLink>
          </div>

          <!-- Menu Mobile -->
          <button 
            @click="toggleMobileMenu"
            class="md:hidden p-2 text-charcoal-600 hover:text-bordeaux-600 rounded-lg hover:bg-cream-50 transition-colors"
          >
            <Icon :name="mobileMenuOpen ? 'lucide:x' : 'lucide:menu'" class="text-xl" />
          </button>
        </div>
      </div>
    </div>

    <!-- Menu Mobile -->
    <div v-if="mobileMenuOpen" class="md:hidden bg-white border-t border-cream-200 shadow-lg">
      <nav class="px-4 py-4 space-y-2">
        <!-- Navigation principale -->
        <template v-for="item in visibleMenuItems" :key="item.path || item.name">
          <NuxtLink 
            v-if="item.path && !item.external"
            :to="item.path"
            class="mobile-nav-item"
            :class="{ 'mobile-nav-item-active': isActiveRoute(item.path) }"
            @click="mobileMenuOpen = false"
          >
            <Icon :name="item.icon" class="text-lg" />
            {{ item.name }}
          </NuxtLink>
          <a 
            v-else-if="item.external"
            :href="getExternalUrl(item.path)"
            target="_blank"
            class="mobile-nav-item"
            @click="mobileMenuOpen = false"
          >
            <Icon :name="item.icon" class="text-lg" />
            {{ item.name }}
          </a>
        </template>
        
        <!-- Séparateur -->
        <div class="border-t border-cream-200 my-4"></div>
        
        <!-- Actions utilisateur mobile -->
        <div v-if="!isAuthenticated" class="space-y-2">
          <NuxtLink 
            to="/connexion"
            class="mobile-nav-item"
            @click="mobileMenuOpen = false"
          >
            <Icon name="lucide:log-in" class="text-lg" />
            Connexion
          </NuxtLink>
          <NuxtLink 
            to="/inscription"
            class="mobile-nav-item bg-bordeaux-50 text-bordeaux-700"
            @click="mobileMenuOpen = false"
          >
            <Icon name="lucide:sparkles" class="text-lg" />
            Inscription Premium
          </NuxtLink>
        </div>
        
        <!-- Menu utilisateur mobile -->
        <div v-else class="space-y-2">
          <div class="flex items-center space-x-3 p-3 bg-cream-50 rounded-lg">
            <UserAvatar :user="user" size="sm" />
            <div>
              <p class="font-medium text-charcoal-900">{{ user?.name }}</p>
              <p class="text-xs text-charcoal-500">{{ getUserRoleLabel(user?.role) }}</p>
            </div>
          </div>
          
          <template v-for="item in userMenuItems" :key="item.name">
            <NuxtLink 
              v-if="item.path && !item.external && !item.divider"
              :to="item.path"
              class="mobile-nav-item"
              @click="mobileMenuOpen = false"
            >
              <Icon :name="item.icon" class="text-lg" :class="item.iconClass" />
              {{ item.name }}
            </NuxtLink>
            <a 
              v-else-if="item.external && !item.divider"
              :href="getExternalUrl(item.path)"
              target="_blank"
              class="mobile-nav-item"
              @click="mobileMenuOpen = false"
            >
              <Icon :name="item.icon" class="text-lg" :class="item.iconClass" />
              {{ item.name }}
            </a>
            <button 
              v-else-if="item.action === 'logout'"
              @click="handleLogout"
              class="mobile-nav-item w-full text-left text-red-600"
            >
              <Icon :name="item.icon" class="text-lg" />
              {{ item.name }}
            </button>
          </template>
        </div>
      </nav>
    </div>

    <!-- Overlay pour fermer les menus -->
    <div 
      v-if="mobileMenuOpen || userMenuOpen"
      @click="closeAllMenus"
      class="fixed inset-0 z-40 bg-black bg-opacity-25 md:hidden"
    ></div>
  </header>
</template>

<script setup>
const { user, isAuthenticated, logout } = useAuth()
const { getMainMenuItems, getUserMenuItems } = useNavigation()
const { toggleSearch } = useMenu()
const config = useRuntimeConfig()
const route = useRoute()

// États des menus
const mobileMenuOpen = ref(false)
const userMenuOpen = ref(false)
const totalNotifications = ref(0)

// Menus dynamiques par rôle
const visibleMenuItems = computed(() => {
  const baseItems = [
    { name: 'Accueil', path: '/', icon: 'lucide:home' },
  ]
  
  if (!isAuthenticated.value) {
    // Visiteur non connecté
    return [
      ...baseItems,
      { name: 'Aperçu Recettes', path: '/recettes', icon: 'lucide:eye' },
      { name: 'Origines', path: '/categories', icon: 'lucide:globe' },
      { name: 'À propos', path: '/a-propos', icon: 'lucide:info' }
    ]
  }
  
  // Utilisateur connecté (tous rôles)
  const authenticatedItems = [
    ...baseItems,
    { name: 'Recettes', path: '/recettes', icon: 'lucide:chef-hat' },
    { name: 'Origines', path: '/categories', icon: 'lucide:globe' }
  ]
  
  // Ajouts spécifiques par rôle
  if (user.value?.role === 'auteur' || user.value?.role === 'admin') {
    authenticatedItems.push(
      { name: 'Mes Recettes', path: '/mes-recettes', icon: 'lucide:book-open' }
    )
  }
  
  return authenticatedItems
})

const userMenuItems = computed(() => {
  if (!isAuthenticated.value) return []
  
  const baseItems = [
    { 
      name: 'Mon Profil', 
      path: '/profil', 
      icon: 'lucide:user', 
      iconClass: 'text-bordeaux-600',
      description: 'Gérer mes informations'
    },
    { 
      name: 'Mes Favoris', 
      path: '/favoris', 
      icon: 'lucide:heart', 
      iconClass: 'text-red-500',
      description: 'Recettes sauvegardées'
    },
    { divider: true }
  ]
  
  // Ajouts par rôle
  if (user.value?.role === 'auteur') {
    baseItems.push(
      { 
        name: 'Créer une Recette', 
        path: '/admin/recipes/create', 
        external: true,
        icon: 'lucide:plus-circle', 
        iconClass: 'text-green-600',
        description: 'Nouvelle création'
      },
      { 
        name: 'Mes Publications', 
        path: '/admin/recipes', 
        external: true,
        icon: 'lucide:book-open', 
        iconClass: 'text-blue-600',
        description: 'Gérer mes recettes'
      },
      { divider: true }
    )
  }
  
  if (user.value?.role === 'admin') {
    baseItems.push(
      { 
        name: 'Administration', 
        path: '/admin', 
        external: true,
        icon: 'lucide:settings', 
        iconClass: 'text-purple-600',
        description: 'Panel complet'
      },
      { 
        name: 'Gestion Utilisateurs', 
        path: '/admin/users', 
        external: true,
        icon: 'lucide:users', 
        iconClass: 'text-indigo-600',
        description: 'Modération'
      },
      { 
        name: 'Statistiques', 
        path: '/admin/stats', 
        external: true,
        icon: 'lucide:bar-chart', 
        iconClass: 'text-emerald-600',
        description: 'Analytics'
      },
      { divider: true }
    )
  }
  
  // Actions communes
  baseItems.push(
    { 
      name: 'Paramètres', 
      path: '/parametres', 
      icon: 'lucide:cog', 
      iconClass: 'text-charcoal-600',
      description: 'Préférences'
    },
    { 
      name: 'Déconnexion', 
      action: 'logout',
      icon: 'lucide:log-out',
      description: 'Quitter la session'
    }
  )
  
  return baseItems
})

// Actions
const toggleMobileMenu = () => {
  mobileMenuOpen.value = !mobileMenuOpen.value
  userMenuOpen.value = false
}

const toggleUserMenu = () => {
  userMenuOpen.value = !userMenuOpen.value
  mobileMenuOpen.value = false
}

const closeAllMenus = () => {
  mobileMenuOpen.value = false
  userMenuOpen.value = false
}

const handleLogout = async () => {
  await logout()
  closeAllMenus()
}

const isActiveRoute = (path) => {
  if (path === '/') return route.path === '/'
  return route.path.startsWith(path)
}

const getExternalUrl = (path) => {
  return `${config.public.apiBase}${path}`
}

const getUserRoleLabel = (role) => {
  const labels = {
    'explorateur': 'Membre',
    'auteur': 'Chef Créateur',
    'admin': 'Administrateur'
  }
  return labels[role] || 'Membre'
}

// Fermer les menus au changement de route
watch(() => route.path, () => {
  closeAllMenus()
})
</script>

<style scoped>
.nav-link {
  @apply flex items-center px-3 py-2 text-charcoal-700 hover:text-bordeaux-600 font-medium transition-all duration-300 rounded-lg hover:bg-cream-50;
}

.nav-link-active {
  @apply text-bordeaux-600 bg-bordeaux-50;
}

.dropdown-item {
  @apply flex items-center space-x-3 px-4 py-3 text-charcoal-700 hover:bg-cream-50 transition-colors;
}

.mobile-nav-item {
  @apply flex items-center space-x-3 px-4 py-3 text-charcoal-700 hover:bg-cream-50 rounded-lg transition-colors;
}

.mobile-nav-item-active {
  @apply text-bordeaux-600 bg-bordeaux-50;
}

.btn-primary {
  @apply px-4 py-2 bg-gradient-to-r from-bordeaux-600 to-bordeaux-700 text-white rounded-lg hover:from-bordeaux-700 hover:to-bordeaux-800 transition-all duration-300 font-medium flex items-center shadow-soft hover:shadow-md;
}

.btn-outline {
  @apply px-4 py-2 border-2 border-bordeaux-600 text-bordeaux-600 rounded-lg hover:bg-bordeaux-600 hover:text-white transition-all duration-300 font-medium flex items-center;
}

/* Animations */
@media (max-width: 768px) {
  .btn-primary,
  .btn-outline {
    @apply px-3 py-2 text-sm;
  }
}
</style>