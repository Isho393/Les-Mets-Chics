<template>
  <header class="bg-white shadow-sm border-b border-gray-200">
    <div class="flex items-center justify-between px-6 py-4">
      
      <!-- Mobile menu button -->
      <button @click="toggleSidebar" class="lg:hidden p-2 rounded-md text-gray-600 hover:bg-gray-100">
        <Icon name="lucide:menu" class="w-6 h-6" />
      </button>

      <!-- Breadcrumb -->
      <div class="hidden lg:flex items-center space-x-2 text-sm text-gray-600">
        <span>Admin</span>
        <Icon name="lucide:chevron-right" class="w-4 h-4" />
        <span class="text-gray-900 font-medium">{{ pageTitle }}</span>
      </div>

      <!-- Right side -->
      <div class="flex items-center space-x-4">
        
        <!-- Notifications -->
        <button class="relative p-2 text-gray-600 hover:text-gray-900">
          <Icon name="lucide:bell" class="w-6 h-6" />
          <span v-if="notificationCount" 
                class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">
            {{ notificationCount }}
          </span>
        </button>

        <!-- User menu -->
        <div class="relative">
          <button @click="showUserMenu = !showUserMenu" 
                  class="flex items-center space-x-3 p-2 rounded-lg hover:bg-gray-100">
            <div class="w-8 h-8 bg-bordeaux-500 rounded-full flex items-center justify-center">
              <span class="text-white text-sm font-semibold">
                {{ user?.name?.charAt(0) }}
              </span>
            </div>
            <span class="hidden md:block text-sm font-medium text-gray-900">
              {{ user?.name }}
            </span>
            <Icon name="lucide:chevron-down" class="w-4 h-4 text-gray-600" />
          </button>

          <!-- Dropdown -->
          <div v-if="showUserMenu" 
               class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-1 z-50">
            <NuxtLink to="/profil" class="dropdown-item">
              <Icon name="lucide:user" class="w-4 h-4 mr-3" />
              Mon Profil
            </NuxtLink>
            <button @click="logout" class="dropdown-item w-full text-left">
              <Icon name="lucide:log-out" class="w-4 h-4 mr-3" />
              Déconnexion
            </button>
          </div>
        </div>
      </div>
    </div>
  </header>
</template>

<script setup>
const { user, logout: authLogout } = useAuth()
const route = useRoute()

const showUserMenu = ref(false)
const notificationCount = ref(3) // À connecter avec l'API

const pageTitle = computed(() => {
  const titles = {
    '/admin': 'Dashboard',
    '/admin/users': 'Utilisateurs',
    '/admin/recipes': 'Recettes',
    '/admin/comments': 'Commentaires',
    '/admin/categories': 'Catégories'
  }
  return titles[route.path] || 'Admin'
})

const toggleSidebar = () => {
  // Émettre un événement pour toggle la sidebar
}

const logout = async () => {
  await authLogout()
  await navigateTo('/')
}

// Fermer le menu utilisateur au clic extérieur
onClickOutside(showUserMenu, () => {
  showUserMenu.value = false
})
</script>

<style scoped>
.dropdown-item {
  @apply flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100;
}
</style>