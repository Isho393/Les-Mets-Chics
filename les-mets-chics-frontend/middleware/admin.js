export default defineNuxtRouteMiddleware((to, from) => {
  const { user, isAuthenticated, isAdmin } = useAuth()
  
  if (!isAuthenticated.value) {
    return navigateTo('/connexion')
  }
  
  if (!isAdmin.value) {
    throw createError({
      statusCode: 403,
      statusMessage: 'Accès refusé - Droits administrateur requis'
    })
  }
})