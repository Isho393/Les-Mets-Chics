export const usePerformance = () => {
  // Lazy loading des images
  const lazyLoadImage = (imgElement, src, placeholder = '/placeholder.jpg') => {
    if (!process.client) return
    
    // Utiliser Intersection Observer pour le lazy loading
    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          const img = entry.target
          img.src = src
          img.classList.remove('loading')
          observer.unobserve(img)
        }
      })
    }, {
      rootMargin: '50px'
    })
    
    imgElement.src = placeholder
    imgElement.classList.add('loading')
    observer.observe(imgElement)
    
    return observer
  }

  // Debounce pour les événements fréquents
  const debounce = (func, wait) => {
    let timeout
    return function executedFunction(...args) {
      const later = () => {
        clearTimeout(timeout)
        func(...args)
      }
      clearTimeout(timeout)
      timeout = setTimeout(later, wait)
    }
  }

  // Throttle pour les événements de scroll
  const throttle = (func, limit) => {
    let inThrottle
    return function(...args) {
      if (!inThrottle) {
        func.apply(this, args)
        inThrottle = true
        setTimeout(() => inThrottle = false, limit)
      }
    }
  }

  // Préchargement des ressources critiques
  const preloadResource = (href, as = 'fetch', crossorigin = 'anonymous') => {
    if (!process.client) return
    
    const link = document.createElement('link')
    link.rel = 'preload'
    link.href = href
    link.as = as
    if (crossorigin) link.crossOrigin = crossorigin
    
    document.head.appendChild(link)
    
    return link
  }

  // Mesure des Core Web Vitals
  const measureWebVitals = () => {
    if (!process.client) return
    
    // Largest Contentful Paint (LCP)
    const observeLCP = () => {
      new PerformanceObserver((entryList) => {
        const entries = entryList.getEntries()
        const lastEntry = entries[entries.length - 1]
        console.log('LCP:', lastEntry.startTime)
      }).observe({ entryTypes: ['largest-contentful-paint'] })
    }

    // First Input Delay (FID)
    const observeFID = () => {
      new PerformanceObserver((entryList) => {
        const entries = entryList.getEntries()
        entries.forEach(entry => {
          console.log('FID:', entry.processingStart - entry.startTime)
        })
      }).observe({ entryTypes: ['first-input'] })
    }

    // Cumulative Layout Shift (CLS)
    const observeCLS = () => {
      let clsValue = 0
      new PerformanceObserver((entryList) => {
        const entries = entryList.getEntries()
        entries.forEach(entry => {
          if (!entry.hadRecentInput) {
            clsValue += entry.value
          }
        })
        console.log('CLS:', clsValue)
      }).observe({ entryTypes: ['layout-shift'] })
    }

    observeLCP()
    observeFID()
    observeCLS()
  }

  // Optimisation des re-rendus
  const memoize = (fn) => {
    const cache = new Map()
    return (...args) => {
      const key = JSON.stringify(args)
      if (cache.has(key)) {
        return cache.get(key)
      }
      const result = fn(...args)
      cache.set(key, result)
      return result
    }
  }

  // Gestion de la mémoire
  const cleanupObservers = (observers = []) => {
    observers.forEach(observer => {
      if (observer && typeof observer.disconnect === 'function') {
        observer.disconnect()
      }
    })
  }

  // Détection de la vitesse de connexion
  const getConnectionSpeed = () => {
    if (!process.client || !navigator.connection) return 'unknown'
    
    const connection = navigator.connection
    return {
      effectiveType: connection.effectiveType,
      downlink: connection.downlink,
      rtt: connection.rtt,
      saveData: connection.saveData
    }
  }

  // Adaptation selon la connexion
  const shouldLoadHighQuality = () => {
    const connection = getConnectionSpeed()
    if (connection === 'unknown') return true
    
    return connection.effectiveType === '4g' && !connection.saveData
  }

  return {
    // Image optimization
    lazyLoadImage,
    
    // Event optimization
    debounce,
    throttle,
    
    // Resource loading
    preloadResource,
    
    // Performance monitoring
    measureWebVitals,
    
    // Optimization utilities
    memoize,
    cleanupObservers,
    
    // Network awareness
    getConnectionSpeed,
    shouldLoadHighQuality
  }
}