import { defineStore } from 'pinia'

export const useMenuStore = defineStore('menu', {
  state: () => ({
    // État du menu
    isOpen: false,
    activeSection: '',
    
    // Recherche
    searchOpen: false,
    searchQuery: '',
    
    // Notifications menu
    notifications: {
      favoris: 0,
      messages: 0,
      admin: 0
    },
    
    // Préférences utilisateur
    preferences: {
      compactMode: false,
      showBadges: true,
      animationsEnabled: true
    }
  }),

  getters: {
    // Total des notifications
    totalNotifications: (state) => {
      return Object.values(state.notifications).reduce((sum, count) => sum + count, 0)
    },

    // Vérifier si une section a des notifications
    hasNotifications: (state) => (section) => {
      return state.notifications[section] > 0
    },

    // Menu items avec notifications
    menuItemsWithBadges: (state) => {
      return {
        favoris: state.notifications.favoris,
        messages: state.notifications.messages,
        admin: state.notifications.admin
      }
    }
  },

  actions: {
    // Gestion du menu principal
    openMenu() {
      this.isOpen = true
      
      // Empêcher le scroll sur mobile
      if (process.client) {
        const { isMobile } = useResponsive()
        if (isMobile.value) {
          document.body.style.overflow = 'hidden'
        }
      }
    },

    closeMenu() {
      this.isOpen = false
      
      // Restaurer le scroll
      if (process.client) {
        document.body.style.overflow = ''
      }
    },

    toggleMenu() {
      if (this.isOpen) {
        this.closeMenu()
      } else {
        this.openMenu()
      }
    },

    // Gestion des sections
    setActiveSection(section) {
      this.activeSection = section
    },

    // Gestion de la recherche
    openSearch() {
      this.searchOpen = true
      this.closeMenu() // Fermer le menu si ouvert
    },

    closeSearch() {
      this.searchOpen = false
      this.searchQuery = ''
    },

    toggleSearch() {
      if (this.searchOpen) {
        this.closeSearch()
      } else {
        this.openSearch()
      }
    },

    setSearchQuery(query) {
      this.searchQuery = query
    },

    // Gestion des notifications
    updateNotification(type, count) {
      if (this.notifications.hasOwnProperty(type)) {
        this.notifications[type] = Math.max(0, count)
      }
    },

    incrementNotification(type) {
      if (this.notifications.hasOwnProperty(type)) {
        this.notifications[type]++
      }
    },

    clearNotification(type) {
      if (this.notifications.hasOwnProperty(type)) {
        this.notifications[type] = 0
      }
    },

    clearAllNotifications() {
      Object.keys(this.notifications).forEach(key => {
        this.notifications[key] = 0
      })
    },

    // Gestion des préférences
    updatePreference(key, value) {
      if (this.preferences.hasOwnProperty(key)) {
        this.preferences[key] = value
        
        // Sauvegarder en localStorage
        if (process.client) {
          localStorage.setItem('menu-preferences', JSON.stringify(this.preferences))
        }
      }
    },

    loadPreferences() {
      if (process.client) {
        const saved = localStorage.getItem('menu-preferences')
        if (saved) {
          try {
            const preferences = JSON.parse(saved)
            this.preferences = { ...this.preferences, ...preferences }
          } catch (e) {
            console.warn('Erreur lors du chargement des préférences menu:', e)
          }
        }
      }
    },

    // Reset complet
    resetMenu() {
      this.closeMenu()
      this.closeSearch()
      this.activeSection = ''
      this.clearAllNotifications()
    },

    // Initialisation
    initMenu() {
      this.loadPreferences()
      
      // Fermer le menu au changement de route
      if (process.client) {
        const router = useRouter()
        router.afterEach(() => {
          this.closeMenu()
        })
      }
    }
  }
})