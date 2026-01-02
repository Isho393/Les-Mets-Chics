<template>
  <nav class="hidden lg:flex items-center gap-8">
    <!-- Navigation principale -->
    <div class="flex items-center gap-6">
      <NuxtLink
        v-for="item in navigationItems"
        :key="item.name"
        :to="item.href"
        :class="navLinkClasses(item.href)"
        class="group relative font-medium transition-all duration-300"
      >
        {{ item.name }}
        
        <!-- Indicateur actif -->
        <span 
          class="absolute -bottom-1 left-0 h-0.5 bg-bordeaux-800 transition-all duration-300"
          :class="isActiveRoute(item.href) ? 'w-full' : 'w-0 group-hover:w-full'"
        ></span>
      </NuxtLink>
    </div>

    <!-- Actions droite -->
    <div class="flex items-center gap-3 ml-auto">
      
      <!-- Recherche -->
      <button 
        class="p-2 text-charcoal-600 hover:text-bordeaux-800 hover:bg-cream-100 rounded-xl transition-all duration-300"
        @click="toggleSearch"
        aria-label="Rechercher"
      >
        <Icon name="lucide:search" class="w-5 h-5" />
      </button>

      <!-- Lien Admin (si autorisé) -->
      <button
        v-if="canAccessAdmin"
        @click="navigateToAdmin"
        class="hidden xl:flex items-center gap-2 px-3 py-2 text-sm font-medium text-bordeaux-700 hover:text-bordeaux-800 hover:bg-bordeaux-50 rounded-xl transition-all duration-300"
      >
        <Icon name="lucide:settings" class="w-4 h-4" />
        <span>{{ isAdmin ? 'Admin' : 'Dashboard' }}</span>
      </button>

      <!-- Menu utilisateur ou Auth buttons -->
      <div v-if="isAuthenticated" class="relative" ref="userMenuRef">
        <button 
          @click="toggleUserMenu"
          class="flex items-center gap-2 p-2 hover:bg-cream-100 rounded-xl transition-all duration-300"
          :aria-expanded="showUserMenu"
        >
          <UserAvatar 
            :avatar="user?.avatar" 
            :name="user?.name" 
            size="sm" 
          />
          <span class="hidden xl:block text-sm font-medium text-charcoal-800">
            {{ user?.name }}
          </span>
          <Icon 
            name="lucide:chevron-down" 
            class="w-4 h-4 text-charcoal-600 transition-transform duration-200"
            :class="{ 'rotate-180': showUserMenu }"
          />
        </button>

        <!-- Dropdown utilisateur -->
        <Transition
          enter-active-class="transition ease-out duration-200"
          enter-from-class="opacity-0 scale-95 translate-y-1"
          enter-to-class="opacity-100 scale-100 translate-y-0"
          leave-active-class="transition ease-in duration-150"
          leave-from-class="opacity-100 scale-100 translate-y-0"
          leave-to-class="opacity-0 scale-95 translate-y-1"
        >
          <div 
            v-if="showUserMenu"
            class="absolute right-0 mt-2 w-64 bg-white rounded-2xl shadow-premium border border-cream-200 py-2 z-50"
          >
            <!-- Header utilisateur -->
            <div class="px-4 py-3 border-b border-cream-200">
              <div class="flex items-center gap-3">
                <UserAvatar 
                  :avatar="user?.avatar" 
                  :name="user?.name" 
                  size="md" 
                />
                <div class="flex-1 min-w-0">
                  <p class="font-medium text-bordeaux-800 truncate">{{ user?.name }}</p>
                  <p class="text-sm text-charcoal-600 truncate">{{ user?.email }}</p>
                  <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium mt-1"
                        :class="statusClasses">
                    {{ statusText }}
                  </span>
                </div>
              </div>
            </div>

            <!-- Menu items -->
            <div class="py-2">
              <ui-menu-item
                v-for="item in userMenuItems"
                :key="item.name"
                :label="item.name"
                :icon="item.icon"
                :to="item.href"
                :badge="item.badge"
                size="sm"
                @click="closeUserMenu"
              />
              
              <!-- Admin link dans dropdown si pas affiché en haut -->
              <ui-menu-item
                v-if="canAccessAdmin"
                :label="isAdmin ? 'Panel Admin' : 'Mon Dashboard'"
                icon="lucide:settings"
                size="sm"
                class="xl:hidden"
                @click="navigateToAdmin"
              />
            </div>

            <div class="border-t border-cream-200 py-2">
              <ui-menu-item
                label="Se déconnecter"
                icon="lucide:log-out"
                variant="danger"
                size="sm"
                @click="handleLogout"
              />
            </div>
          </div>
        </Transition>
      </div>

      <!-- Auth buttons (non connecté) -->
      <div v-else class="flex items-center gap-2">
        <ui-button 
          variant="ghost" 
          size="sm"
          tag="NuxtLink"
          to="/connexion"
        >
          Connexion
        </ui-button>
        <ui-button 
          variant="primary" 
          size="sm"
          tag="NuxtLink"
          to="/inscription"
        >
          Inscription
        </ui-button>
      </div>
    </div>
  </nav>
</template>

<script setup>
const { user, isAuthenticated, isAdmin, canAccessAdminPanel, logout } = useAuth()
const { navigateToAdmin, isActiveRoute } = useNavigation()
const route = useRoute()

// State
const showUserMenu = ref(false)
const userMenuRef = ref(null)

// Computed
const canAccessAdmin = computed(() => canAccessAdminPanel.value)

const statusClasses = computed(() => {
  const status = user.value?.status
  return {
    'bg-green-100 text-green-800': status === 'active',
    'bg-yellow-100 text-yellow-800': status === 'pending',
    'bg-red-100 text-red-800': status === 'suspended'
  }
})

const statusText = computed(() => {
  const status = user.value?.status
  return {
    'active': 'Actif',
    'pending': 'En attente',
    'suspended': 'Suspendu'
  }[status] || status
})

// Navigation selon le statut
const navigationItems = computed(() => {
  if (!isAuthenticated.value) {
    return [
      { name: 'Accueil', href: '/' },
      { name: 'À propos', href: '/a-propos' },
      { name: 'Contact', href: '/contact' }
    ]
  }
  
  return [
    { name: 'Recettes', href: '/recettes' },
    { name: 'Catégories', href: '/categories' },
    { name: 'À propos', href: '/a-propos' }
  ]
})

const userMenuItems = computed(() => [
  { name: 'Mon profil', href: '/profil', icon: 'lucide:user' },
  { name: 'Mes favoris', href: '/favoris', icon: 'lucide:heart', badge: '3' },
  { name: 'Paramètres', href: '/parametres', icon: 'lucide:settings' }
])

// Methods
const navLinkClasses = (href) => {
  return isActiveRoute(href) 
    ? 'text-bordeaux-800' 
    : 'text-charcoal-700 hover:text-bordeaux-800'
}

const toggleUserMenu = () => {
  showUserMenu.value = !showUserMenu.value
}

const closeUserMenu = () => {
  showUserMenu.value = false
}

const toggleSearch = () => {
  // TODO: Implémenter la modal de recherche
  console.log('Toggle search')
}

const handleLogout = async () => {
  await logout()
  closeUserMenu()
}

// Fermer le menu utilisateur au clic extérieur
const handleClickOutside = (event) => {
  if (userMenuRef.value && !userMenuRef.value.contains(event.target)) {
    closeUserMenu()
  }
}

onMounted(() => {
  document.addEventListener('click', handleClickOutside)
})

onUnmounted(() => {
  document.removeEventListener('click', handleClickOutside)
})

// Fermer au changement de route
watch(() => route.path, () => {
  closeUserMenu()
})
</script>