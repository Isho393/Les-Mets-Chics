<template>
  <footer class="bg-charcoal-900 text-white">
    
    <!-- Main Footer -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 lg:gap-12">
        
        <!-- Brand Section -->
        <div class="lg:col-span-1">
          <div class="flex items-center gap-3 mb-6">
            <div class="w-12 h-12 bg-gradient-to-br from-bordeaux-600 to-bordeaux-800 rounded-xl flex items-center justify-center shadow-premium">
              <Icon name="lucide:chef-hat" class="w-6 h-6 text-white" />
            </div>
            <div>
              <h3 class="font-display text-xl font-bold text-white">Les Mets Chics</h3>
              <p class="text-xs text-charcoal-400">Gastronomie raffinée</p>
            </div>
          </div>
          
          <p class="text-charcoal-300 mb-6 leading-relaxed">
            Découvrez l'art de la gastronomie française à travers nos recettes d'exception, créées par des chefs passionnés.
          </p>
          
          <!-- Social Links -->
          <div class="flex gap-3">
            <a 
              v-for="social in socialLinks" 
              :key="social.name"
              :href="social.href"
              class="w-10 h-10 bg-charcoal-800 hover:bg-bordeaux-700 rounded-xl flex items-center justify-center transition-all duration-300 hover:scale-110"
              :aria-label="social.name"
            >
              <Icon :name="social.icon" class="w-5 h-5" />
            </a>
          </div>
        </div>

        <!-- Navigation Links -->
        <div>
          <h4 class="font-semibold text-white mb-6">Navigation</h4>
          <ul class="space-y-3">
            <li v-for="link in navigationLinks" :key="link.name">
              <NuxtLink 
                :to="link.href"
                class="text-charcoal-300 hover:text-gold-400 transition-colors duration-300 flex items-center gap-2 group"
              >
                <Icon name="lucide:arrow-right" class="w-3 h-3 opacity-0 group-hover:opacity-100 transition-opacity duration-300" />
                {{ link.name }}
              </NuxtLink>
            </li>
          </ul>
        </div>

        <!-- Categories -->
        <div>
          <h4 class="font-semibold text-white mb-6">Catégories</h4>
          <ul class="space-y-3">
            <li v-for="category in categoryLinks" :key="category.name">
              <NuxtLink 
                :to="category.href"
                class="text-charcoal-300 hover:text-gold-400 transition-colors duration-300 flex items-center gap-2 group"
              >
                <Icon name="lucide:arrow-right" class="w-3 h-3 opacity-0 group-hover:opacity-100 transition-opacity duration-300" />
                {{ category.name }}
              </NuxtLink>
            </li>
          </ul>
        </div>

        <!-- Newsletter -->
        <div>
          <h4 class="font-semibold text-white mb-6">Newsletter</h4>
          <p class="text-charcoal-300 mb-4 text-sm">
            Recevez nos dernières recettes et conseils culinaires
          </p>
          
          <form @submit.prevent="subscribeNewsletter" class="space-y-3">
            <input 
              v-model="email"
              type="email"
              placeholder="Votre email"
              class="w-full px-4 py-3 bg-charcoal-800 border border-charcoal-700 rounded-xl text-white placeholder-charcoal-400 focus:outline-none focus:ring-2 focus:ring-bordeaux-500 focus:border-transparent transition-all duration-300"
              required
            />
            <Button 
              type="submit"
              variant="gold" 
              size="sm"
              full-width
              :loading="isSubscribing"
            >
              S'abonner
            </Button>
          </form>
          
          <p class="text-xs text-charcoal-400 mt-3">
            Pas de spam, désabonnement facile
          </p>
        </div>
      </div>
    </div>

    <!-- Bottom Bar -->
    <div class="border-t border-charcoal-800">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
          
          <!-- Copyright -->
          <div class="text-charcoal-400 text-sm">
            © {{ currentYear }} Les Mets Chics. Tous droits réservés.
          </div>
          
          <!-- Legal Links -->
          <div class="flex gap-6 text-sm">
            <NuxtLink 
              v-for="legal in legalLinks" 
              :key="legal.name"
              :to="legal.href"
              class="text-charcoal-400 hover:text-gold-400 transition-colors duration-300"
            >
              {{ legal.name }}
            </NuxtLink>
          </div>
        </div>
      </div>
    </div>

    <!-- Back to Top -->
    <Transition
      enter-active-class="transition ease-out duration-300"
      enter-from-class="opacity-0 scale-95"
      enter-to-class="opacity-100 scale-100"
      leave-active-class="transition ease-in duration-200"
      leave-from-class="opacity-100 scale-100"
      leave-to-class="opacity-0 scale-95"
    >
      <button 
        v-if="showBackToTop"
        @click="scrollToTop"
        class="fixed bottom-6 right-6 w-12 h-12 bg-bordeaux-700 hover:bg-bordeaux-600 text-white rounded-full shadow-premium hover:shadow-gold transition-all duration-300 hover:scale-110 z-40"
        aria-label="Retour en haut"
      >
        <Icon name="lucide:arrow-up" class="w-5 h-5 mx-auto" />
      </button>
    </Transition>
  </footer>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'

// State
const email = ref('')
const isSubscribing = ref(false)
const showBackToTop = ref(false)

// Computed
const currentYear = computed(() => new Date().getFullYear())

// Data
const socialLinks = [
  { name: 'Facebook', icon: 'lucide:facebook', href: '#' },
  { name: 'Instagram', icon: 'lucide:instagram', href: '#' },
  { name: 'Twitter', icon: 'lucide:twitter', href: '#' },
  { name: 'YouTube', icon: 'lucide:youtube', href: '#' }
]

const navigationLinks = [
  { name: 'Accueil', href: '/' },
  { name: 'Recettes', href: '/recettes' },
  { name: 'Catégories', href: '/categories' },
  { name: 'À propos', href: '/a-propos' },
  { name: 'Contact', href: '/contact' }
]

const categoryLinks = [
  { name: 'Entrées', href: '/categories/entrees' },
  { name: 'Plats principaux', href: '/categories/plats' },
  { name: 'Desserts', href: '/categories/desserts' },
  { name: 'Boissons', href: '/categories/boissons' }
]

const legalLinks = [
  { name: 'Mentions légales', href: '/mentions-legales' },
  { name: 'Confidentialité', href: '/confidentialite' },
  { name: 'CGV', href: '/cgv' }
]

// Methods
const subscribeNewsletter = async () => {
  if (!email.value) return
  
  isSubscribing.value = true
  
  try {
    // TODO: Implement newsletter subscription
    await new Promise(resolve => setTimeout(resolve, 1000)) // Simulate API call
    
    // Success feedback
    email.value = ''
    // TODO: Show success message
    
  } catch (error) {
    // TODO: Show error message
    console.error('Newsletter subscription failed:', error)
  } finally {
    isSubscribing.value = false
  }
}

const scrollToTop = () => {
  window.scrollTo({
    top: 0,
    behavior: 'smooth'
  })
}

const handleScroll = () => {
  showBackToTop.value = window.scrollY > 300
}

// Lifecycle
onMounted(() => {
  window.addEventListener('scroll', handleScroll)
})

onUnmounted(() => {
  window.removeEventListener('scroll', handleScroll)
})
</script>