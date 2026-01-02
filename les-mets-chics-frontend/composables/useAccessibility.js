export const useAccessibility = () => {
  // Gestion du focus
  const trapFocus = (element) => {
    const focusableElements = element.querySelectorAll(
      'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])'
    )
    
    const firstElement = focusableElements[0]
    const lastElement = focusableElements[focusableElements.length - 1]
    
    const handleTabKey = (e) => {
      if (e.key === 'Tab') {
        if (e.shiftKey) {
          if (document.activeElement === firstElement) {
            lastElement.focus()
            e.preventDefault()
          }
        } else {
          if (document.activeElement === lastElement) {
            firstElement.focus()
            e.preventDefault()
          }
        }
      }
    }
    
    element.addEventListener('keydown', handleTabKey)
    
    // Focus sur le premier élément
    if (firstElement) {
      firstElement.focus()
    }
    
    // Retourner une fonction de nettoyage
    return () => {
      element.removeEventListener('keydown', handleTabKey)
    }
  }

  // Gestion des annonces pour les lecteurs d'écran
  const announce = (message, priority = 'polite') => {
    if (!process.client) return
    
    const announcer = document.createElement('div')
    announcer.setAttribute('aria-live', priority)
    announcer.setAttribute('aria-atomic', 'true')
    announcer.className = 'sr-only'
    announcer.textContent = message
    
    document.body.appendChild(announcer)
    
    // Supprimer après annonce
    setTimeout(() => {
      document.body.removeChild(announcer)
    }, 1000)
  }

  // Vérification du contraste
  const hasGoodContrast = (foreground, background) => {
    // Fonction simplifiée - en production, utiliser une librairie dédiée
    const getLuminance = (color) => {
      // Conversion simplifiée RGB vers luminance
      const rgb = color.match(/\d+/g)
      if (!rgb) return 0
      
      const [r, g, b] = rgb.map(x => {
        x = parseInt(x) / 255
        return x <= 0.03928 ? x / 12.92 : Math.pow((x + 0.055) / 1.055, 2.4)
      })
      
      return 0.2126 * r + 0.7152 * g + 0.0722 * b
    }
    
    const l1 = getLuminance(foreground)
    const l2 = getLuminance(background)
    const ratio = (Math.max(l1, l2) + 0.05) / (Math.min(l1, l2) + 0.05)
    
    return ratio >= 4.5 // WCAG AA standard
  }

  // Gestion des préférences utilisateur
  const prefersReducedMotion = () => {
    if (!process.client) return false
    return window.matchMedia('(prefers-reduced-motion: reduce)').matches
  }

  const prefersDarkMode = () => {
    if (!process.client) return false
    return window.matchMedia('(prefers-color-scheme: dark)').matches
  }

  const prefersHighContrast = () => {
    if (!process.client) return false
    return window.matchMedia('(prefers-contrast: high)').matches
  }

  // Génération d'IDs uniques pour l'accessibilité
  const generateId = (prefix = 'element') => {
    return `${prefix}-${Math.random().toString(36).substr(2, 9)}`
  }

  // Gestion des raccourcis clavier
  const addKeyboardShortcut = (key, callback, options = {}) => {
    const { ctrl = false, alt = false, shift = false } = options
    
    const handleKeydown = (e) => {
      if (
        e.key.toLowerCase() === key.toLowerCase() &&
        e.ctrlKey === ctrl &&
        e.altKey === alt &&
        e.shiftKey === shift
      ) {
        e.preventDefault()
        callback(e)
      }
    }
    
    if (process.client) {
      document.addEventListener('keydown', handleKeydown)
      
      // Retourner fonction de nettoyage
      return () => {
        document.removeEventListener('keydown', handleKeydown)
      }
    }
    
    return () => {}
  }

  return {
    // Focus management
    trapFocus,
    
    // Screen reader
    announce,
    
    // Contrast
    hasGoodContrast,
    
    // User preferences
    prefersReducedMotion,
    prefersDarkMode,
    prefersHighContrast,
    
    // Utilities
    generateId,
    addKeyboardShortcut
  }
}