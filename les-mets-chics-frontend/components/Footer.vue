<template>
  <footer class="bg-primary-900 text-white mt-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
      <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
        <!-- About -->
        <div class="col-span-1 md:col-span-2">
          <div class="flex items-center space-x-3 mb-4">
            <div class="w-10 h-10 bg-gradient-to-br from-primary-500 to-accent-500 rounded-full flex items-center justify-center">
              <Icon name="mdi:chef-hat" class="text-white text-xl" />
            </div>
            <h3 class="text-xl font-serif font-bold">Les Mets Chics</h3>
          </div>
          <p class="text-primary-200 mb-4">
            Découvrez l'art de la gastronomie française à travers nos recettes raffinées et authentiques.
          </p>
          <div class="flex space-x-4">
            <a href="#" class="social-link">
              <Icon name="mdi:facebook" />
            </a>
            <a href="#" class="social-link">
              <Icon name="mdi:instagram" />
            </a>
            <a href="#" class="social-link">
              <Icon name="mdi:twitter" />
            </a>
            <a href="#" class="social-link">
              <Icon name="mdi:youtube" />
            </a>
          </div>
        </div>

        <!-- Quick Links -->
        <div>
          <h4 class="font-semibold mb-4">Navigation</h4>
          <ul class="space-y-2">
            <li><NuxtLink to="/" class="footer-link">Accueil</NuxtLink></li>
            <li><NuxtLink to="/categories" class="footer-link">Catégories</NuxtLink></li>
            <li><NuxtLink to="/recettes" class="footer-link">Recettes</NuxtLink></li>
            <li><NuxtLink to="/a-propos" class="footer-link">À propos</NuxtLink></li>
          </ul>
        </div>

        <!-- Legal -->
        <div>
          <h4 class="font-semibold mb-4">Légal</h4>
          <ul class="space-y-2">
            <li><NuxtLink to="/mentions-legales" class="footer-link">Mentions légales</NuxtLink></li>
            <li><NuxtLink to="/confidentialite" class="footer-link">Confidentialité</NuxtLink></li>
            <li><NuxtLink to="/cgv" class="footer-link">CGV</NuxtLink></li>
            <li><NuxtLink to="/contact" class="footer-link">Contact</NuxtLink></li>
          </ul>
        </div>
      </div>

      <div class="border-t border-primary-800 mt-8 pt-8 text-center text-primary-300">
        <p>&copy; {{ new Date().getFullYear() }} Les Mets Chics. Tous droits réservés.</p>
      </div>
    </div>
  </footer>
</template>

<style scoped>
.footer-link {
  @apply text-primary-200 hover:text-white transition-colors;
}

.social-link {
  @apply w-10 h-10 bg-primary-800 hover:bg-primary-700 rounded-full flex items-center justify-center text-xl transition-colors;
}
</style>
