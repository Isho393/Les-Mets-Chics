export default defineNuxtPlugin((nuxtApp) => {
  const uiStore = useUiStore()
  
  // Gestion des erreurs Vue
  nuxtApp.vueApp.config.errorHandler = (error, context) => {
    console.error('Vue Error:', error, context)
    
    // Afficher une notification d'erreur
    uiStore.showError(
      'Une erreur inattendue s\'est produite',
      'Erreur'
    )
  }
  
  // Gestion des erreurs non capturées
  if (process.client) {
    window.addEventListener('unhandledrejection', (event) => {
      console.error('Unhandled Promise Rejection:', event.reason)
      
      // Afficher une notification d'erreur
      uiStore.showError(
        'Une erreur de connexion s\'est produite',
        'Erreur réseau'
      )
      
      // Empêcher l'affichage de l'erreur dans la console
      event.preventDefault()
    })
    
    window.addEventListener('error', (event) => {
      console.error('Global Error:', event.error)
      
      // Afficher une notification d'erreur pour les erreurs critiques
      if (event.error && !event.error.message?.includes('ResizeObserver')) {
        uiStore.showError(
          'Une erreur technique s\'est produite',
          'Erreur'
        )
      }
    })
  }
})