<template>
  <div 
    v-if="show"
    class="rounded-xl border p-4 transition-all duration-300"
    :class="containerClasses"
  >
    <div class="flex items-start gap-3">
      <!-- Icon -->
      <div class="flex-shrink-0">
        <Icon 
          :name="iconName" 
          class="w-5 h-5"
          :class="iconClasses"
        />
      </div>
      
      <!-- Content -->
      <div class="flex-1 min-w-0">
        <h3 v-if="title" class="font-semibold mb-1" :class="titleClasses">
          {{ title }}
        </h3>
        
        <div class="text-sm" :class="messageClasses">
          <slot>
            {{ message }}
          </slot>
        </div>
        
        <!-- Actions -->
        <div v-if="showRetry || showClose" class="flex gap-2 mt-3">
          <ui-button 
            v-if="showRetry"
            variant="outline"
            size="sm"
            @click="$emit('retry')"
          >
            <Icon name="lucide:refresh-cw" class="w-4 h-4" />
            Réessayer
          </ui-button>
          
          <ui-button 
            v-if="showClose"
            variant="ghost"
            size="sm"
            @click="$emit('close')"
          >
            <Icon name="lucide:x" class="w-4 h-4" />
            Fermer
          </ui-button>
        </div>
      </div>
      
      <!-- Close button (top right) -->
      <button 
        v-if="dismissible"
        @click="$emit('close')"
        class="flex-shrink-0 p-1 rounded-lg transition-colors duration-200"
        :class="closeButtonClasses"
      >
        <Icon name="lucide:x" class="w-4 h-4" />
      </button>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  type: {
    type: String,
    default: 'error',
    validator: (value) => ['error', 'warning', 'info'].includes(value)
  },
  title: {
    type: String,
    default: ''
  },
  message: {
    type: String,
    default: ''
  },
  show: {
    type: Boolean,
    default: true
  },
  dismissible: {
    type: Boolean,
    default: true
  },
  showRetry: {
    type: Boolean,
    default: false
  },
  showClose: {
    type: Boolean,
    default: false
  }
})

defineEmits(['close', 'retry'])

const iconName = computed(() => {
  const icons = {
    error: 'lucide:alert-circle',
    warning: 'lucide:alert-triangle',
    info: 'lucide:info'
  }
  return icons[props.type]
})

const containerClasses = computed(() => {
  const variants = {
    error: 'bg-red-50 border-red-200',
    warning: 'bg-yellow-50 border-yellow-200',
    info: 'bg-blue-50 border-blue-200'
  }
  return variants[props.type]
})

const iconClasses = computed(() => {
  const variants = {
    error: 'text-red-500',
    warning: 'text-yellow-500',
    info: 'text-blue-500'
  }
  return variants[props.type]
})

const titleClasses = computed(() => {
  const variants = {
    error: 'text-red-800',
    warning: 'text-yellow-800',
    info: 'text-blue-800'
  }
  return variants[props.type]
})

const messageClasses = computed(() => {
  const variants = {
    error: 'text-red-700',
    warning: 'text-yellow-700',
    info: 'text-blue-700'
  }
  return variants[props.type]
})

const closeButtonClasses = computed(() => {
  const variants = {
    error: 'text-red-400 hover:text-red-600 hover:bg-red-100',
    warning: 'text-yellow-400 hover:text-yellow-600 hover:bg-yellow-100',
    info: 'text-blue-400 hover:text-blue-600 hover:bg-blue-100'
  }
  return variants[props.type]
})
</script>