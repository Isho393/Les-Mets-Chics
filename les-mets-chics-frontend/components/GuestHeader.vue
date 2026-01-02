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

        <!-- Navigation Desktop (Limitée) -->
        <nav class="hidden lg:flex items-center gap-8">
          <NuxtLink 
            v-for="item in guestNavigation" 
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
          
          <!-- Auth Buttons Desktop -->
          <div class="hidden sm:flex items-center gap-2">
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
              v-for="item in guestNavigation" 
              :key="item.name"
              :to="item.href"
              class="block px-4 py-3 text-charcoal-700 hover:text-bordeaux-800 hover:bg-cream-50 rounded-xl font-medium transition-all duration-200"
              @click="showMobileMenu = false"
            >
              {{ item.name }}
            </NuxtLink>
          </nav>

          <!-- Auth Section -->
          <div class="pt-4 border-t border-cream-200 space-y-2">
            <ui-button 
              variant="outline" 
              size="md"
              full-width
              tag="NuxtLink"
              to="/connexion"
              @click="showMobileMenu = false"
            >
              Connexion
            </ui-button>
            <ui-button 
              variant="primary" 
              size="md"
              full-width
              tag="NuxtLink"
              to="/inscription"
              @click="showMobileMenu = false"
            >
              Inscription
            </ui-button>
          </div>

          <!-- CTA Section -->
          <div class="pt-4 border-t border-cream-200">
            <div class="bg-gradient-to-r from-bordeaux-50 to-gold-50 rounded-xl p-4 text-center">
              <h3 class="font-semibold text-bordeaux-800 mb-2">Rejoignez-nous !</h3>
              <p class="text-sm text-charcoal-600 mb-3">
                Découvrez des recettes exclusives et rejoignez notre communauté de gourmets.
              </p>
              <ui-button 
                variant="gold" 
                size="sm"
                tag="NuxtLink"
                to="/inscription"
                @click="showMobileMenu = false"
              >
                Commencer gratuitement
              </ui-button>
            </div>
          </div>
        </div>
      </div>
    </Transition>
  </header>
</template>

<script setup>
import { ref } from 'vue'

// State
const showMobileMenu = ref(false)

// Navigation pour visiteurs (limitée)
const guestNavigation = [
  { name: 'Accueil', href: '/' },
  { name: 'À propos', href: '/a-propos' },
  { name: 'Contact', href: '/contact' }
]

// Methods
const toggleMobileMenu = () => {
  showMobileMenu.value = !showMobileMenu.value
}

// Close menu on route change
watch(() => useRoute().path, () => {
  showMobileMenu.value = false
})
</script>