<template>
  <footer class="relative bg-gradient-to-br from-bordeaux-900 via-bordeaux-800 to-charcoal-900 text-white overflow-hidden">
    <!-- Pattern de fond -->
    <div class="absolute inset-0 opacity-10">
      <div class="absolute inset-0 bg-pattern-footer"></div>
    </div>
    
    <!-- Contenu principal -->
    <div class="relative">
      <!-- Section principale -->
      <div class="py-16 lg:py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div class="grid grid-cols-1 lg:grid-cols-4 gap-12">
            
            <!-- Branding -->
            <div class="lg:col-span-1">
              <div class="mb-8">
                <div class="flex items-center mb-6">
                  <div class="w-12 h-12 bg-gradient-to-br from-gold-500 to-gold-700 rounded-2xl flex items-center justify-center mr-4">
                    <Icon name="lucide:chef-hat" class="text-white text-2xl" />
                  </div>
                  <span class="text-2xl font-display font-bold">Les Mets Chics</span>
                </div>
                <p class="text-bordeaux-100 leading-relaxed mb-6">
                  L'art culinaire à son apogée. Découvrez des recettes d'exception qui éveilleront vos sens.
                </p>
                <div class="flex items-center text-gold-400">
                  <Icon name="lucide:crown" class="mr-2" />
                  <span class="text-sm font-medium">Gastronomie Premium</span>
                </div>
              </div>
            </div>
            
            <!-- Navigation -->
            <div class="lg:col-span-1">
              <h3 class="text-lg font-display font-semibold mb-6 text-gold-400">Navigation</h3>
              <ul class="space-y-4">
                <li>
                  <NuxtLink to="/" class="footer-link group">
                    <Icon name="lucide:home" class="mr-2 group-hover:scale-110 transition-transform" />
                    Accueil
                  </NuxtLink>
                </li>
                <li>
                  <NuxtLink to="/recettes" class="footer-link group">
                    <Icon name="lucide:chef-hat" class="mr-2 group-hover:scale-110 transition-transform" />
                    Recettes
                  </NuxtLink>
                </li>
                <li>
                  <NuxtLink to="/categories" class="footer-link group">
                    <Icon name="lucide:globe" class="mr-2 group-hover:scale-110 transition-transform" />
                    Origines
                  </NuxtLink>
                </li>
                <li>
                  <NuxtLink to="/a-propos" class="footer-link group">
                    <Icon name="lucide:info" class="mr-2 group-hover:scale-110 transition-transform" />
                    À propos
                  </NuxtLink>
                </li>
                <li>
                  <NuxtLink to="/contact" class="footer-link group">
                    <Icon name="lucide:mail" class="mr-2 group-hover:scale-110 transition-transform" />
                    Contact
                  </NuxtLink>
                </li>
              </ul>
            </div>
            
            <!-- Légal -->
            <div class="lg:col-span-1">
              <h3 class="text-lg font-display font-semibold mb-6 text-gold-400">Informations</h3>
              <ul class="space-y-4">
                <li>
                  <NuxtLink to="/cgv" class="footer-link group">
                    <Icon name="lucide:file-text" class="mr-2 group-hover:scale-110 transition-transform" />
                    Conditions générales
                  </NuxtLink>
                </li>
                <li>
                  <NuxtLink to="/confidentialite" class="footer-link group">
                    <Icon name="lucide:shield" class="mr-2 group-hover:scale-110 transition-transform" />
                    Confidentialité
                  </NuxtLink>
                </li>
                <li>
                  <NuxtLink to="/mentions-legales" class="footer-link group">
                    <Icon name="lucide:scale" class="mr-2 group-hover:scale-110 transition-transform" />
                    Mentions légales
                  </NuxtLink>
                </li>
                <li>
                  <a href="#" class="footer-link group">
                    <Icon name="lucide:help-circle" class="mr-2 group-hover:scale-110 transition-transform" />
                    Aide
                  </a>
                </li>
                <li>
                  <a href="#" class="footer-link group">
                    <Icon name="lucide:headphones" class="mr-2 group-hover:scale-110 transition-transform" />
                    Support
                  </a>
                </li>
              </ul>
            </div>
            
            <!-- Newsletter & Social -->
            <div class="lg:col-span-1">
              <h3 class="text-lg font-display font-semibold mb-6 text-gold-400">Restez connecté</h3>
              
              <!-- Newsletter -->
              <div class="mb-8">
                <p class="text-bordeaux-100 text-sm mb-4">
                  Recevez nos dernières recettes et actualités culinaires
                </p>
                <form @submit.prevent="subscribeNewsletter" class="space-y-3">
                  <div class="relative">
                    <input 
                      v-model="newsletterEmail"
                      type="email" 
                      placeholder="votre@email.com"
                      class="w-full px-4 py-3 bg-white/10 backdrop-blur-sm border border-white/20 rounded-xl text-white placeholder-white/60 focus:ring-2 focus:ring-gold-500 focus:border-transparent transition-all"
                      required
                    />
                  </div>
                  <button 
                    type="submit"
                    :disabled="newsletterLoading"
                    class="w-full px-4 py-3 bg-gradient-to-r from-gold-500 to-gold-700 text-white rounded-xl font-semibold transition-all duration-300 hover:shadow-lg hover:scale-105 disabled:opacity-50 flex items-center justify-center"
                  >
                    <Icon v-if="newsletterLoading" name="lucide:loader-2" class="mr-2 animate-spin" />
                    <Icon v-else name="lucide:send" class="mr-2" />
                    {{ newsletterLoading ? 'Inscription...' : 'S\'abonner' }}
                  </button>
                </form>
              </div>
              
              <!-- Réseaux sociaux -->
              <div>
                <p class="text-bordeaux-100 text-sm mb-4">Suivez-nous</p>
                <div class="flex space-x-4">
                  <a href="#" class="social-link group">
                    <Icon name="lucide:facebook" class="group-hover:scale-110 transition-transform" />
                  </a>
                  <a href="#" class="social-link group">
                    <Icon name="lucide:instagram" class="group-hover:scale-110 transition-transform" />
                  </a>
                  <a href="#" class="social-link group">
                    <Icon name="lucide:twitter" class="group-hover:scale-110 transition-transform" />
                  </a>
                  <a href="#" class="social-link group">
                    <Icon name="lucide:youtube" class="group-hover:scale-110 transition-transform" />
                  </a>
                  <a href="#" class="social-link group">
                    <Icon name="lucide:linkedin" class="group-hover:scale-110 transition-transform" />
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Séparateur élégant -->
      <div class="border-t border-white/10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div class="py-8">
            <div class="flex flex-col md:flex-row items-center justify-between">
              <!-- Copyright -->
              <div class="flex items-center mb-4 md:mb-0">
                <p class="text-bordeaux-200 text-sm">
                  © {{ currentYear }} Les Mets Chics. Tous droits réservés.
                </p>
              </div>
              
              <!-- Stats premium -->
              <div class="flex items-center space-x-8 text-sm">
                <div class="flex items-center text-gold-400">
                  <Icon name="lucide:users" class="mr-2" />
                  <span>{{ stats.members }}+ Membres</span>
                </div>
                <div class="flex items-center text-gold-400">
                  <Icon name="lucide:chef-hat" class="mr-2" />
                  <span>{{ stats.recipes }}+ Recettes</span>
                </div>
                <div class="flex items-center text-gold-400">
                  <Icon name="lucide:award" class="mr-2" />
                  <span>{{ stats.chefs }}+ Chefs</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Particules flottantes -->
    <div class="absolute inset-0 pointer-events-none">
      <div v-for="i in 8" :key="i" 
           class="absolute w-1 h-1 bg-gold-400/20 rounded-full animate-float"
           :style="{
             left: Math.random() * 100 + '%',
             top: Math.random() * 100 + '%',
             animationDelay: Math.random() * 3 + 's',
             animationDuration: (4 + Math.random() * 2) + 's'
           }"></div>
    </div>
  </footer>
</template>

<script setup>
const { success, error } = useNotifications()

// État
const newsletterEmail = ref('')
const newsletterLoading = ref(false)

// Données
const currentYear = computed(() => new Date().getFullYear())

const stats = ref({
  members: '2.5K',
  recipes: '500',
  chefs: '50'
})

// Actions
const subscribeNewsletter = async () => {
  if (!newsletterEmail.value) return
  
  newsletterLoading.value = true
  
  try {
    // Simuler l'inscription
    await new Promise(resolve => setTimeout(resolve, 1500))
    
    success('Inscription réussie ! Merci de rejoindre notre communauté.')
    newsletterEmail.value = ''
  } catch (err) {
    error('Erreur lors de l\'inscription à la newsletter')
  } finally {
    newsletterLoading.value = false
  }
}
</script>

<style scoped>
.footer-link {
  @apply flex items-center text-bordeaux-200 hover:text-gold-400 transition-all duration-300 hover:translate-x-1;
}

.social-link {
  @apply w-10 h-10 bg-white/10 backdrop-blur-sm border border-white/20 rounded-xl flex items-center justify-center text-bordeaux-200 hover:text-gold-400 hover:bg-gold-500/20 transition-all duration-300;
}

/* Pattern de fond */
.bg-pattern-footer {
  background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23f5d085' fill-opacity='0.03'%3E%3Ccircle cx='30' cy='30' r='2'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
}

/* Animation des particules */
@keyframes float {
  0%, 100% { 
    transform: translateY(0px) rotate(0deg); 
    opacity: 0.2;
  }
  50% { 
    transform: translateY(-20px) rotate(180deg); 
    opacity: 0.8;
  }
}

.animate-float {
  animation: float 6s ease-in-out infinite;
}

/* Responsive */
@media (max-width: 768px) {
  .footer-link {
    @apply text-sm;
  }
  
  .social-link {
    @apply w-8 h-8;
  }
}
</style>