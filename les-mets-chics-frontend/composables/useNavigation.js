export const useNavigation = () => {
  const { isAuthenticated, isActive, isPending, userRole } = useAuth()
  const router = useRouter()
  const route = useRoute()

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
    '/reinitialiser-mot-de-passe'
  ]

  // Routes qui nécessitent une authentification
  const protectedRoutes = [
    '/recettes',
    '/categories',
    '/profil'
  ]

  // Vérification si une route est publique
  const isPublicRoute = (path) => {
    return publicRoutes.some(route => {
      if (route === '/') return path === '/'
      return path.startsWith(route)
    })
  }

  // Vérification si une route est protégée
  const isProtectedRoute = (path) => {
    return protectedRoutes.some(route => path.startsWith(route))
  }

  // Navigation sécurisée vers une recette
  const navigateToRecipe = async (slug) => {
    if (!isAuthenticated.value) {
      await router.push('/connexion')
      return
    }

    if (isPending.value) {
      await router.push('/en-attente')
      return
    }

    await router.push(`/recettes/${slug}`)
  }

  // Navigation sécurisée vers une catégorie
  const navigateToCategory = async (slug) => {
    if (!isAuthenticated.value) {
      await router.push('/connexion')
      return
    }

    if (isPending.value) {
      await router.push('/en-attente')
      return
    }

    await router.push(`/categories/${slug}`)
  }

  // Navigation vers le panel admin selon le rôle
  const navigateToAdmin = () => {
    const config = useRuntimeConfig()
    const adminUrl = `${config.public.apiBase.replace('/api', '')}/admin`
    
    if (userRole.value === 'admin' || userRole.value === 'auteur') {
      window.open(adminUrl, '_blank')
    }
  }

  // Redirection après connexion selon le rôle et le statut
  const redirectAfterLogin = async () => {
    if (isPending.value) {
      await router.push('/en-attente')
      return
    }

    // Rediriger vers la page demandée initialement ou vers l'accueil
    const redirectTo = route.query.redirect || '/recettes'
    await router.push(redirectTo)
  }

  // Vérification des permissions pour une action
  const canPerformAction = (action) => {
    if (!isAuthenticated.value || !isActive.value) {
      return false
    }

    switch (action) {
      case 'like':
      case 'comment':
        return true
      case 'admin':
        return userRole.value === 'admin' || userRole.value === 'auteur'
      default:
        return false
    }
  }

  // Gestion des clics sur les recettes (pour visiteurs)
  const handleRecipeClick = async (slug) => {
    if (isAuthenticated.value && isActive.value) {
      await router.push(`/recettes/${slug}`)
    } else {
      // Stocker la destination pour redirection après connexion
      await router.push({
        path: '/connexion',
        query: { redirect: `/recettes/${slug}` }
      })
    }
  }

  // Navigation du breadcrumb
  const getBreadcrumb = () => {
    const path = route.path
    const breadcrumb = [{ name: 'Accueil', path: '/' }]

    if (path.startsWith('/recettes/')) {
      breadcrumb.push({ name: 'Recettes', path: '/recettes' })
      if (route.params.slug) {
        breadcrumb.push({ name: 'Détail', path: path })
      }
    } else if (path.startsWith('/categories/')) {
      breadcrumb.push({ name: 'Catégories', path: '/categories' })
      if (route.params.slug) {
        breadcrumb.push({ name: 'Catégorie', path: path })
      }
    } else if (path === '/profil') {
      breadcrumb.push({ name: 'Mon Profil', path: '/profil' })
    }

    return breadcrumb
  }

  return {
    // Vérifications
    isPublicRoute,
    isProtectedRoute,
    canPerformAction,

    // Navigation
    navigateToRecipe,
    navigateToCategory,
    navigateToAdmin,
    redirectAfterLogin,
    handleRecipeClick,

    // Utilitaires
    getBreadcrumb,
    publicRoutes,
    protectedRoutes
  }
}