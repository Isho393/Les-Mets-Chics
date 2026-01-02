export default defineNuxtRouteMiddleware((to, from) => {
  const authStore = useAuthStore()
  const { isPublicRoute } = useNavigation()
  
  // Permettre l'accès aux routes publiques
  if (isPublicRoute(to.path)) {
    return
  }
  
  // Vérifier l'authentification
  if (!authStore.isAuthenticated) {
    return navigateTo({
      path: '/connexion',
      query: { redirect: to.fullPath }
    })
  }
  
  // Vérifier le statut du compte
  if (authStore.userStatus === 'pending') {
    return navigateTo('/en-attente')
  }
  
  if (authStore.userStatus === 'suspended') {
    // Déconnecter l'utilisateur suspendu
    authStore.logout()
    return navigateTo('/connexion')
  }
  
  // Vérifier que le compte est actif
  if (authStore.userStatus !== 'active') {
    return navigateTo('/connexion')
  }
})
