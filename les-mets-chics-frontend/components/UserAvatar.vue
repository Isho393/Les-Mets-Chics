<template>
  <div :class="avatarClasses" class="relative">
    <!-- Image avatar -->
    <img 
      v-if="avatarUrl"
      :src="avatarUrl"
      :alt="displayName"
      class="w-full h-full object-cover"
      @error="handleImageError"
    />
    
    <!-- Initiales par défaut -->
    <div v-else class="w-full h-full flex items-center justify-center bg-gradient-to-br from-bordeaux-500 to-bordeaux-700 text-white font-semibold">
      {{ initials }}
    </div>
    
    <!-- Badge de statut -->
    <div v-if="showStatus && user?.status" :class="statusClasses"></div>
    
    <!-- Badge de rôle -->
    <div v-if="showRole && user?.role && (user.role === 'admin' || user.role === 'auteur')" :class="roleClasses">
      <Icon :name="roleIcon" class="w-3 h-3" />
    </div>
  </div>
</template>

<script setup>
const props = defineProps({
  user: {
    type: Object,
    default: null
  },
  
  size: {
    type: String,
    default: 'md',
    validator: (value) => ['xs', 'sm', 'md', 'lg', 'xl'].includes(value)
  },
  
  showStatus: {
    type: Boolean,
    default: false
  },
  
  showRole: {
    type: Boolean,
    default: false
  },
  
  avatar: String,
  name: String
})

// État
const imageError = ref(false)

// Computed
const displayName = computed(() => {
  return props.name || props.user?.name || 'Utilisateur'
})

const avatarUrl = computed(() => {
  if (imageError.value) return null
  return props.avatar || props.user?.avatar || props.user?.profile_photo_url
})

const initials = computed(() => {
  const name = displayName.value
  const words = name.split(' ')
  if (words.length >= 2) {
    return (words[0][0] + words[1][0]).toUpperCase()
  }
  return name.substring(0, 2).toUpperCase()
})

const avatarClasses = computed(() => {
  const sizes = {
    xs: 'w-6 h-6',
    sm: 'w-8 h-8', 
    md: 'w-10 h-10',
    lg: 'w-12 h-12',
    xl: 'w-16 h-16'
  }
  
  return [
    sizes[props.size],
    'rounded-full overflow-hidden border-2 border-white shadow-soft flex-shrink-0'
  ].join(' ')
})

const statusClasses = computed(() => {
  const baseClasses = 'absolute -bottom-0.5 -right-0.5 rounded-full border-2 border-white'
  
  const sizes = {
    xs: 'w-2 h-2',
    sm: 'w-2.5 h-2.5',
    md: 'w-3 h-3', 
    lg: 'w-3.5 h-3.5',
    xl: 'w-4 h-4'
  }
  
  const statusColors = {
    active: 'bg-green-500',
    pending: 'bg-yellow-500',
    suspended: 'bg-red-500'
  }
  
  return [
    baseClasses,
    sizes[props.size],
    statusColors[props.user?.status] || 'bg-gray-400'
  ].join(' ')
})

const roleClasses = computed(() => {
  const baseClasses = 'absolute -top-0.5 -right-0.5 rounded-full border-2 border-white flex items-center justify-center'
  
  const sizes = {
    xs: 'w-4 h-4',
    sm: 'w-5 h-5',
    md: 'w-6 h-6',
    lg: 'w-7 h-7', 
    xl: 'w-8 h-8'
  }
  
  const roleColors = {
    admin: 'bg-purple-500 text-white',
    auteur: 'bg-gold-500 text-white'
  }
  
  return [
    baseClasses,
    sizes[props.size],
    roleColors[props.user?.role] || 'bg-gray-400 text-white'
  ].join(' ')
})

const roleIcon = computed(() => {
  const icons = {
    admin: 'lucide:crown',
    auteur: 'lucide:chef-hat'
  }
  return icons[props.user?.role] || 'lucide:user'
})

// Methods
const handleImageError = () => {
  imageError.value = true
}
</script>

<style scoped>
/* Animations subtiles */
.avatar-hover {
  transition: all 0.3s ease;
}

.avatar-hover:hover {
  transform: scale(1.05);
  box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}
</style>