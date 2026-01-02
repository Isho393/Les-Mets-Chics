export default defineNuxtRouteMiddleware((to, from) => {
  const authStore = useAuthStore()
  
  // Routes publiques (accessibles sans authentification)
  const publicRoutes = [
    '/',
    '/connexion',
    '/inscription', 
    '/en-attente',
    '/a-propos',
    '/contact',
    '/cgv',
    '/confidentialite',
    '/mentions-legales',
    '/mot-de-passe-oublie',
    '/reinitialiser-mot-de-passe',
    '/auth/callback'
  ]
  
  // Vérifier si la route est publique
  const isPublicRoute = publicRoutes.some(route => {
    if (route === '/') return to.path === '/'
    return to.path.startsWith(route)
  })
  
  // Si route publique, permettre l'accès
  if (isPublicRoute) {
    return
  }
  
  // Pour toutes les autres routes, vérifier l'authentification
  if (!authStore.isAuthenticated) {
    return navigateTo({
      path: '/connexion',
      query: { redirect: to.fullPath }
    })
  }
  
  // Vérifier le statut du compte
  if (authStore.userStatus === 'pending') {
    // Si l'utilisateur essaie d'accéder à /en-attente, permettre
    if (to.path === '/en-attente') {
      return
    }
    return navigateTo('/en-attente')
  }
  
  if (authStore.userStatus === 'suspended') {
    // Déconnecter l'utilisateur suspendu
    authStore.logout()
    return navigateTo('/connexion')
  }
  
  // Vérifier que le compte est actif pour les routes protégées
  if (authStore.userStatus !== 'active') {
    return navigateTo('/connexion')
  }
})