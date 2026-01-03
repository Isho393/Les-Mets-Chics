<template>
  <div class="fixed inset-y-0 left-0 z-50 w-64 bg-bordeaux-900 transform transition-transform lg:translate-x-0" 
       :class="{ '-translate-x-full': !sidebarOpen }">
    
    <!-- Logo -->
    <div class="flex items-center justify-center h-16 bg-bordeaux-800">
      <h1 class="text-xl font-display font-bold text-white">Admin Panel</h1>
    </div>

    <!-- Navigation -->
    <nav class="mt-8">
      <div class="px-4 space-y-2">
        <NuxtLink 
          v-for="item in menuItems" 
          :key="item.path"
          :to="item.path"
          class="nav-item group"
          :class="{ 'active': $route.path === item.path }"
        >
          <Icon :name="item.icon" class="nav-icon" />
          <span>{{ item.name }}</span>
        </NuxtLink>
      </div>

      <!-- Divider -->
      <div class="my-6 border-t border-bordeaux-700"></div>

      <!-- Actions -->
      <div class="px-4 space-y-2">
        <a href="/admin" target="_blank" class="nav-item group">
          <Icon name="lucide:external-link" class="nav-icon" />
          <span>Panel Filament</span>
        </a>
        
        <NuxtLink to="/" class="nav-item group">
          <Icon name="lucide:home" class="nav-icon" />
          <span>Retour au site</span>
        </NuxtLink>
      </div>
    </nav>
  </div>

  <!-- Mobile overlay -->
  <div v-if="sidebarOpen" @click="closeSidebar" 
       class="fixed inset-0 z-40 bg-black bg-opacity-50 lg:hidden"></div>
</template>

<script setup>
const route = useRoute()
const sidebarOpen = ref(false)

const menuItems = [
  { name: 'Dashboard', path: '/admin', icon: 'lucide:layout-dashboard' },
  { name: 'Utilisateurs', path: '/admin/users', icon: 'lucide:users' },
  { name: 'Recettes', path: '/admin/recipes', icon: 'lucide:chef-hat' },
  { name: 'Commentaires', path: '/admin/comments', icon: 'lucide:message-circle' },
  { name: 'Catégories', path: '/admin/categories', icon: 'lucide:folder' }
]

const closeSidebar = () => {
  sidebarOpen.value = false
}

defineExpose({
  sidebarOpen
})
</script>

<style scoped>
.nav-item {
  @apply flex items-center px-4 py-3 text-bordeaux-100 rounded-lg transition-colors hover:bg-bordeaux-800 hover:text-white;
}

.nav-item.active {
  @apply bg-bordeaux-700 text-white;
}

.nav-icon {
  @apply w-5 h-5 mr-3;
}
</style>