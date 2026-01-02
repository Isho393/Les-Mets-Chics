import { defineStore } from 'pinia'

export const useUiStore = defineStore('ui', {
  state: () => ({
    // Navigation
    mobileMenuOpen: false,
    
    // Modales
    modals: {
      login: false,
      register: false,
      profile: false,
      changePassword: false
    },
    
    // Loading states globaux
    globalLoading: false,
    
    // Notifications/Messages
    notifications: [],
    
    // Thème (pour futur mode sombre)
    theme: 'light',
    
    // Sidebar/Drawer states
    sidebarOpen: false,
    
    // Search
    searchOpen: false,
    searchQuery: '',
    
    // Scroll position (pour restaurer après navigation)
    scrollPositions: new Map()
  }),

  getters: {
    // Vérifier si une modale est ouverte
    isModalOpen: (state) => (modalName) => {
      return state.modals[modalName] || false
    },

    // Vérifier si au moins une modale est ouverte
    hasOpenModal: (state) => {
      return Object.values(state.modals).some(isOpen => isOpen)
    },

    // Notifications par type
    notificationsByType: (state) => (type) => {
      return state.notifications.filter(notif => notif.type === type)
    },

    // Notifications non lues
    unreadNotifications: (state) => {
      return state.notifications.filter(notif => !notif.read)
    }
  },

  actions: {
    // Gestion du menu mobile
    toggleMobileMenu() {
      this.mobileMenuOpen = !this.mobileMenuOpen
    },

    closeMobileMenu() {
      this.mobileMenuOpen = false
    },

    // Gestion des modales
    openModal(modalName) {
      // Fermer toutes les autres modales
      Object.keys(this.modals).forEach(key => {
        this.modals[key] = false
      })
      
      // Ouvrir la modale demandée
      if (this.modals.hasOwnProperty(modalName)) {
        this.modals[modalName] = true
      }
    },

    closeModal(modalName) {
      if (this.modals.hasOwnProperty(modalName)) {
        this.modals[modalName] = false
      }
    },

    closeAllModals() {
      Object.keys(this.modals).forEach(key => {
        this.modals[key] = false
      })
    },

    // Gestion du loading global
    setGlobalLoading(loading) {
      this.globalLoading = loading
    },

    // Gestion des notifications
    addNotification(notification) {
      const id = Date.now() + Math.random()
      const newNotification = {
        id,
        type: 'info', // info, success, warning, error
        title: '',
        message: '',
        duration: 5000, // 5 secondes par défaut
        read: false,
        createdAt: new Date(),
        ...notification
      }

      this.notifications.unshift(newNotification)

      // Auto-suppression après la durée spécifiée
      if (newNotification.duration > 0) {
        setTimeout(() => {
          this.removeNotification(id)
        }, newNotification.duration)
      }

      return id
    },

    removeNotification(id) {
      const index = this.notifications.findIndex(notif => notif.id === id)
      if (index > -1) {
        this.notifications.splice(index, 1)
      }
    },

    markNotificationAsRead(id) {
      const notification = this.notifications.find(notif => notif.id === id)
      if (notification) {
        notification.read = true
      }
    },

    clearAllNotifications() {
      this.notifications = []
    },

    // Notifications rapides
    showSuccess(message, title = 'Succès') {
      return this.addNotification({
        type: 'success',
        title,
        message,
        duration: 4000
      })
    },

    showError(message, title = 'Erreur') {
      return this.addNotification({
        type: 'error',
        title,
        message,
        duration: 6000
      })
    },

    showWarning(message, title = 'Attention') {
      return this.addNotification({
        type: 'warning',
        title,
        message,
        duration: 5000
      })
    },

    showInfo(message, title = 'Information') {
      return this.addNotification({
        type: 'info',
        title,
        message,
        duration: 4000
      })
    },

    // Gestion de la recherche
    toggleSearch() {
      this.searchOpen = !this.searchOpen
      if (!this.searchOpen) {
        this.searchQuery = ''
      }
    },

    setSearchQuery(query) {
      this.searchQuery = query
    },

    // Gestion du thème
    setTheme(theme) {
      this.theme = theme
      if (process.client) {
        localStorage.setItem('theme', theme)
        document.documentElement.setAttribute('data-theme', theme)
      }
    },

    initTheme() {
      if (process.client) {
        const savedTheme = localStorage.getItem('theme') || 'light'
        this.setTheme(savedTheme)
      }
    },

    // Gestion des positions de scroll
    saveScrollPosition(routeName, position) {
      this.scrollPositions.set(routeName, position)
    },

    getScrollPosition(routeName) {
      return this.scrollPositions.get(routeName) || 0
    },

    // Utilitaires
    resetUiState() {
      this.mobileMenuOpen = false
      this.closeAllModals()
      this.searchOpen = false
      this.searchQuery = ''
      this.sidebarOpen = false
    }
  }
})