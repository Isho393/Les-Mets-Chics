<template>
  <NuxtLayout name="default">
    <div class="max-w-4xl mx-auto px-4 py-12">
      <div class="bg-white rounded-3xl shadow-2xl p-8 md:p-12">
        <h1 class="text-4xl font-serif font-bold text-primary-900 mb-8">Contactez-nous</h1>
        
        <div class="grid md:grid-cols-2 gap-8 mb-8">
          <div>
            <h2 class="text-2xl font-serif font-bold text-primary-900 mb-4">Informations</h2>
            <div class="space-y-4 text-primary-700">
              <div class="flex items-start">
                <Icon name="mdi:email" class="text-accent-500 text-2xl mr-3 mt-1" />
                <div>
                  <p class="font-semibold">Email</p>
                  <a href="mailto:contact@lesmets-chics.fr" class="text-primary-600 hover:text-accent-500">
                    contact@lesmets-chics.fr
                  </a>
                </div>
              </div>
              
              <div class="flex items-start">
                <Icon name="mdi:map-marker" class="text-accent-500 text-2xl mr-3 mt-1" />
                <div>
                  <p class="font-semibold">Adresse</p>
                  <p class="text-primary-600">[Adresse à compléter]</p>
                </div>
              </div>
              
              <div class="flex items-start">
                <Icon name="mdi:phone" class="text-accent-500 text-2xl mr-3 mt-1" />
                <div>
                  <p class="font-semibold">Téléphone</p>
                  <p class="text-primary-600">[Téléphone à compléter]</p>
                </div>
              </div>
            </div>
          </div>

          <div>
            <h2 class="text-2xl font-serif font-bold text-primary-900 mb-4">Envoyez-nous un message</h2>
            <form @submit.prevent="submitForm" class="space-y-4">
              <div>
                <label class="block text-sm font-medium text-primary-700 mb-2">Nom</label>
                <input 
                  v-model="form.name"
                  type="text" 
                  required
                  class="w-full px-4 py-2 border-2 border-primary-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                />
              </div>
              
              <div>
                <label class="block text-sm font-medium text-primary-700 mb-2">Email</label>
                <input 
                  v-model="form.email"
                  type="email" 
                  required
                  class="w-full px-4 py-2 border-2 border-primary-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                />
              </div>
              
              <div>
                <label class="block text-sm font-medium text-primary-700 mb-2">Message</label>
                <textarea 
                  v-model="form.message"
                  required
                  rows="5"
                  class="w-full px-4 py-2 border-2 border-primary-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent resize-none"
                ></textarea>
              </div>
              
              <button 
                type="submit" 
                class="w-full bg-gradient-to-r from-primary-500 to-accent-500 text-white px-6 py-3 rounded-full font-medium hover:shadow-lg transition-all"
              >
                Envoyer le message
              </button>
              
              <p v-if="submitted" class="text-green-600 text-center">
                Message envoyé avec succès !
              </p>
            </form>
          </div>
        </div>
      </div>
    </div>
  </NuxtLayout>
</template>

<script setup>
useHead({
  title: 'Contact - Les Mets Chics',
  meta: [
    { name: 'description', content: 'Contactez l\'équipe Les Mets Chics' }
  ]
})

const form = ref({
  name: '',
  email: '',
  message: ''
})

const submitted = ref(false)

const submitForm = () => {
  // TODO: Implémenter l'envoi du formulaire vers le backend
  console.log('Formulaire soumis:', form.value)
  submitted.value = true
  
  setTimeout(() => {
    form.value = { name: '', email: '', message: '' }
    submitted.value = false
  }, 3000)
}
</script>
