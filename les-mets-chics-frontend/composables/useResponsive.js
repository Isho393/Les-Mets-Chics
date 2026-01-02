export const useResponsive = () => {
  const windowWidth = ref(0)
  const windowHeight = ref(0)

  // Breakpoints Tailwind
  const breakpoints = {
    sm: 640,
    md: 768,
    lg: 1024,
    xl: 1280,
    '2xl': 1536
  }

  // États réactifs pour les breakpoints
  const isMobile = computed(() => windowWidth.value < breakpoints.md)
  const isTablet = computed(() => windowWidth.value >= breakpoints.md && windowWidth.value < breakpoints.lg)
  const isDesktop = computed(() => windowWidth.value >= breakpoints.lg)
  const isLargeScreen = computed(() => windowWidth.value >= breakpoints.xl)

  // Détection du type d'appareil
  const isTouchDevice = computed(() => {
    if (process.client) {
      return 'ontouchstart' in window || navigator.maxTouchPoints > 0
    }
    return false
  })

  // Orientation
  const isPortrait = computed(() => windowHeight.value > windowWidth.value)
  const isLandscape = computed(() => windowWidth.value > windowHeight.value)

  // Mise à jour des dimensions
  const updateDimensions = () => {
    if (process.client) {
      windowWidth.value = window.innerWidth
      windowHeight.value = window.innerHeight
    }
  }

  // Initialisation côté client
  onMounted(() => {
    updateDimensions()
    window.addEventListener('resize', updateDimensions)
    window.addEventListener('orientationchange', () => {
      setTimeout(updateDimensions, 100) // Délai pour l'orientation
    })
  })

  onUnmounted(() => {
    if (process.client) {
      window.removeEventListener('resize', updateDimensions)
      window.removeEventListener('orientationchange', updateDimensions)
    }
  })

  // Utilitaires
  const getColumnsCount = (sm = 1, md = 2, lg = 3, xl = 4) => {
    if (windowWidth.value >= breakpoints.xl) return xl
    if (windowWidth.value >= breakpoints.lg) return lg
    if (windowWidth.value >= breakpoints.md) return md
    return sm
  }

  const getContainerPadding = () => {
    if (isMobile.value) return 'px-4'
    if (isTablet.value) return 'px-6'
    return 'px-8'
  }

  return {
    // Dimensions
    windowWidth: readonly(windowWidth),
    windowHeight: readonly(windowHeight),
    
    // Breakpoints
    isMobile,
    isTablet,
    isDesktop,
    isLargeScreen,
    
    // Device
    isTouchDevice,
    
    // Orientation
    isPortrait,
    isLandscape,
    
    // Utilitaires
    getColumnsCount,
    getContainerPadding,
    updateDimensions
  }
}