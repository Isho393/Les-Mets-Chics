export const useAuth = () => {
  const authStore = useAuthStore()
  const router = useRouter()

  // États réactifs
  const isAuthenticated = computed(() => authStore.isAuthenticated)
  const user = computed(() => authStore.user)
  const userRole = computed(() => authStore.user?.role)
  const userStatus = computed(() => authStore.user?.status)

  // Vérifications de rôle
  const isAdmin = computed(() => userRole.value === 'admin')
  const isAuthor = computed(() => userRole.value === 'auteur')
  const isExplorer = computed(() => userRole.value === 'explorateur')
  const isActive = computed(() => userStatus.value === 'active')
  const isPending = computed(() => userStatus.value === 'pending')

  // Actions d'authentification
  const login = async (credentials) => {
    try {
      const result = await authStore.login(credentials)
      return { success: true, data: result }
    } catch (error) {
      let message = 'Erreur de connexion'
      
      if (error.status === 403) {
        message = 'Votre compte est en attente de validation par un administrateur.'
      } else if (error.status === 401) {
        message = 'Email ou mot de passe incorrect.'
      }
      
      return { success: false, error: message }
    }
  }

  const register = async (credentials) => {
    try {
      const result = await authStore.register(credentials)
      return { success: true, data: result }
    } catch (error) {
      let message = 'Erreur lors de l\'inscription'
      
      if (error.data?.errors) {
        message = Object.values(error.data.errors).flat().join(', ')
      }
      
      return { success: false, error: message }
    }
  }

  const logout = async () => {
    await authStore.logout()
    await router.push('/')
  }

  // Navigation conditionnelle selon le rôle
  const getRedirectPath = () => {
    if (!isAuthenticated.value) return '/connexion'
    if (isPending.value) return '/en-attente'
    
    // Redirection selon le rôle après connexion
    if (isAdmin.value || isAuthor.value) {
      return '/recettes' // ou dashboard
    }
    
    return '/recettes'
  }

  // Vérification des permissions
  const canAccessAdminPanel = computed(() => 
    isAuthenticated.value && isActive.value && (isAdmin.value || isAuthor.value)
  )

  const canLikeRecipes = computed(() => 
    isAuthenticated.value && isActive.value
  )

  const canComment = computed(() => 
    isAuthenticated.value && isActive.value
  )

  // Initialisation de l'authentification
  const initAuth = () => {
    authStore.initAuth()
  }

  return {
    // États
    isAuthenticated,
    user,
    userRole,
    userStatus,
    isAdmin,
    isAuthor,
    isExplorer,
    isActive,
    isPending,
    
    // Actions
    login,
    register,
    logout,
    initAuth,
    
    // Utilitaires
    getRedirectPath,
    canAccessAdminPanel,
    canLikeRecipes,
    canComment
  }
}