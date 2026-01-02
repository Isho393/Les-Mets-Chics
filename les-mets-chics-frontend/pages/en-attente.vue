<template>
  <div class="min-h-screen bg-gradient-to-br from-cream-50 to-bordeaux-50 flex items-center justify-center px-4">
    <div class="max-w-md w-full">
      
      <!-- Card principale -->
      <div class="bg-white rounded-2xl shadow-premium p-8 text-center">
        
        <!-- Icon -->
        <div class="w-20 h-20 bg-gradient-to-br from-gold-500 to-gold-600 rounded-full flex items-center justify-center mx-auto mb-6">
          <Icon name="lucide:clock" class="w-10 h-10 text-white" />
        </div>
        
        <!-- Titre -->
        <h1 class="font-display text-3xl font-bold text-bordeaux-800 mb-4">
          Compte en attente
        </h1>
        
        <!-- Message principal -->
        <p class="text-lg text-charcoal-600 mb-6 leading-relaxed">
          Merci pour votre inscription ! Votre compte est en cours de validation par notre équipe.
        </p>
        
        <!-- Détails -->
        <div class="bg-cream-50 rounded-xl p-4 mb-6 text-left">
          <h3 class="font-semibold text-bordeaux-800 mb-3 flex items-center gap-2">
            <Icon name="lucide:info" class="w-4 h-4" />
            Prochaines étapes
          </h3>
          <ul class="space-y-2 text-sm text-charcoal-600">
            <li class="flex items-start gap-2">
              <Icon name="lucide:check" class="w-4 h-4 text-green-500 mt-0.5 flex-shrink-0" />
              <span>Votre inscription a été reçue avec succès</span>
            </li>
            <li class="flex items-start gap-2">
              <Icon name="lucide:clock" class="w-4 h-4 text-gold-500 mt-0.5 flex-shrink-0" />
              <span>Validation en cours par notre équipe (24-48h)</span>
            </li>
            <li class="flex items-start gap-2">
              <Icon name="lucide:mail" class="w-4 h-4 text-blue-500 mt-0.5 flex-shrink-0" />
              <span>Vous recevrez un email de confirmation</span>
            </li>
          </ul>
        </div>
        
        <!-- Informations utilisateur -->
        <div v-if="user" class="bg-bordeaux-50 rounded-xl p-4 mb-6">
          <div class="flex items-center gap-3">
            <UserAvatar 
              :avatar="user.avatar" 
              :name="user.name" 
              size="sm" 
            />
            <div class="text-left">
              <p class="font-medium text-bordeaux-800">{{ user.name }}</p>
              <p class="text-sm text-charcoal-600">{{ user.email }}</p>
            </div>
          </div>
        </div>
        
        <!-- Actions -->
        <div class="space-y-3">
          <ui-button 
            variant="primary" 
            size="lg"
            full-width
            tag="NuxtLink"
            to="/"
          >
            <Icon name="lucide:home" />
            Retour à l'accueil
          </ui-button>
          
          <ui-button 
            variant="ghost" 
            size="md"
            full-width
            @click="logout"
          >
            <Icon name="lucide:log-out" />
            Se déconnecter
          </ui-button>
        </div>
      </div>
      
      <!-- Informations supplémentaires -->
      <div class="mt-6 text-center">
        <p class="text-sm text-charcoal-500 mb-4">
          Des questions ? Contactez notre équipe
        </p>
        
        <div class="flex justify-center gap-4">
          <NuxtLink 
            to="/contact"
            class="text-bordeaux-700 hover:text-bordeaux-800 text-sm font-medium transition-colors duration-200"
          >
            <Icon name="lucide:mail" class="w-4 h-4 inline mr-1" />
            Contact
          </NuxtLink>
          
          <NuxtLink 
            to="/a-propos"
            class="text-bordeaux-700 hover:text-bordeaux-800 text-sm font-medium transition-colors duration-200"
          >
            <Icon name="lucide:info" class="w-4 h-4 inline mr-1" />
            À propos
          </NuxtLink>
        </div>
      </div>
      
      <!-- Animation de fond -->
      <div class="fixed inset-0 pointer-events-none overflow-hidden -z-10">
        <div class="absolute top-20 left-10 w-2 h-2 bg-gold-400 rounded-full animate-float opacity-60"></div>
        <div class="absolute top-40 right-20 w-1 h-1 bg-bordeaux-300 rounded-full animate-float" style="animation-delay: 1s;"></div>
        <div class="absolute bottom-32 left-1/4 w-3 h-3 bg-gold-500 rounded-full animate-float opacity-40" style="animation-delay: 2s;"></div>
      </div>
    </div>
  </div>
</template>

<script setup>
const { user, logout } = useAuth()

// SEO
useHead({
  title: 'Compte en attente - Les Mets Chics',
  meta: [
    { name: 'description', content: 'Votre compte Les Mets Chics est en cours de validation. Vous recevrez un email de confirmation une fois votre compte activé.' },
    { name: 'robots', content: 'noindex, nofollow' }
  ]
})

// Middleware pour vérifier que l'utilisateur est bien en attente
definePageMeta({
  middleware: (to, from) => {
    const authStore = useAuthStore()
    
    // Si pas connecté, rediriger vers connexion
    if (!authStore.isAuthenticated) {
      return navigateTo('/connexion')
    }
    
    // Si compte déjà actif, rediriger vers les recettes
    if (authStore.userStatus === 'active') {
      return navigateTo('/recettes')
    }
    
    // Si compte suspendu, déconnecter et rediriger
    if (authStore.userStatus === 'suspended') {
      authStore.logout()
      return navigateTo('/connexion')
    }
  }
})
</script>