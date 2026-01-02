<template>
  <!-- Overlay backdrop -->
  <Teleport to="body">
    <Transition
      enter-active-class="transition-opacity duration-300"
      enter-from-class="opacity-0"
      enter-to-class="opacity-100"
      leave-active-class="transition-opacity duration-200"
      leave-from-class="opacity-100"
      leave-to-class="opacity-0"
    >
      <div
        v-if="isOpen"
        class="fixed inset-0 z-40 bg-charcoal-900/50 backdrop-blur-sm lg:hidden"
        @click="$emit('close')"
      />
    </Transition>

    <!-- Menu panel -->
    <Transition
      enter-active-class="transition-transform duration-300 ease-out"
      enter-from-class="translate-x-full"
      enter-to-class="translate-x-0"
      leave-active-class="transition-transform duration-200 ease-in"
      leave-from-class="translate-x-0"
      leave-to-class="translate-x-full"
    >
      <div
        v-if="isOpen"
        class="fixed top-0 right-0 bottom-0 z-50 w-full max-w-sm bg-white shadow-premium lg:hidden"
        @click.stop
      >
        <!-- Header du menu -->
        <div class="flex items-center justify-between p-4 border-b border-cream-200">
          <div class="flex items-center gap-3">
            <div class="w-8 h-8 bg-gradient-to-br from-bordeaux-600 to-bordeaux-800 rounded-lg flex items-center justify-center">
              <Icon name="lucide:chef-hat" class="w-4 h-4 text-white" />
            </div>
            <span class="font-display font-bold text-bordeaux-800">Les Mets Chics</span>
          </div>
          
          <ui-menu-button
            :is-open="true"
            @toggle="$emit('close')"
          />
        </div>

        <!-- Contenu scrollable -->
        <div class="flex-1 overflow-y-auto overscroll-contain">
          <div class="p-4 space-y-6">
            
            <!-- Section utilisateur (si connecté) -->
            <ui-menu-section
              v-if="isAuthenticated"
              variant="highlighted"
              title="Mon compte"
            >
              <div class="flex items-center gap-3 mb-4">
                <UserAvatar 
                  :avatar="user?.avatar" 
                  :name="user?.name" 
                  size="md" 
                />
                <div class="flex-1 min-w-0">
                  <p class="font-medium text-bordeaux-900 truncate">{{ user?.name }}</p>
                  <p class="text-sm text-charcoal-600 truncate">{{ user?.email }}</p>
                  <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium mt-1"
                        :class="statusClasses">
                    {{ statusText }}
                  </span>
                </div>
              </div>
            </ui-menu-section>

            <!-- Navigation principale -->
            <ui-menu-section title="Navigation">
              <ui-menu-item
                v-for="item in navigationItems"
                :key="item.name"
                :label="item.name"
                :icon="item.icon"
                :to="item.href"
                :is-active="$route.path === item.href"
                @click="$emit('close')"
              />
            </ui-menu-section>

            <!-- Actions utilisateur (si connecté) -->
            <ui-menu-section
              v-if="isAuthenticated"
              title="Mon espace"
              divider
            >
              <ui-menu-item
                v-for="item in userMenuItems"
                :key="item.name"
                :label="item.name"
                :icon="item.icon"
                :to="item.href"
                :badge="item.badge"
                @click="$emit('close')"
              />
              
              <!-- Lien admin si autorisé -->
              <ui-menu-item
                v-if="canAccessAdmin"
                :label="isAdmin ? 'Panel Admin' : 'Mon Dashboard'"
                icon="lucide:settings"
                variant="primary"
                @click="navigateToAdmin"
              />
            </ui-menu-section>

            <!-- Section authentification (si non connecté) -->
            <ui-menu-section
              v-if="!isAuthenticated"
              variant="highlighted"
              title="Rejoignez-nous"
              description="Accédez aux recettes exclusives"
            >
              <div class="space-y-3">
                <ui-menu-item
                  label="Se connecter"
                  icon="lucide:log-in"
                  to="/connexion"
                  variant="ghost"
                  @click="$emit('close')"
                />
                <ui-menu-item
                  label="Créer un compte"
                  icon="lucide:user-plus"
                  to="/inscription"
                  variant="primary"
                  @click="$emit('close')"
                />
              </div>
            </ui-menu-section>

            <!-- Section déconnexion (si connecté) -->
            <ui-menu-section v-if="isAuthenticated" divider>
              <ui-menu-item
                label="Se déconnecter"
                icon="lucide:log-out"
                variant="danger"
                @click="handleLogout"
              />
            </ui-menu-section>

            <!-- Footer links -->
            <ui-menu-section title="Informations" divider variant="compact">
              <ui-menu-item
                v-for="item in footerLinks"
                :key="item.name"
                :label="item.name"
                :to="item.href"
                size="sm"
                variant="ghost"
                @click="$emit('close')"
              />
            </ui-menu-section>
          </div>
        </div>
      </div>
    </Transition>
  </Teleport>
</template>

<script setup>
const props = defineProps({
  isOpen: {
    type: Boolean,
    default: false
  }
})

defineEmits(['close'])

const { user, isAuthenticated, isAdmin, isAuthor, canAccessAdminPanel, logout } = useAuth()
const { navigateToAdmin } = useNavigation()
const route = useRoute()

// Computed
const canAccessAdmin = computed(() => canAccessAdminPanel.value)

const statusClasses = computed(() => {
  const status = user.value?.status
  return {
    'bg-green-100 text-green-800': status === 'active',
    'bg-yellow-100 text-yellow-800': status === 'pending',
    'bg-red-100 text-red-800': status === 'suspended'
  }
})

const statusText = computed(() => {
  const status = user.value?.status
  return {
    'active': 'Actif',
    'pending': 'En attente',
    'suspended': 'Suspendu'
  }[status] || status
})

// Navigation items selon le statut
const navigationItems = computed(() => {
  if (!isAuthenticated.value) {
    return [
      { name: 'Accueil', href: '/', icon: 'lucide:home' },
      { name: 'À propos', href: '/a-propos', icon: 'lucide:info' },
      { name: 'Contact', href: '/contact', icon: 'lucide:mail' }
    ]
  }
  
  return [
    { name: 'Recettes', href: '/recettes', icon: 'lucide:chef-hat' },
    { name: 'Catégories', href: '/categories', icon: 'lucide:grid-3x3' },
    { name: 'À propos', href: '/a-propos', icon: 'lucide:info' }
  ]
})

const userMenuItems = computed(() => [
  { name: 'Mon profil', href: '/profil', icon: 'lucide:user' },
  { name: 'Mes favoris', href: '/favoris', icon: 'lucide:heart' },
  { name: 'Paramètres', href: '/parametres', icon: 'lucide:settings' }
])

const footerLinks = [
  { name: 'Mentions légales', href: '/mentions-legales' },
  { name: 'Confidentialité', href: '/confidentialite' },
  { name: 'CGV', href: '/cgv' }
]

// Methods
const handleLogout = async () => {
  await logout()
  emit('close')
}

// Gestion des gestes tactiles pour fermer
let startX = 0
let currentX = 0

const handleTouchStart = (e) => {
  startX = e.touches[0].clientX
}

const handleTouchMove = (e) => {
  currentX = e.touches[0].clientX
}

const handleTouchEnd = () => {
  const diffX = currentX - startX
  if (diffX > 100) { // Swipe vers la droite > 100px
    emit('close')
  }
}

onMounted(() => {
  if (process.client) {
    document.addEventListener('touchstart', handleTouchStart, { passive: true })
    document.addEventListener('touchmove', handleTouchMove, { passive: true })
    document.addEventListener('touchend', handleTouchEnd, { passive: true })
  }
})

onUnmounted(() => {
  if (process.client) {
    document.removeEventListener('touchstart', handleTouchStart)
    document.removeEventListener('touchmove', handleTouchMove)
    document.removeEventListener('touchend', handleTouchEnd)
  }
})
</script>