<template>
  <NuxtLayout name="default">
    <div class="max-w-4xl mx-auto px-4 py-16">
      <div class="text-center mb-12">
        <div class="w-20 h-20 bg-gradient-to-br from-primary-500 to-accent-500 rounded-full flex items-center justify-center mx-auto mb-6">
          <Icon name="mdi:chef-hat" class="text-white text-4xl" />
        </div>
        <h1 class="text-5xl font-serif font-bold text-primary-900 mb-4">À Propos</h1>
        <p class="text-xl text-primary-600">L'histoire de Les Mets Chics</p>
      </div>

      <div class="bg-white rounded-3xl shadow-2xl p-8 md:p-12 space-y-8">
        <section>
          <h2 class="text-3xl font-serif font-bold text-primary-900 mb-4">Notre Mission</h2>
          <p class="text-lg text-primary-700 leading-relaxed">
            Les Mets Chics est né de la passion pour la gastronomie française et internationale. 
            Notre mission est de rendre accessible l'art culinaire à tous, en partageant des recettes 
            raffinées et authentiques, accompagnées de vidéos détaillées et de conseils d'experts.
          </p>
        </section>

        <section>
          <h2 class="text-3xl font-serif font-bold text-primary-900 mb-4">Notre Communauté</h2>
          <p class="text-lg text-primary-700 leading-relaxed mb-6">
            Rejoignez une communauté de passionnés de cuisine qui partagent leurs créations, 
            échangent des astuces et découvrent de nouvelles saveurs chaque jour.
          </p>
          <div class="grid md:grid-cols-3 gap-6">
            <div class="text-center p-6 bg-primary-50 rounded-2xl">
              <Icon name="mdi:book-open-variant" class="text-4xl text-primary-600 mb-3 mx-auto" />
              <h3 class="font-semibold text-primary-900 mb-2">Recettes Variées</h3>
              <p class="text-sm text-primary-600">Des centaines de recettes de tous horizons</p>
            </div>
            <div class="text-center p-6 bg-accent-50 rounded-2xl">
              <Icon name="mdi:video" class="text-4xl text-accent-600 mb-3 mx-auto" />
              <h3 class="font-semibold text-primary-900 mb-2">Vidéos Détaillées</h3>
              <p class="text-sm text-primary-600">Suivez chaque étape en vidéo</p>
            </div>
            <div class="text-center p-6 bg-primary-50 rounded-2xl">
              <Icon name="mdi:account-group" class="text-4xl text-primary-600 mb-3 mx-auto" />
              <h3 class="font-semibold text-primary-900 mb-2">Communauté Active</h3>
              <p class="text-sm text-primary-600">Échangez avec d'autres passionnés</p>
            </div>
          </div>
        </section>

        <section>
          <h2 class="text-3xl font-serif font-bold text-primary-900 mb-4">Nos Valeurs</h2>
          <ul class="space-y-3">
            <li class="flex items-start">
              <Icon name="mdi:check-circle" class="text-accent-500 mr-3 mt-1 flex-shrink-0" />
              <span class="text-lg text-primary-700"><strong>Qualité :</strong> Chaque recette est testée et approuvée</span>
            </li>
            <li class="flex items-start">
              <Icon name="mdi:check-circle" class="text-accent-500 mr-3 mt-1 flex-shrink-0" />
              <span class="text-lg text-primary-700"><strong>Authenticité :</strong> Des recettes traditionnelles et modernes</span>
            </li>
            <li class="flex items-start">
              <Icon name="mdi:check-circle" class="text-accent-500 mr-3 mt-1 flex-shrink-0" />
              <span class="text-lg text-primary-700"><strong>Partage :</strong> Une communauté bienveillante et passionnée</span>
            </li>
            <li class="flex items-start">
              <Icon name="mdi:check-circle" class="text-accent-500 mr-3 mt-1 flex-shrink-0" />
              <span class="text-lg text-primary-700"><strong>Innovation :</strong> Des outils modernes pour cuisiner mieux</span>
            </li>
          </ul>
        </section>

        <section class="bg-gradient-to-r from-primary-500 to-accent-500 rounded-2xl p-8 text-white text-center">
          <h2 class="text-3xl font-serif font-bold mb-4">Rejoignez-nous !</h2>
          <p class="text-lg mb-6 text-primary-50">
            Inscrivez-vous gratuitement et commencez votre aventure culinaire dès aujourd'hui.
          </p>
          <NuxtLink to="/inscription" class="inline-block bg-white text-primary-900 px-8 py-3 rounded-full font-semibold hover:bg-primary-50 transition-colors">
            Créer mon compte
          </NuxtLink>
        </section>
      </div>
    </div>
  </NuxtLayout>
</template>

<script setup>
useHead({
  title: 'À Propos - Les Mets Chics',
  meta: [
    { name: 'description', content: 'Découvrez l\'histoire et la mission de Les Mets Chics, votre plateforme de recettes gastronomiques.' }
  ]
})
</script>
