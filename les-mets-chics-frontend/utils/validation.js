// Utilitaires de validation pour les formulaires
export const validators = {
  // Validation email
  email: (value) => {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
    if (!value) return 'L\'email est requis'
    if (!emailRegex.test(value)) return 'Format d\'email invalide'
    return null
  },

  // Validation mot de passe
  password: (value) => {
    if (!value) return 'Le mot de passe est requis'
    if (value.length < 8) return 'Le mot de passe doit contenir au moins 8 caractères'
    return null
  },

  // Validation confirmation mot de passe
  passwordConfirmation: (value, originalPassword) => {
    if (!value) return 'La confirmation est requise'
    if (value !== originalPassword) return 'Les mots de passe ne correspondent pas'
    return null
  },

  // Validation nom
  name: (value) => {
    if (!value) return 'Le nom est requis'
    if (value.length < 2) return 'Le nom doit contenir au moins 2 caractères'
    if (value.length > 50) return 'Le nom ne peut pas dépasser 50 caractères'
    return null
  },

  // Validation fichier image
  imageFile: (file) => {
    if (!file) return null // Optionnel
    
    const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif']
    const maxSize = 2 * 1024 * 1024 // 2MB
    
    if (!allowedTypes.includes(file.type)) {
      return 'Format non supporté (JPEG, PNG, JPG, GIF uniquement)'
    }
    
    if (file.size > maxSize) {
      return 'La taille du fichier ne doit pas dépasser 2MB'
    }
    
    return null
  }
}

// Fonction pour valider un objet complet
export const validateForm = (formData, rules) => {
  const errors = {}
  let isValid = true

  for (const [field, value] of Object.entries(formData)) {
    if (rules[field]) {
      const error = rules[field](value, formData)
      if (error) {
        errors[field] = error
        isValid = false
      }
    }
  }

  return { isValid, errors }
}

// Fonction pour nettoyer les données d'entrée
export const sanitizeInput = (input) => {
  if (typeof input !== 'string') return input
  
  return input
    .trim()
    .replace(/[<>]/g, '') // Supprimer les balises HTML basiques
}