<template>
  <NuxtLayout name="default">
    <div class="max-w-4xl mx-auto px-4 py-12">
      <div class="bg-white rounded-3xl shadow-2xl p-8 md:p-12">
        <h1 class="text-4xl font-serif font-bold text-primary-900 mb-8">Conditions générales d'utilisation</h1>
        
        <div class="prose max-w-none space-y-6 text-primary-700">
          <section>
            <h2 class="text-2xl font-serif font-bold text-primary-900 mb-4">Objet</h2>
            <p>
              Les présentes conditions générales d'utilisation (CGU) régissent l'utilisation du site Les Mets Chics 
              et de ses services associés.
            </p>
          </section>

          <section>
            <h2 class="text-2xl font-serif font-bold text-primary-900 mb-4">Accès au site</h2>
            <p>
              L'accès au site est gratuit. Certaines fonctionnalités (publication de recettes, commentaires, likes) 
              nécessitent la création d'un compte utilisateur.
            </p>
          </section>

          <section>
            <h2 class="text-2xl font-serif font-bold text-primary-900 mb-4">Compte utilisateur</h2>
            <p>Pour créer un compte, vous devez :</p>
            <ul class="list-disc pl-6 space-y-2">
              <li>Fournir des informations exactes et à jour</li>
              <li>Être âgé d'au moins 13 ans</li>
              <li>Maintenir la confidentialité de votre mot de passe</li>
              <li>Nous informer de toute utilisation non autorisée de votre compte</li>
            </ul>
          </section>

          <section>
            <h2 class="text-2xl font-serif font-bold text-primary-900 mb-4">Contenu utilisateur</h2>
            <p>En publiant du contenu (recettes, commentaires, images), vous :</p>
            <ul class="list-disc pl-6 space-y-2">
              <li>Garantissez en être l'auteur ou avoir les droits nécessaires</li>
              <li>Accordez à Les Mets Chics une licence d'utilisation non exclusive</li>
              <li>Vous engagez à ne pas publier de contenu illégal, offensant ou inapproprié</li>
            </ul>
          </section>

          <section>
            <h2 class="text-2xl font-serif font-bold text-primary-900 mb-4">Modération</h2>
            <p>
              Nous nous réservons le droit de modérer, modifier ou supprimer tout contenu qui ne respecterait pas 
              les présentes CGU ou qui serait contraire à la loi.
            </p>
          </section>

          <section>
            <h2 class="text-2xl font-serif font-bold text-primary-900 mb-4">Responsabilité</h2>
            <p>
              Les recettes publiées sur le site sont fournies à titre informatif. Nous ne pouvons être tenus responsables 
              des résultats obtenus ou des allergies alimentaires. Vérifiez toujours les ingrédients avant de cuisiner.
            </p>
          </section>

          <section>
            <h2 class="text-2xl font-serif font-bold text-primary-900 mb-4">Modification des CGU</h2>
            <p>
              Nous nous réservons le droit de modifier les présentes CGU à tout moment. Les utilisateurs seront informés 
              des modifications importantes.
            </p>
          </section>
        </div>
      </div>
    </div>
  </NuxtLayout>
</template>

<script setup>
useHead({
  title: 'Conditions générales d\'utilisation - Les Mets Chics',
  meta: [
    { name: 'description', content: 'Conditions générales d\'utilisation du site Les Mets Chics' }
  ]
})
</script>
