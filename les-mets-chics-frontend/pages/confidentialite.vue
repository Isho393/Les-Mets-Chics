<template>
  <NuxtLayout name="default">
    <div class="max-w-4xl mx-auto px-4 py-12">
      <div class="bg-white rounded-3xl shadow-2xl p-8 md:p-12">
        <h1 class="text-4xl font-serif font-bold text-primary-900 mb-8">Politique de confidentialité</h1>
        
        <div class="prose max-w-none space-y-6 text-primary-700">
          <section>
            <h2 class="text-2xl font-serif font-bold text-primary-900 mb-4">Collecte des données</h2>
            <p>
              Nous collectons les données suivantes lors de votre inscription et utilisation du site :
            </p>
            <ul class="list-disc pl-6 space-y-2">
              <li>Nom et prénom</li>
              <li>Adresse email</li>
              <li>Mot de passe (crypté)</li>
              <li>Recettes, commentaires et likes que vous publiez</li>
            </ul>
          </section>

          <section>
            <h2 class="text-2xl font-serif font-bold text-primary-900 mb-4">Utilisation des données</h2>
            <p>Vos données personnelles sont utilisées pour :</p>
            <ul class="list-disc pl-6 space-y-2">
              <li>Gérer votre compte utilisateur</li>
              <li>Vous permettre de publier et partager des recettes</li>
              <li>Améliorer nos services</li>
              <li>Vous envoyer des notifications (avec votre consentement)</li>
            </ul>
          </section>

          <section>
            <h2 class="text-2xl font-serif font-bold text-primary-900 mb-4">Protection des données</h2>
            <p>
              Nous mettons en œuvre toutes les mesures techniques et organisationnelles nécessaires pour assurer 
              la sécurité de vos données personnelles et empêcher leur altération, leur perte ou l'accès non autorisé.
            </p>
          </section>

          <section>
            <h2 class="text-2xl font-serif font-bold text-primary-900 mb-4">Vos droits</h2>
            <p>Conformément au RGPD, vous disposez des droits suivants :</p>
            <ul class="list-disc pl-6 space-y-2">
              <li>Droit d'accès à vos données</li>
              <li>Droit de rectification</li>
              <li>Droit à l'effacement</li>
              <li>Droit à la portabilité</li>
              <li>Droit d'opposition</li>
            </ul>
            <p class="mt-4">
              Pour exercer ces droits, contactez-nous à : contact@lesmets-chics.fr
            </p>
          </section>

          <section>
            <h2 class="text-2xl font-serif font-bold text-primary-900 mb-4">Cookies</h2>
            <p>
              Nous utilisons des cookies essentiels pour le fonctionnement du site (authentification, préférences). 
              Aucun cookie de tracking ou publicitaire n'est utilisé.
            </p>
          </section>
        </div>
      </div>
    </div>
  </NuxtLayout>
</template>

<script setup>
useHead({
  title: 'Politique de confidentialité - Les Mets Chics',
  meta: [
    { name: 'description', content: 'Politique de confidentialité et protection des données personnelles' }
  ]
})
</script>
