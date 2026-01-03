import { defineStore } from 'pinia'

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: null,
    token: null,
    isAuthenticated: false,
    loading: false,
    error: null
  }),

  getters: {
    userRole: (state) => state.user?.role,
    userStatus: (state) => state.user?.status,
    isAdmin: (state) => state.user?.role === 'admin',
    isAuthor: (state) => state.user?.role === 'auteur',
    isExplorer: (state) => state.user?.role === 'explorateur',
    isActive: (state) => state.user?.status === 'active',
    isPending: (state) => state.user?.status === 'pending',
    isSuspended: (state) => state.user?.status === 'suspended',
    canAccessAdmin: (state) => {
      return state.isAuthenticated && 
             state.user?.status === 'active' && 
             (state.user?.role === 'admin' || state.user?.role === 'auteur')
    }
  },

  actions: {
    setLoading(loading) {
      this.loading = loading
    },

    setError(error) {
      this.error = error
    },

    clearError() {
      this.error = null
    },
    async register(credentials) {
      this.setLoading(true)
      this.clearError()
      
      try {
        const config = useRuntimeConfig()
        
        const { data, error } = await $fetch(`${config.public.apiBase}/register`, {
          method: 'POST',
          body: credentials
        })
        
        return data
      } catch (error) {
        this.setError(error)
        throw error
      } finally {
        this.setLoading(false)
      }
    },

    async login(credentials) {
      this.setLoading(true)
      this.clearError()
      
      try {
        const config = useRuntimeConfig()
        
        const data = await $fetch(`${config.public.apiBase}/login`, {
          method: 'POST',
          body: credentials
        })
        
        this.token = data.token
        this.user = data.user
        this.isAuthenticated = true
        
        if (process.client) {
          localStorage.setItem('token', data.token)
          localStorage.setItem('user', JSON.stringify(data.user))
        }
        
        return data
      } catch (error) {
        this.setError(error)
        throw error
      } finally {
        this.setLoading(false)
      }
    },

    async logout() {
      this.setLoading(true)
      
      try {
        const config = useRuntimeConfig()
        
        if (this.token) {
          await $fetch(`${config.public.apiBase}/logout`, {
            method: 'POST',
            headers: {
              'Authorization': `Bearer ${this.token}`
            }
          })
        }
      } catch (e) {
        console.error('Logout error:', e)
      } finally {
        // Toujours nettoyer l'état local
        this.user = null
        this.token = null
        this.isAuthenticated = false
        this.clearError()
        
        if (process.client) {
          localStorage.removeItem('token')
          localStorage.removeItem('user')
        }
        
        this.setLoading(false)
      }
    },

    async fetchUser() {
      if (!this.token) return
      
      try {
        const config = useRuntimeConfig()
        const data = await $fetch(`${config.public.apiBase}/user`, {
          headers: {
            'Authorization': `Bearer ${this.token}`
          }
        })
        
        this.user = data.user
        this.isAuthenticated = true
        
        // Mettre à jour localStorage
        if (process.client) {
          localStorage.setItem('user', JSON.stringify(data.user))
        }
      } catch (e) {
        console.error('Fetch user error:', e)
        // Token invalide, déconnecter
        this.logout()
      }
    },

    initAuth() {
      if (process.client) {
        const token = localStorage.getItem('token')
        const user = localStorage.getItem('user')
        
        if (token && user) {
          try {
            this.token = token
            this.user = JSON.parse(user)
            this.isAuthenticated = true
            // Vérifier la validité du token
            this.fetchUser()
          } catch (e) {
            console.error('Init auth error:', e)
            this.logout()
          }
        }
      }
    },

    async updateProfile(formData) {
      this.setLoading(true)
      this.clearError()
      
      try {
        const config = useRuntimeConfig()
        
        const data = await $fetch(`${config.public.apiBase}/user/profile`, {
          method: 'POST',
          headers: {
            'Authorization': `Bearer ${this.token}`
          },
          body: formData
        })
        
        this.user = data.user
        
        if (process.client) {
          localStorage.setItem('user', JSON.stringify(data.user))
        }
        
        return data
      } catch (error) {
        this.setError(error)
        throw error
      } finally {
        this.setLoading(false)
      }
    },

    async updatePassword(passwords) {
      this.setLoading(true)
      this.clearError()
      
      try {
        const config = useRuntimeConfig()
        
        const data = await $fetch(`${config.public.apiBase}/user/password`, {
          method: 'PUT',
          headers: {
            'Authorization': `Bearer ${this.token}`
          },
          body: passwords
        })
        
        return data
      } catch (error) {
        this.setError(error)
        throw error
      } finally {
        this.setLoading(false)
      }
    }
  }
})
