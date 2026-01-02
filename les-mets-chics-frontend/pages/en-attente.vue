<template>
  <div class="min-h-screen bg-gradient-to-br from-cream-50 via-white to-bordeaux-50 flex items-center justify-center py-12 px-4">
    <div class="max-w-2xl w-full text-center">
      
      <!-- Animation principale -->
      <div class="mb-12">
        <div class="relative inline-block">
          <!-- Cercles animés -->
          <div class="absolute inset-0 flex items-center justify-center">
            <div class="w-32 h-32 border-4 border-gold-200 rounded-full animate-ping opacity-20"></div>
            <div class="absolute w-24 h-24 border-4 border-bordeaux-200 rounded-full animate-pulse"></div>
          </div>
          
          <!-- Icône centrale -->
          <div class="relative w-20 h-20 bg-gradient-to-br from-gold-500 to-gold-700 rounded-full flex items-center justify-center animate-float shadow-premium">
            <Icon name="lucide:clock" class="text-white text-3xl" />
          </div>
        </div>
      </div>

      <!-- Contenu principal -->
      <div class="space-y-8 animate-fade-in-up">
        <div>
          <h1 class="text-4xl md:text-5xl font-display font-bold text-bordeaux-900 mb-4">
            Compte en Attente
          </h1>
          <p class="text-xl text-charcoal-600 leading-relaxed">
            Votre inscription a été reçue avec succès
          </p>
        </div>

        <!-- Message principal -->
        <div class="bg-white/80 backdrop-blur-sm rounded-3xl shadow-premium p-8 border border-white/50">
          <div class="space-y-6">
            <div class="flex items-center justify-center w-16 h-16 bg-gold-100 rounded-2xl mx-auto">
              <Icon name="lucide:mail-check" class="text-gold-600 text-2xl" />
            </div>
            
            <div>
              <h2 class="text-2xl font-display font-semibold text-bordeaux-900 mb-3">
                Validation en Cours
              </h2>
              <p class="text-charcoal-600 leading-relaxed">
                Votre compte est actuellement en cours de validation par notre équipe. 
                Vous recevrez un email de confirmation dès que votre accès sera activé.
              </p>
            </div>

            <!-- Étapes -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8">
              <div class="text-center">
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-3">
                  <Icon name="lucide:check" class="text-green-600" />
                </div>
                <h3 class="font-semibold text-bordeaux-900 mb-1">Inscription</h3>
                <p class="text-sm text-charcoal-600">Terminée</p>
              </div>
              
              <div class="text-center">
                <div class="w-12 h-12 bg-gold-100 rounded-full flex items-center justify-center mx-auto mb-3 animate-pulse">
                  <Icon name="lucide:clock" class="text-gold-600" />
                </div>
                <h3 class="font-semibold text-bordeaux-900 mb-1">Validation</h3>
                <p class="text-sm text-charcoal-600">En cours</p>
              </div>
              
              <div class="text-center">
                <div class="w-12 h-12 bg-charcoal-100 rounded-full flex items-center justify-center mx-auto mb-3">
                  <Icon name="lucide:sparkles" class="text-charcoal-400" />
                </div>
                <h3 class="font-semibold text-charcoal-600 mb-1">Activation</h3>
                <p class="text-sm text-charcoal-500">En attente</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Informations supplémentaires -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div class="bg-white/60 backdrop-blur-sm rounded-2xl p-6 border border-white/50">
            <div class="flex items-center mb-4">
              <Icon name="lucide:info" class="text-bordeaux-600 mr-3" />
              <h3 class="font-semibold text-bordeaux-900">Délai de validation</h3>
            </div>
            <p class="text-charcoal-600 text-sm">
              La validation prend généralement entre 24 et 48 heures ouvrées.
            </p>
          </div>
          
          <div class="bg-white/60 backdrop-blur-sm rounded-2xl p-6 border border-white/50">
            <div class="flex items-center mb-4">
              <Icon name="lucide:shield-check" class="text-bordeaux-600 mr-3" />
              <h3 class="font-semibold text-bordeaux-900">Sécurité</h3>
            </div>
            <p class="text-charcoal-600 text-sm">
              Cette étape garantit la qualité de notre communauté gastronomique.
            </p>
          </div>
        </div>

        <!-- Actions -->
        <div class="space-y-4">
          <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <NuxtLink 
              to="/"
              class="premium-btn group"
            >
              <Icon name="lucide:home" class="mr-2 group-hover:scale-110 transition-transform" />
              Retour à l'accueil
            </NuxtLink>
            
            <button 
              @click="resendEmail"
              :disabled="emailSent"
              class="outline-btn group"
            >
              <Icon name="lucide:mail" class="mr-2 group-hover:scale-110 transition-transform" />
              {{ emailSent ? 'Email envoyé' : 'Renvoyer l\'email' }}
            </button>
          </div>
          
          <p class="text-sm text-charcoal-500">
            Problème avec votre inscription ? 
            <NuxtLink to="/contact" class="text-bordeaux-600 hover:text-bordeaux-800 font-medium">
              Contactez-nous
            </NuxtLink>
          </p>
        </div>
      </div>

      <!-- Aperçu des avantages -->
      <div class="mt-16 animate-fade-in-up" style="animation-delay: 0.4s">
        <h3 class="text-2xl font-display font-bold text-bordeaux-900 mb-8">
          Ce qui vous attend
        </h3>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
          <div class="text-center p-6 bg-white/60 backdrop-blur-sm rounded-2xl border border-white/50 hover:shadow-md transition-shadow">
            <Icon name="lucide:crown" class="text-gold-600 text-3xl mb-4 mx-auto" />
            <h4 class="font-semibold text-bordeaux-900 mb-2">Recettes Exclusives</h4>
            <p class="text-charcoal-600 text-sm">
              Accès à plus de 500 recettes gastronomiques d'exception
            </p>
          </div>
          
          <div class="text-center p-6 bg-white/60 backdrop-blur-sm rounded-2xl border border-white/50 hover:shadow-md transition-shadow">
            <Icon name="lucide:video" class="text-gold-600 text-3xl mb-4 mx-auto" />
            <h4 class="font-semibold text-bordeaux-900 mb-2">Vidéos HD</h4>
            <p class="text-charcoal-600 text-sm">
              Tutoriels vidéo détaillés par nos chefs partenaires
            </p>
          </div>
          
          <div class="text-center p-6 bg-white/60 backdrop-blur-sm rounded-2xl border border-white/50 hover:shadow-md transition-shadow">
            <Icon name="lucide:users" class="text-gold-600 text-3xl mb-4 mx-auto" />
            <h4 class="font-semibold text-bordeaux-900 mb-2">Communauté</h4>
            <p class="text-charcoal-600 text-sm">
              Échangez avec des passionnés de gastronomie
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
const { success } = useNotifications()

// SEO
useHead({
  title: 'Compte en Attente - Les Mets Chics',
  meta: [
    { name: 'description', content: 'Votre compte Les Mets Chics est en cours de validation.' }
  ]
})

// État
const emailSent = ref(false)

// Actions
const resendEmail = async () => {
  try {
    // Simuler l'envoi d'email
    await new Promise(resolve => setTimeout(resolve, 1000))
    emailSent.value = true
    success('Email de confirmation renvoyé')
    
    // Réactiver le bouton après 30 secondes
    setTimeout(() => {
      emailSent.value = false
    }, 30000)
  } catch (error) {
    console.error('Erreur lors de l\'envoi:', error)
  }
}
</script>

<style scoped>
.premium-btn {
  @apply px-6 py-3 bg-gradient-to-r from-bordeaux-600 to-bordeaux-800 text-white rounded-xl font-semibold transition-all duration-300 hover:shadow-lg hover:scale-105 flex items-center justify-center;
}

.outline-btn {
  @apply px-6 py-3 border-2 border-bordeaux-600 text-bordeaux-600 rounded-xl font-semibold transition-all duration-300 hover:bg-bordeaux-600 hover:text-white disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center;
}

/* Animations */
@keyframes float {
  0%, 100% { transform: translateY(0px) rotate(0deg); }
  50% { transform: translateY(-12px) rotate(3deg); }
}

@keyframes fade-in-up {
  0% {
    opacity: 0;
    transform: translateY(30px);
  }
  100% {
    opacity: 1;
    transform: translateY(0);
  }
}

.animate-float {
  animation: float 4s ease-in-out infinite;
}

.animate-fade-in-up {
  animation: fade-in-up 0.8s ease-out;
}
</style>