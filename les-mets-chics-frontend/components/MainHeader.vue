<template>
  <header class="bg-white shadow-sm border-b border-cream-200 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between items-center h-16">
        
        <!-- Logo -->
        <NuxtLink to="/" class="flex items-center space-x-3">
          <div class="w-10 h-10 bg-gradient-to-br from-bordeaux-600 to-bordeaux-800 rounded-full flex items-center justify-center">
            <Icon name="lucide:chef-hat" class="text-white text-xl" />
          </div>
          <span class="font-display font-bold text-xl text-bordeaux-900 hidden sm:block">
            Les Mets Chics
          </span>
        </NuxtLink>

        <!-- Navigation Desktop -->
        <nav class="hidden md:flex items-center space-x-8">
          <template v-for="item in mainMenuItems" :key="item.path || item.name">
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
        <div class="flex items-center space-x-4">
          
          <!-- Recherche -->
          <button 
            @click="toggleSearch"
            class="p-2 text-charcoal-600 hover:text-bordeaux-600 transition-colors"
            title="Rechercher (Ctrl+K)"
          >
            <Icon name="lucide:search" class="text-xl" />
          </button>

          <!-- Notifications (si connecté) -->
          <div v-if="isAuthenticated" class="relative">
            <button class="p-2 text-charcoal-600 hover:text-bordeaux-600 transition-colors">
              <Icon name="lucide:bell" class="text-xl" />
              <span v-if="totalNotifications > 0" 
                    class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">
                {{ totalNotifications }}
              </span>
            </button>
          </div>

          <!-- Menu Utilisateur -->
          <div v-if="isAuthenticated" class="relative">
            <button 
              @click="toggleUserMenu"
              class="flex items-center space-x-2 p-2 rounded-lg hover:bg-cream-50 transition-colors"
            >
              <UserAvatar :user="user" size="sm" />
              <Icon name="lucide:chevron-down" class="text-sm text-charcoal-500" />
            </button>
            
            <!-- Dropdown Menu -->
            <div v-if="userMenuOpen" 
                 class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-cream-200 py-2">
              <template v-for="item in userMenuItems" :key="item.name">
                <NuxtLink 
                  v-if="item.path && !item.external"
                  :to="item.path"
                  class="dropdown-item"
                  @click="userMenuOpen = false"
                >
                  <Icon :name="item.icon" class="text-lg" />
                  {{ item.name }}
                </NuxtLink>
                <a 
                  v-else-if="item.external"
                  :href="getExternalUrl(item.path)"
                  target="_blank"
                  class="dropdown-item"
                  @click="userMenuOpen = false"
                >
                  <Icon :name="item.icon" class="text-lg" />
                  {{ item.name }}
                </a>
                <button 
                  v-else-if="item.action === 'logout'"
                  @click="handleLogout"
                  class="dropdown-item w-full text-left text-red-600 hover:bg-red-50"
                >
                  <Icon :name="item.icon" class="text-lg" />
                  {{ item.name }}
                </button>
              </template>
            </div>
          </div>

          <!-- Bouton Connexion (si non connecté) -->
          <NuxtLink 
            v-else
            to="/connexion"
            class="btn-primary"
          >
            Connexion
          </NuxtLink>

          <!-- Menu Mobile -->
          <button 
            @click="toggleMobileMenu"
            class="md:hidden p-2 text-charcoal-600 hover:text-bordeaux-600"
          >
            <Icon :name="mobileMenuOpen ? 'lucide:x' : 'lucide:menu'" class="text-xl" />
          </button>
        </div>
      </div>
    </div>

    <!-- Menu Mobile -->
    <div v-if="mobileMenuOpen" class="md:hidden bg-white border-t border-cream-200">
      <nav class="px-4 py-4 space-y-2">
        <template v-for="item in mainMenuItems" :key="item.path || item.name">
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

// Menus dynamiques
const mainMenuItems = computed(() => getMainMenuItems())
const userMenuItems = computed(() => getUserMenuItems())

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

// Fermer les menus au changement de route
watch(() => route.path, () => {
  closeAllMenus()
})
</script>

<style scoped>
.nav-link {
  @apply flex items-center px-3 py-2 text-charcoal-700 hover:text-bordeaux-600 font-medium transition-colors rounded-lg hover:bg-cream-50;
}

.nav-link-active {
  @apply text-bordeaux-600 bg-bordeaux-50;
}

.dropdown-item {
  @apply flex items-center space-x-3 px-4 py-2 text-charcoal-700 hover:bg-cream-50 transition-colors;
}

.mobile-nav-item {
  @apply flex items-center space-x-3 px-4 py-3 text-charcoal-700 hover:bg-cream-50 rounded-lg transition-colors;
}

.mobile-nav-item-active {
  @apply text-bordeaux-600 bg-bordeaux-50;
}

.btn-primary {
  @apply px-4 py-2 bg-bordeaux-600 text-white rounded-lg hover:bg-bordeaux-700 transition-colors font-medium;
}
</style>