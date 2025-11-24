<template>
  <NuxtLayout name="default">
    <div class="max-w-4xl mx-auto px-4 py-12">
      <div class="bg-white rounded-3xl shadow-2xl p-8 md:p-12">
        <h1 class="text-4xl font-serif font-bold text-primary-900 mb-8">Mentions légales</h1>
        
        <div class="prose max-w-none space-y-6 text-primary-700">
          <section>
            <h2 class="text-2xl font-serif font-bold text-primary-900 mb-4">Éditeur du site</h2>
            <p>
              <strong>Nom de l'entreprise :</strong> Les Mets Chics<br>
              <strong>Forme juridique :</strong> [À compléter]<br>
              <strong>Adresse :</strong> [À compléter]<br>
              <strong>Email :</strong> contact@lesmets-chics.fr<br>
              <strong>Téléphone :</strong> [À compléter]
            </p>
          </section>

          <section>
            <h2 class="text-2xl font-serif font-bold text-primary-900 mb-4">Directeur de la publication</h2>
            <p>[Nom du directeur de publication]</p>
          </section>

          <section>
            <h2 class="text-2xl font-serif font-bold text-primary-900 mb-4">Hébergement</h2>
            <p>
              <strong>Hébergeur :</strong> [Nom de l'hébergeur]<br>
              <strong>Adresse :</strong> [Adresse de l'hébergeur]<br>
              <strong>Téléphone :</strong> [Téléphone de l'hébergeur]
            </p>
          </section>

          <section>
            <h2 class="text-2xl font-serif font-bold text-primary-900 mb-4">Propriété intellectuelle</h2>
            <p>
              L'ensemble du contenu de ce site (textes, images, vidéos, recettes) est la propriété exclusive de Les Mets Chics 
              ou de ses partenaires. Toute reproduction, même partielle, est interdite sans autorisation préalable.
            </p>
          </section>

          <section>
            <h2 class="text-2xl font-serif font-bold text-primary-900 mb-4">Données personnelles</h2>
            <p>
              Conformément au RGPD, vous disposez d'un droit d'accès, de rectification et de suppression de vos données personnelles. 
              Pour exercer ces droits, contactez-nous à : contact@lesmets-chics.fr
            </p>
          </section>
        </div>
      </div>
    </div>
  </NuxtLayout>
</template>

<script setup>
useHead({
  title: 'Mentions légales - Les Mets Chics',
  meta: [
    { name: 'description', content: 'Mentions légales du site Les Mets Chics' }
  ]
})
</script>
