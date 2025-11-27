import { defineStore } from 'pinia'

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: null,
    token: null,
    isAuthenticated: false
  }),

  actions: {
    async register(credentials) {
      const config = useRuntimeConfig()
      const { data, error } = await useFetch(`${config.public.apiBase}/register`, {
        method: 'POST',
        body: credentials,
        // Ne pas définir Content-Type pour FormData, le navigateur le fera automatiquement
      })
      
      if (error.value) throw error.value
      return data.value
    },

    async login(credentials) {
      const config = useRuntimeConfig()
      const { data, error } = await useFetch(`${config.public.apiBase}/login`, {
        method: 'POST',
        body: credentials
      })
      
      if (error.value) throw error.value
      
      this.token = data.value.token
      this.user = data.value.user
      this.isAuthenticated = true
      
      // Store token in localStorage
      if (process.client) {
        localStorage.setItem('token', data.value.token)
        localStorage.setItem('user', JSON.stringify(data.value.user))
      }
      
      return data.value
    },

    async logout() {
      const config = useRuntimeConfig()
      
      try {
        await $fetch(`${config.public.apiBase}/logout`, {
          method: 'POST',
          headers: {
            'Authorization': `Bearer ${this.token}`
          }
        })
      } catch (e) {
        console.error('Logout error:', e)
      }
      
      this.user = null
      this.token = null
      this.isAuthenticated = false
      
      if (process.client) {
        localStorage.removeItem('token')
        localStorage.removeItem('user')
      }
    },

    async fetchUser() {
      const config = useRuntimeConfig()
      
      if (!this.token) return
      
      try {
        const data = await $fetch(`${config.public.apiBase}/user`, {
          headers: {
            'Authorization': `Bearer ${this.token}`
          }
        })
        
        this.user = data.user
        this.isAuthenticated = true
      } catch (e) {
        this.logout()
      }
    },

    initAuth() {
      if (process.client) {
        const token = localStorage.getItem('token')
        const user = localStorage.getItem('user')
        
        if (token && user) {
          this.token = token
          this.user = JSON.parse(user)
          this.isAuthenticated = true
          this.fetchUser()
        }
      }
    },

    async updateProfile(formData) {
      const config = useRuntimeConfig()
      
      const { data, error } = await useFetch(`${config.public.apiBase}/user/profile`, {
        method: 'POST',
        headers: {
          'Authorization': `Bearer ${this.token}`
        },
        body: formData
      })
      
      if (error.value) throw error.value
      
      this.user = data.value.user
      
      if (process.client) {
        localStorage.setItem('user', JSON.stringify(data.value.user))
      }
      
      return data.value
    },

    async updatePassword(passwords) {
      const config = useRuntimeConfig()
      
      const { data, error } = await useFetch(`${config.public.apiBase}/user/password`, {
        method: 'PUT',
        headers: {
          'Authorization': `Bearer ${this.token}`
        },
        body: passwords
      })
      
      if (error.value) throw error.value
      
      return data.value
    }
  }
})
