export default defineNuxtRouteMiddleware((to, from) => {
  const authStore = useAuthStore()
  
  // Si l'utilisateur est connecté, rediriger vers l'accueil des membres
  if (authStore.isAuthenticated) {
    if (authStore.userStatus === 'pending') {
      return navigateTo('/en-attente')
    }
    
    return navigateTo('/recettes')
  }
})