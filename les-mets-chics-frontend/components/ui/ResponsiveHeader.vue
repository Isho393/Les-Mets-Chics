<template>
  <header class="sticky top-0 z-40 bg-white/95 backdrop-blur-md border-b border-cream-200 shadow-soft">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex items-center justify-between h-16 lg:h-20">
        
        <!-- Logo -->
        <NuxtLink :to="logoLink" class="flex items-center gap-3 group">
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
        <ui-desktop-menu />

        <!-- Actions Mobile -->
        <div class="flex items-center gap-3 lg:hidden">
          
          <!-- Recherche mobile (si connecté) -->
          <button 
            v-if="isAuthenticated"
            class="p-2 text-charcoal-600 hover:text-bordeaux-800 hover:bg-cream-100 rounded-xl transition-all duration-300"
            @click="toggleSearch"
            aria-label="Rechercher"
          >
            <Icon name="lucide:search" class="w-5 h-5" />
          </button>

          <!-- Auth buttons mobile (si non connecté) -->
          <div v-if="!isAuthenticated" class="hidden sm:flex items-center gap-2">
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

          <!-- Menu Button -->
          <ui-menu-button
            :is-open="isMenuOpen"
            @toggle="toggleMenu"
          />
        </div>
      </div>
    </div>

    <!-- Menu Mobile -->
    <ui-mobile-menu
      :is-open="isMenuOpen"
      @close="closeMenu"
    />

    <!-- Search Modal (placeholder) -->
    <!-- TODO: Implémenter la modal de recherche -->
  </header>
</template>

<script setup>
const { isAuthenticated } = useAuth()
const { isMenuOpen, toggleMenu, closeMenu } = useMenu()

// Logo link selon le statut
const logoLink = computed(() => {
  return isAuthenticated.value ? '/recettes' : '/'
})

// Methods
const toggleSearch = () => {
  // TODO: Implémenter la modal de recherche
  console.log('Toggle search modal')
}
</script>