<template>
  <header class="bg-white/80 backdrop-blur-md shadow-sm sticky top-0 z-50">
    <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between items-center h-20">
        <!-- Logo -->
        <NuxtLink to="/" class="flex items-center space-x-3 group">
          <div class="w-12 h-12 bg-gradient-to-br from-primary-500 to-accent-500 rounded-full flex items-center justify-center transform group-hover:scale-110 transition-transform">
            <Icon name="mdi:chef-hat" class="text-white text-2xl" />
          </div>
          <div>
            <h1 class="text-2xl font-serif font-bold text-primary-900">Les Mets Chics</h1>
            <p class="text-xs text-primary-600 -mt-1">Gastronomie raffinée</p>
          </div>
        </NuxtLink>

        <!-- Desktop Navigation -->
        <div class="hidden md:flex items-center space-x-8">
          <NuxtLink to="/" class="nav-link">Accueil</NuxtLink>
          <NuxtLink to="/categories" class="nav-link">Catégories</NuxtLink>
          <NuxtLink to="/recettes" class="nav-link">Recettes</NuxtLink>
          
          <template v-if="authStore.isAuthenticated">
            <NuxtLink to="/profil" class="nav-link flex items-center gap-2">
              <UserAvatar 
                :avatar="authStore.user?.avatar" 
                :name="authStore.user?.name || 'User'" 
                size="sm" 
              />
              {{ authStore.user?.name }}
            </NuxtLink>
            <button @click="handleLogout" class="btn-secondary">
              Déconnexion
            </button>
          </template>
          <template v-else>
            <NuxtLink to="/connexion" class="btn-secondary">Connexion</NuxtLink>
            <NuxtLink to="/inscription" class="btn-primary">Inscription</NuxtLink>
          </template>
        </div>

        <!-- Mobile menu button -->
        <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden p-2">
          <Icon :name="mobileMenuOpen ? 'mdi:close' : 'mdi:menu'" class="text-2xl text-primary-900" />
        </button>
      </div>

      <!-- Mobile Navigation -->
      <div v-if="mobileMenuOpen" class="md:hidden py-4 border-t border-primary-100">
        <div class="flex flex-col space-y-3">
          <NuxtLink to="/" class="mobile-nav-link">Accueil</NuxtLink>
          <NuxtLink to="/categories" class="mobile-nav-link">Catégories</NuxtLink>
          <NuxtLink to="/recettes" class="mobile-nav-link">Recettes</NuxtLink>
          
          <template v-if="authStore.isAuthenticated">
            <NuxtLink to="/profil" class="mobile-nav-link flex items-center gap-2">
              <UserAvatar 
                :avatar="authStore.user?.avatar" 
                :name="authStore.user?.name || 'User'" 
                size="sm" 
              />
              Mon Profil
            </NuxtLink>
            <button @click="handleLogout" class="btn-secondary w-full">Déconnexion</button>
          </template>
          <template v-else>
            <NuxtLink to="/connexion" class="btn-secondary w-full text-center">Connexion</NuxtLink>
            <NuxtLink to="/inscription" class="btn-primary w-full text-center">Inscription</NuxtLink>
          </template>
        </div>
      </div>
    </nav>
  </header>
</template>

<script setup>
const authStore = useAuthStore()
const router = useRouter()
const mobileMenuOpen = ref(false)

const handleLogout = async () => {
  await authStore.logout()
  router.push('/')
  mobileMenuOpen.value = false
}
</script>

<style scoped>
.nav-link {
  @apply text-primary-700 hover:text-primary-900 font-medium transition-colors relative;
}

.nav-link::after {
  content: '';
  @apply absolute bottom-0 left-0 w-0 h-0.5 bg-primary-900 transition-all;
}

.nav-link:hover::after {
  @apply w-full;
}

.mobile-nav-link {
  @apply text-primary-700 hover:text-primary-900 font-medium py-2 px-4 rounded-lg hover:bg-primary-50 transition-colors;
}

.btn-primary {
  @apply bg-[#A3C9A8] text-white px-6 py-2.5 rounded-full font-medium hover:shadow-lg transform hover:-translate-y-0.5 transition-all;
}

.btn-secondary {
  @apply border-2 border-primary-500 text-primary-700 px-6 py-2 rounded-full font-medium hover:bg-primary-50 transition-colors;
}
</style>
