<template>
  <Teleport to="body">
    <Transition
      enter-active-class="transition ease-out duration-300"
      enter-from-class="opacity-0"
      enter-to-class="opacity-100"
      leave-active-class="transition ease-in duration-200"
      leave-from-class="opacity-100"
      leave-to-class="opacity-0"
    >
      <div 
        v-if="show"
        class="fixed inset-0 z-50 overflow-y-auto"
        @click="handleBackdropClick"
      >
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-charcoal-900/50 backdrop-blur-sm"></div>
        
        <!-- Modal Container -->
        <div class="flex min-h-full items-center justify-center p-4">
          <Transition
            enter-active-class="transition ease-out duration-300"
            enter-from-class="opacity-0 scale-95"
            enter-to-class="opacity-100 scale-100"
            leave-active-class="transition ease-in duration-200"
            leave-from-class="opacity-100 scale-100"
            leave-to-class="opacity-0 scale-95"
          >
            <div 
              v-if="show"
              class="relative bg-white rounded-2xl shadow-premium"
              :class="modalClasses"
              @click.stop
            >
              <!-- Header -->
              <div v-if="showHeader" class="flex items-center justify-between p-6 border-b border-cream-200">
                <div>
                  <h2 v-if="title" class="text-xl font-display font-bold text-bordeaux-800">
                    {{ title }}
                  </h2>
                  <p v-if="subtitle" class="text-sm text-charcoal-600 mt-1">
                    {{ subtitle }}
                  </p>
                </div>
                
                <button 
                  v-if="closable"
                  @click="$emit('close')"
                  class="p-2 text-charcoal-400 hover:text-charcoal-600 hover:bg-cream-100 rounded-xl transition-all duration-200"
                >
                  <Icon name="lucide:x" class="w-5 h-5" />
                </button>
              </div>
              
              <!-- Body -->
              <div class="p-6" :class="bodyClasses">
                <slot />
              </div>
              
              <!-- Footer -->
              <div v-if="showFooter" class="flex items-center justify-end gap-3 p-6 border-t border-cream-200">
                <slot name="footer">
                  <ui-button 
                    v-if="showCancel"
                    variant="ghost"
                    @click="$emit('cancel')"
                  >
                    {{ cancelText }}
                  </ui-button>
                  
                  <ui-button 
                    v-if="showConfirm"
                    :variant="confirmVariant"
                    :loading="loading"
                    @click="$emit('confirm')"
                  >
                    {{ confirmText }}
                  </ui-button>
                </slot>
              </div>
            </div>
          </Transition>
        </div>
      </div>
    </Transition>
  </Teleport>
</template>

<script setup>
import { computed, onMounted, onUnmounted } from 'vue'

const props = defineProps({
  show: {
    type: Boolean,
    default: false
  },
  title: {
    type: String,
    default: ''
  },
  subtitle: {
    type: String,
    default: ''
  },
  size: {
    type: String,
    default: 'md',
    validator: (value) => ['sm', 'md', 'lg', 'xl', 'full'].includes(value)
  },
  closable: {
    type: Boolean,
    default: true
  },
  closeOnBackdrop: {
    type: Boolean,
    default: true
  },
  showHeader: {
    type: Boolean,
    default: true
  },
  showFooter: {
    type: Boolean,
    default: false
  },
  showCancel: {
    type: Boolean,
    default: true
  },
  showConfirm: {
    type: Boolean,
    default: true
  },
  cancelText: {
    type: String,
    default: 'Annuler'
  },
  confirmText: {
    type: String,
    default: 'Confirmer'
  },
  confirmVariant: {
    type: String,
    default: 'primary'
  },
  loading: {
    type: Boolean,
    default: false
  }
})

const emit = defineEmits(['close', 'cancel', 'confirm'])

const modalClasses = computed(() => {
  const sizes = {
    sm: 'w-full max-w-md',
    md: 'w-full max-w-lg',
    lg: 'w-full max-w-2xl',
    xl: 'w-full max-w-4xl',
    full: 'w-full max-w-7xl mx-4'
  }
  
  return sizes[props.size]
})

const bodyClasses = computed(() => {
  return {
    'pt-0': !props.showHeader,
    'pb-0': props.showFooter
  }
})

const handleBackdropClick = () => {
  if (props.closeOnBackdrop && props.closable) {
    emit('close')
  }
}

const handleEscapeKey = (event) => {
  if (event.key === 'Escape' && props.show && props.closable) {
    emit('close')
  }
}

onMounted(() => {
  document.addEventListener('keydown', handleEscapeKey)
  // Empêcher le scroll du body quand la modale est ouverte
  if (props.show) {
    document.body.style.overflow = 'hidden'
  }
})

onUnmounted(() => {
  document.removeEventListener('keydown', handleEscapeKey)
  document.body.style.overflow = ''
})

watch(() => props.show, (newValue) => {
  if (newValue) {
    document.body.style.overflow = 'hidden'
  } else {
    document.body.style.overflow = ''
  }
})
</script>