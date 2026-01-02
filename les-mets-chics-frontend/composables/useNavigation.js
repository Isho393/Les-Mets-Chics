export const useNavigation = () => {
  const { user, isAuthenticated, isActive, isPending, isAdmin, isAuthor } = useAuth()
  const route = useRoute()

  // Menus principaux selon le rôle
  const getMainMenuItems = () => {
    const baseItems = [
      { name: 'Accueil', path: '/', icon: 'lucide:home' }
    ]

    if (!isAuthenticated.value) {
      return [
        ...baseItems,
        { name: 'Recettes', path: '/connexion', icon: 'lucide:chef-hat', requiresAuth: true },
        { name: 'Origines', path: '/connexion', icon: 'lucide:globe', requiresAuth: true },
        { name: 'À propos', path: '/a-propos', icon: 'lucide:info' }
      ]
    }

    if (isPending.value) {
      return [
        ...baseItems,
        { name: 'Statut du compte', path: '/en-attente', icon: 'lucide:clock' }
      ]
    }

    if (isActive.value) {
      const items = [
        ...baseItems,
        { name: 'Recettes', path: '/recettes', icon: 'lucide:chef-hat' },
        { name: 'Origines', path: '/categories', icon: 'lucide:globe' }
      ]

      // Ajout selon le rôle
      if (isAdmin.value) {
        items.push({ name: 'Panel Admin', path: '/admin', icon: 'lucide:settings', external: true })
      } else if (isAuthor.value) {
        items.push({ name: 'Mon Dashboard', path: '/admin', icon: 'lucide:layout-dashboard', external: true })
      }

      return items
    }

    return baseItems
  }

  // Menu utilisateur (dropdown profil)
  const getUserMenuItems = () => {
    if (!isAuthenticated.value) return []

    const items = [
      { name: 'Mon Profil', path: '/profil', icon: 'lucide:user' }
    ]

    if (isActive.value && (isAdmin.value || isAuthor.value)) {
      items.push({ 
        name: isAdmin.value ? 'Panel Admin' : 'Dashboard', 
        path: '/admin', 
        icon: 'lucide:external-link',
        external: true 
      })
    }

    items.push({ name: 'Déconnexion', action: 'logout', icon: 'lucide:log-out' })

    return items
  }

  // Breadcrumbs dynamiques
  const getBreadcrumbs = () => {
    const segments = route.path.split('/').filter(Boolean)
    const breadcrumbs = [{ name: 'Accueil', path: '/' }]

    let currentPath = ''
    segments.forEach((segment, index) => {
      currentPath += `/${segment}`
      
      let name = segment.charAt(0).toUpperCase() + segment.slice(1)
      
      // Noms personnalisés
      const customNames = {
        'recettes': 'Recettes',
        'categories': 'Origines',
        'profil': 'Mon Profil',
        'a-propos': 'À propos'
      }
      
      if (customNames[segment]) {
        name = customNames[segment]
      }

      breadcrumbs.push({
        name,
        path: currentPath,
        isLast: index === segments.length - 1
      })
    })

    return breadcrumbs
  }

  // Actions contextuelles par page
  const getPageActions = () => {
    const path = route.path

    if (path.startsWith('/recettes/') && path !== '/recettes') {
      return [
        { name: 'J\'aime', action: 'like', icon: 'lucide:heart', requiresAuth: true },
        { name: 'Commenter', action: 'comment', icon: 'lucide:message-circle', requiresAuth: true },
        { name: 'Partager', action: 'share', icon: 'lucide:share-2' }
      ]
    }

    if (path.startsWith('/categories/')) {
      return [
        { name: 'Filtrer', action: 'filter', icon: 'lucide:filter' },
        { name: 'Trier', action: 'sort', icon: 'lucide:arrow-up-down' }
      ]
    }

    return []
  }

  // Navigation après connexion
  const redirectAfterLogin = async () => {
    if (isPending.value) {
      await navigateTo('/en-attente')
    } else if (isActive.value) {
      const redirect = route.query.redirect || '/recettes'
      await navigateTo(redirect)
    }
  }

  // Vérification d'accès aux pages
  const canAccessPage = (path) => {
    const protectedPaths = ['/recettes', '/categories', '/profil']
    
    if (protectedPaths.some(p => path.startsWith(p))) {
      return isAuthenticated.value && isActive.value
    }
    
    return true
  }

  return {
    getMainMenuItems,
    getUserMenuItems,
    getBreadcrumbs,
    getPageActions,
    redirectAfterLogin,
    canAccessPage
  }
}