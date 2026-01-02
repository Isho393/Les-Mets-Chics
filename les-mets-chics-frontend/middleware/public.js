export default defineNuxtRouteMiddleware((to, from) => {
  const authStore = useAuthStore()
  const { isPublicRoute } = useNavigation()
  
  // Ce middleware s'assure que les routes publiques restent accessibles
  // même pour les utilisateurs connectés
  
  // Si c'est une route publique, permettre l'accès
  if (isPublicRoute(to.path)) {
    return
  }
  
  // Pour les routes non-publiques, vérifier l'authentification
  if (!authStore.isAuthenticated) {
    return navigateTo('/connexion')
  }
  
  // Si l'utilisateur est connecté mais en attente
  if (authStore.userStatus === 'pending') {
    return navigateTo('/en-attente')
  }
})