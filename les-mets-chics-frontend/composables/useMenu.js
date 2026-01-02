export const useMenu = () => {
  const menuStore = useMenuStore()
  const { isMobile } = useResponsive()
  const route = useRoute()
  
  // États réactifs depuis le store
  const isOpen = computed(() => menuStore.isOpen)
  const searchOpen = computed(() => menuStore.searchOpen)
  const searchQuery = computed(() => menuStore.searchQuery)
  const notifications = computed(() => menuStore.notifications)
  const totalNotifications = computed(() => menuStore.totalNotifications)
  
  // Actions du menu
  const openMenu = () => menuStore.openMenu()
  const closeMenu = () => menuStore.closeMenu()
  const toggleMenu = () => menuStore.toggleMenu()
  
  // Actions de recherche
  const openSearch = () => menuStore.openSearch()
  const closeSearch = () => menuStore.closeSearch()
  const toggleSearch = () => menuStore.toggleSearch()
  const setSearchQuery = (query) => menuStore.setSearchQuery(query)
  
  // Gestion des notifications
  const updateNotification = (type, count) => menuStore.updateNotification(type, count)
  const clearNotification = (type) => menuStore.clearNotification(type)
  
  // Utilitaires de navigation
  const isActiveRoute = (path) => {
    const currentPath = route.path
    if (path === '/') {
      return currentPath === '/'
    }
    return currentPath.startsWith(path)
  }
  
  // Gestion des raccourcis clavier
  const handleKeyboardShortcuts = (event) => {
    // Ctrl/Cmd + K pour ouvrir la recherche
    if ((event.ctrlKey || event.metaKey) && event.key === 'k') {
      event.preventDefault()
      toggleSearch()
    }
    
    // Escape pour fermer
    if (event.key === 'Escape') {
      if (searchOpen.value) {
        closeSearch()
      } else if (isOpen.value) {
        closeMenu()
      }
    }
  }
  
  // Initialisation
  onMounted(() => {
    menuStore.initMenu()
    
    if (process.client) {
      document.addEventListener('keydown', handleKeyboardShortcuts)
    }
  })
  
  onUnmounted(() => {
    if (process.client) {
      document.removeEventListener('keydown', handleKeyboardShortcuts)
      // Nettoyer les styles
      document.body.style.overflow = ''
    }
  })
  
  return {
    // États
    isOpen,
    searchOpen,
    searchQuery,
    notifications,
    totalNotifications,
    
    // Actions menu
    openMenu,
    closeMenu,
    toggleMenu,
    
    // Actions recherche
    openSearch,
    closeSearch,
    toggleSearch,
    setSearchQuery,
    
    // Notifications
    updateNotification,
    clearNotification,
    
    // Utilitaires
    isActiveRoute
  }
}