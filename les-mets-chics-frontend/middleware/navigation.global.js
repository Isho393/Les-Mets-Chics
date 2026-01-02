export default defineNuxtRouteMiddleware((to) => {
  const { isAuthenticated, isActive, isPending, canAccessPage } = useNavigation()
  
  // Pages publiques (toujours accessibles)
  const publicPages = ['/', '/connexion', '/inscription', '/en-attente', '/a-propos', '/contact', '/mentions-legales', '/cgv', '/confidentialite']
  
  // Vérifier si la page est publique
  if (publicPages.includes(to.path)) {
    return
  }
  
  // Pages protégées nécessitent une authentification
  if (!isAuthenticated.value) {
    return navigateTo(`/connexion?redirect=${encodeURIComponent(to.path)}`)
  }
  
  // Utilisateurs en attente ne peuvent accéder qu'à /en-attente
  if (isPending.value && to.path !== '/en-attente') {
    return navigateTo('/en-attente')
  }
  
  // Vérifier l'accès à la page spécifique
  if (!canAccessPage(to.path)) {
    return navigateTo('/recettes')
  }
})