<template>
  <div class="min-h-screen bg-gradient-to-br from-cream-50 to-bordeaux-50 flex items-center justify-center px-4">
    <div class="max-w-md w-full text-center">
      
      <!-- Error Icon -->
      <div class="w-24 h-24 bg-gradient-to-br from-bordeaux-600 to-bordeaux-800 rounded-full flex items-center justify-center mx-auto mb-8">
        <Icon :name="errorIcon" class="w-12 h-12 text-white" />
      </div>
      
      <!-- Error Title -->
      <h1 class="font-display text-4xl font-bold text-bordeaux-800 mb-4">
        {{ errorTitle }}
      </h1>
      
      <!-- Error Message -->
      <p class="text-lg text-charcoal-600 mb-8 leading-relaxed">
        {{ errorMessage }}
      </p>
      
      <!-- Actions -->
      <div class="space-y-3">
        <ui-button 
          variant="primary" 
          size="lg"
          full-width
          @click="handleError"
        >
          {{ primaryAction }}
        </ui-button>
        
        <ui-button 
          variant="ghost" 
          size="md"
          full-width
          tag="NuxtLink"
          to="/"
        >
          <Icon name="lucide:home" />
          Retour à l'accueil
        </ui-button>
      </div>
      
      <!-- Additional Info -->
      <div class="mt-8 p-4 bg-white rounded-xl shadow-soft">
        <p class="text-sm text-charcoal-500 mb-2">
          Code d'erreur : {{ error.statusCode }}
        </p>
        <p class="text-xs text-charcoal-400">
          Si le problème persiste, contactez notre équipe.
        </p>
      </div>
    </div>
  </div>
</template>

<script setup>
const props = defineProps({
  error: {
    type: Object,
    required: true
  }
})

// SEO
useHead({
  title: `Erreur ${props.error.statusCode} - Les Mets Chics`
})

// Computed properties pour personnaliser selon le type d'erreur
const errorIcon = computed(() => {
  switch (props.error.statusCode) {
    case 404:
      return 'lucide:search-x'
    case 403:
      return 'lucide:shield-x'
    case 500:
      return 'lucide:server-crash'
    default:
      return 'lucide:alert-triangle'
  }
})

const errorTitle = computed(() => {
  switch (props.error.statusCode) {
    case 404:
      return 'Page introuvable'
    case 403:
      return 'Accès refusé'
    case 500:
      return 'Erreur serveur'
    default:
      return 'Une erreur est survenue'
  }
})

const errorMessage = computed(() => {
  switch (props.error.statusCode) {
    case 404:
      return 'La page que vous recherchez n\'existe pas ou a été déplacée.'
    case 403:
      return 'Vous n\'avez pas les permissions nécessaires pour accéder à cette page.'
    case 500:
      return 'Une erreur technique est survenue. Nos équipes ont été notifiées.'
    default:
      return props.error.statusMessage || 'Une erreur inattendue s\'est produite.'
  }
})

const primaryAction = computed(() => {
  switch (props.error.statusCode) {
    case 404:
      return 'Rechercher'
    case 403:
      return 'Se connecter'
    default:
      return 'Réessayer'
  }
})

// Actions
const handleError = () => {
  switch (props.error.statusCode) {
    case 404:
      navigateTo('/recettes')
      break
    case 403:
      navigateTo('/connexion')
      break
    default:
      // Recharger la page
      window.location.reload()
  }
}
</script>