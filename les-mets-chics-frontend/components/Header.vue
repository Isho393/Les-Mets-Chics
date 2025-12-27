<template>
  <header class="sticky top-0 z-50 bg-white/95 backdrop-blur-md border-b border-cream-200 shadow-soft">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex items-center justify-between h-16 lg:h-20">
        
        <!-- Logo -->
        <NuxtLink to="/" class="flex items-center gap-3 group">
          <div class="w-10 h-10 lg:w-12 lg:h-12 bg-gradient-to-br from-bordeaux-800 to-bordeaux-900 rounded-xl flex items-center justify-center shadow-premium group-hover:scale-105 transition-transform duration-300">
            <Icon name="lucide:chef-hat" class="w-5 h-5 lg:w-6 lg:h-6 text-white" />
          </div>
          <div class="hidden sm:block">
            <h1 class="font-display text-xl lg:text-2xl font-bold text-bordeaux-800">
              Les Mets Chics
            </h1>
            <p class="text-xs text-charcoal-600 -mt-1">Gastronomie raffinée</p>
          </div>
        </NuxtLink>

        <!-- Navigation Desktop -->
        <nav class="hidden lg:flex items-center gap-8">
          <NuxtLink 
            v-for="item in navigation" 
            :key="item.name"
            :to="item.href"
            class="text-charcoal-700 hover:text-bordeaux-800 font-medium transition-colors duration-300 relative group"
          >
            {{ item.name }}
            <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-bordeaux-800 transition-all duration-300 group-hover:w-full"></span>
          </NuxtLink>
        </nav>

        <!-- Actions -->
        <div class="flex items-center gap-3">
          
          <!-- Search Button -->
          <button 
            class="p-2 text-charcoal-600 hover:text-bordeaux-800 hover:bg-cream-100 rounded-xl transition-all duration-300"
            @click="toggleSearch"
          >
            <Icon name="lucide:search" class="w-5 h-5" />
          </button>

          <!-- User Menu (Connected) -->
          <div v-if="authStore.isAuthenticated" class="relative" ref="userMenuRef">
            <button 
              @click="toggleUserMenu"
              class="flex items-center gap-2 p-2 hover:bg-cream-100 rounded-xl transition-all duration-300"
            >
              <UserAvatar 
                :avatar="authStore.user?.avatar" 
                :name="authStore.user?.name" 
                size="sm" 
              />
              <span class="hidden sm:block text-sm font-medium text-charcoal-800">
                {{ authStore.user?.name }}
              </span>
              <Icon name="lucide:chevron-down" class="w-4 h-4 text-charcoal-600" />
            </button>

            <!-- Dropdown Menu -->
            <Transition
              enter-active-class="transition ease-out duration-200"
              enter-from-class="opacity-0 scale-95"
              enter-to-class="opacity-100 scale-100"
              leave-active-class="transition ease-in duration-150"
              leave-from-class="opacity-100 scale-100"
              leave-to-class="opacity-0 scale-95"
            >
              <div 
                v-if="showUserMenu"
                class="absolute right-0 mt-2 w-56 bg-white rounded-2xl shadow-premium border border-cream-200 py-2"
              >
                <NuxtLink 
                  v-for="item in userMenuItems" 
                  :key="item.name"
                  :to="item.href"
                  class="flex items-center gap-3 px-4 py-3 text-sm text-charcoal-700 hover:bg-cream-50 hover:text-bordeaux-800 transition-colors duration-200"
                  @click="showUserMenu = false"
                >
                  <Icon :name="item.icon" class="w-4 h-4" />
                  {{ item.name }}
                </NuxtLink>
                
                <hr class="my-2 border-cream-200" />
                
                <button 
                  @click="logout"
                  class="flex items-center gap-3 w-full px-4 py-3 text-sm text-charcoal-700 hover:bg-cream-50 hover:text-bordeaux-800 transition-colors duration-200"
                >
                  <Icon name="lucide:log-out" class="w-4 h-4" />
                  Se déconnecter
                </button>
              </div>
            </Transition>
          </div>

          <!-- Auth Buttons (Not Connected) -->
          <div v-else class="hidden sm:flex items-center gap-2">
            <Button 
              variant="ghost" 
              size="sm"
              tag="NuxtLink"
              to="/connexion"
            >
              Connexion
            </Button>
            <Button 
              variant="primary" 
              size="sm"
              tag="NuxtLink"
              to="/inscription"
            >
              Inscription
            </Button>
          </div>

          <!-- Mobile Menu Button -->
          <button 
            @click="toggleMobileMenu"
            class="lg:hidden p-2 text-charcoal-600 hover:text-bordeaux-800 hover:bg-cream-100 rounded-xl transition-all duration-300"
          >
            <Icon :name="showMobileMenu ? 'lucide:x' : 'lucide:menu'" class="w-5 h-5" />
          </button>
        </div>
      </div>
    </div>

    <!-- Mobile Menu -->
    <Transition
      enter-active-class="transition ease-out duration-300"
      enter-from-class="opacity-0 -translate-y-4"
      enter-to-class="opacity-100 translate-y-0"
      leave-active-class="transition ease-in duration-200"
      leave-from-class="opacity-100 translate-y-0"
      leave-to-class="opacity-0 -translate-y-4"
    >
      <div 
        v-if="showMobileMenu"
        class="lg:hidden bg-white border-t border-cream-200 shadow-soft"
      >
        <div class="px-4 py-6 space-y-4">
          
          <!-- Navigation Links -->
          <nav class="space-y-2">
            <NuxtLink 
              v-for="item in navigation" 
              :key="item.name"
              :to="item.href"
              class="block px-4 py-3 text-charcoal-700 hover:text-bordeaux-800 hover:bg-cream-50 rounded-xl font-medium transition-all duration-200"
              @click="showMobileMenu = false"
            >
              {{ item.name }}
            </NuxtLink>
          </nav>

          <!-- Auth Section -->
          <div v-if="!authStore.isAuthenticated" class="pt-4 border-t border-cream-200 space-y-2">
            <Button 
              variant="outline" 
              size="md"
              full-width
              tag="NuxtLink"
              to="/connexion"
              @click="showMobileMenu = false"
            >
              Connexion
            </Button>
            <Button 
              variant="primary" 
              size="md"
              full-width
              tag="NuxtLink"
              to="/inscription"
              @click="showMobileMenu = false"
            >
              Inscription
            </Button>
          </div>

          <!-- User Menu Mobile -->
          <div v-else class="pt-4 border-t border-cream-200">
            <div class="flex items-center gap-3 mb-4">
              <UserAvatar 
                :avatar="authStore.user?.avatar" 
                :name="authStore.user?.name" 
                size="md" 
              />
              <div>
                <p class="font-medium text-charcoal-800">{{ authStore.user?.name }}</p>
                <p class="text-sm text-charcoal-600">{{ authStore.user?.email }}</p>
              </div>
            </div>
            
            <nav class="space-y-1">
              <NuxtLink 
                v-for="item in userMenuItems" 
                :key="item.name"
                :to="item.href"
                class="flex items-center gap-3 px-4 py-3 text-charcoal-700 hover:text-bordeaux-800 hover:bg-cream-50 rounded-xl transition-all duration-200"
                @click="showMobileMenu = false"
              >
                <Icon :name="item.icon" class="w-4 h-4" />
                {{ item.name }}
              </NuxtLink>
              
              <button 
                @click="logout"
                class="flex items-center gap-3 w-full px-4 py-3 text-charcoal-700 hover:text-bordeaux-800 hover:bg-cream-50 rounded-xl transition-all duration-200"
              >
                <Icon name="lucide:log-out" class="w-4 h-4" />
                Se déconnecter
              </button>
            </nav>
          </div>
        </div>
      </div>
    </Transition>

    <!-- Search Overlay -->
    <Transition
      enter-active-class="transition ease-out duration-300"
      enter-from-class="opacity-0"
      enter-to-class="opacity-100"
      leave-active-class="transition ease-in duration-200"
      leave-from-class="opacity-100"
      leave-to-class="opacity-0"
    >
      <div 
        v-if="showSearch"
        class="fixed inset-0 z-50 bg-charcoal-900/50 backdrop-blur-sm"
        @click="showSearch = false"
      >
        <div class="max-w-2xl mx-auto mt-20 px-4">
          <div class="bg-white rounded-2xl shadow-premium p-6" @click.stop>
            <div class="flex items-center gap-4 mb-4">
              <Icon name="lucide:search" class="w-5 h-5 text-charcoal-600" />
              <input 
                type="text"
                placeholder="Rechercher une recette, un ingrédient..."
                class="flex-1 text-lg border-none outline-none placeholder-charcoal-400"
                v-model="searchQuery"
                @keyup.enter="performSearch"
              />
              <button 
                @click="showSearch = false"
                class="p-2 text-charcoal-600 hover:text-bordeaux-800 rounded-lg"
              >
                <Icon name="lucide:x" class="w-5 h-5" />
              </button>
            </div>
            
            <!-- Search Results Preview -->
            <div v-if="searchQuery" class="border-t border-cream-200 pt-4">
              <p class="text-sm text-charcoal-600 mb-2">Suggestions</p>
              <!-- TODO: Implement search results -->
            </div>
          </div>
        </div>
      </div>
    </Transition>
  </header>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'

const authStore = useAuthStore()

// State
const showMobileMenu = ref(false)
const showUserMenu = ref(false)
const showSearch = ref(false)
const searchQuery = ref('')
const userMenuRef = ref(null)

// Navigation
const navigation = [
  { name: 'Accueil', href: '/' },
  { name: 'Recettes', href: '/recettes' },
  { name: 'Catégories', href: '/categories' },
  { name: 'À propos', href: '/a-propos' },
]

const userMenuItems = [
  { name: 'Mon profil', href: '/profil', icon: 'lucide:user' },
  { name: 'Mes favoris', href: '/favoris', icon: 'lucide:heart' },
  { name: 'Paramètres', href: '/parametres', icon: 'lucide:settings' },
]

// Methods
const toggleMobileMenu = () => {
  showMobileMenu.value = !showMobileMenu.value
  if (showMobileMenu.value) {
    showUserMenu.value = false
    showSearch.value = false
  }
}

const toggleUserMenu = () => {
  showUserMenu.value = !showUserMenu.value
  if (showUserMenu.value) {
    showMobileMenu.value = false
  }
}

const toggleSearch = () => {
  showSearch.value = !showSearch.value
  if (showSearch.value) {
    showMobileMenu.value = false
    showUserMenu.value = false
  }
}

const performSearch = () => {
  if (searchQuery.value.trim()) {
    navigateTo(`/recettes?search=${encodeURIComponent(searchQuery.value)}`)
    showSearch.value = false
    searchQuery.value = ''
  }
}

const logout = async () => {
  await authStore.logout()
  showUserMenu.value = false
  showMobileMenu.value = false
  navigateTo('/')
}

// Close menus on outside click
const handleClickOutside = (event) => {
  if (userMenuRef.value && !userMenuRef.value.contains(event.target)) {
    showUserMenu.value = false
  }
}

onMounted(() => {
  document.addEventListener('click', handleClickOutside)
})

onUnmounted(() => {
  document.removeEventListener('click', handleClickOutside)
})

// Close menus on route change
watch(() => useRoute().path, () => {
  showMobileMenu.value = false
  showUserMenu.value = false
  showSearch.value = false
})
</script>