<?php

namespace Database\Seeders;

use App\Models\Recipe;
use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Seeder;

class RecipeSeeder extends Seeder
{
    public function run(): void
    {
        $author1 = User::where('email', 'pierre@lesmets-chics.com')->first();
        $author2 = User::where('email', 'marie@lesmets-chics.com')->first();
        
        $francaise = Category::where('slug', 'cuisine-francaise')->first();
        $japonaise = Category::where('slug', 'cuisine-japonaise')->first();
        $patisserie = Category::where('slug', 'patisserie')->first();

        // Recipe 1: Coq au Vin
        Recipe::create([
            'user_id' => $author1->id,
            'category_id' => $francaise->id,
            'title' => 'Coq au Vin Traditionnel',
            'slug' => 'coq-au-vin-traditionnel',
            'description' => 'Un grand classique de la cuisine bourguignonne. Ce plat mijoté révèle toute la richesse des saveurs françaises avec son poulet fondant mariné dans un vin rouge corsé.',
            'main_image_url' => 'https://images.unsplash.com/photo-1598103442097-8b74394b95c6',
            'video_url' => null,
            'prep_time' => 30,
            'cook_time' => 120,
            'servings' => 6,
            'ingredients' => [
                ['quantite' => '1', 'nom' => 'poulet fermier découpé'],
                ['quantite' => '200g', 'nom' => 'lardons fumés'],
                ['quantite' => '20', 'nom' => 'petits oignons grelots'],
                ['quantite' => '250g', 'nom' => 'champignons de Paris'],
                ['quantite' => '75cl', 'nom' => 'vin rouge de Bourgogne'],
                ['quantite' => '2', 'nom' => 'carottes'],
                ['quantite' => '3', 'nom' => 'gousses d\'ail'],
                ['quantite' => '1', 'nom' => 'bouquet garni'],
                ['quantite' => '50g', 'nom' => 'beurre'],
                ['quantite' => '2 cuillères à soupe', 'nom' => 'farine'],
            ],
            'steps' => [
                ['etape' => 1, 'description' => 'Faire mariner le poulet dans le vin rouge avec les carottes, l\'ail et le bouquet garni pendant 12 heures.'],
                ['etape' => 2, 'description' => 'Égoutter le poulet et le faire dorer dans une cocotte avec du beurre.'],
                ['etape' => 3, 'description' => 'Ajouter les lardons et les faire revenir.'],
                ['etape' => 4, 'description' => 'Saupoudrer de farine et mélanger.'],
                ['etape' => 5, 'description' => 'Verser la marinade filtrée et porter à ébullition.'],
                ['etape' => 6, 'description' => 'Couvrir et laisser mijoter 2 heures à feu doux.'],
                ['etape' => 7, 'description' => 'Ajouter les champignons et les oignons grelots 30 minutes avant la fin.'],
                ['etape' => 8, 'description' => 'Servir bien chaud avec des pommes de terre vapeur.'],
            ],
            'status' => 'published',
        ]);

        // Recipe 2: Ramen Authentique
        Recipe::create([
            'user_id' => $author2->id,
            'category_id' => $japonaise->id,
            'title' => 'Ramen Tonkotsu Maison',
            'slug' => 'ramen-tonkotsu-maison',
            'description' => 'Le ramen ultime avec son bouillon crémeux mijoté pendant des heures. Une explosion de saveurs umami qui vous transportera directement à Tokyo.',
            'main_image_url' => 'https://images.unsplash.com/photo-1569718212165-3a8278d5f624',
            'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'prep_time' => 45,
            'cook_time' => 480,
            'servings' => 4,
            'ingredients' => [
                ['quantite' => '1kg', 'nom' => 'os de porc'],
                ['quantite' => '500g', 'nom' => 'poitrine de porc'],
                ['quantite' => '4 portions', 'nom' => 'nouilles ramen fraîches'],
                ['quantite' => '4', 'nom' => 'œufs marinés'],
                ['quantite' => '2', 'nom' => 'oignons verts'],
                ['quantite' => '100g', 'nom' => 'pousses de bambou'],
                ['quantite' => '4 feuilles', 'nom' => 'nori'],
                ['quantite' => '2 cuillères à soupe', 'nom' => 'miso blanc'],
                ['quantite' => '3 cuillères à soupe', 'nom' => 'sauce soja'],
                ['quantite' => '1 cuillère à soupe', 'nom' => 'huile de sésame'],
            ],
            'steps' => [
                ['etape' => 1, 'description' => 'Faire blanchir les os de porc 10 minutes puis rincer.'],
                ['etape' => 2, 'description' => 'Placer les os dans une grande marmite, couvrir d\'eau et porter à ébullition.'],
                ['etape' => 3, 'description' => 'Laisser mijoter à feu doux pendant 8 heures en ajoutant de l\'eau si nécessaire.'],
                ['etape' => 4, 'description' => 'Cuire la poitrine de porc séparément dans un bouillon épicé pendant 3 heures.'],
                ['etape' => 5, 'description' => 'Filtrer le bouillon d\'os et incorporer le miso et la sauce soja.'],
                ['etape' => 6, 'description' => 'Cuire les nouilles selon les instructions du paquet.'],
                ['etape' => 7, 'description' => 'Assembler : nouilles, bouillon chaud, tranches de porc, œuf mariné, oignons verts, bambou et nori.'],
                ['etape' => 8, 'description' => 'Arroser d\'huile de sésame et servir immédiatement.'],
            ],
            'status' => 'published',
        ]);

        // Recipe 3: Macaron (draft)
        Recipe::create([
            'user_id' => $author1->id,
            'category_id' => $patisserie->id,
            'title' => 'Macarons Parisiens',
            'slug' => 'macarons-parisiens',
            'description' => 'Les célèbres macarons français avec leur coque croquante et leur cœur moelleux. Une technique précise pour un résultat parfait.',
            'main_image_url' => 'https://images.unsplash.com/photo-1569864358642-9d1684040f43',
            'video_url' => null,
            'prep_time' => 60,
            'cook_time' => 15,
            'servings' => 30,
            'ingredients' => [
                ['quantite' => '200g', 'nom' => 'poudre d\'amandes'],
                ['quantite' => '200g', 'nom' => 'sucre glace'],
                ['quantite' => '150g', 'nom' => 'blancs d\'œufs'],
                ['quantite' => '200g', 'nom' => 'sucre semoule'],
                ['quantite' => 'Quelques gouttes', 'nom' => 'colorant alimentaire'],
                ['quantite' => '200g', 'nom' => 'ganache chocolat ou confiture'],
            ],
            'steps' => [
                ['etape' => 1, 'description' => 'Tamiser ensemble la poudre d\'amandes et le sucre glace.'],
                ['etape' => 2, 'description' => 'Monter les blancs en neige avec le sucre semoule jusqu\'à obtenir une meringue ferme et brillante.'],
                ['etape' => 3, 'description' => 'Incorporer délicatement le mélange d\'amandes à la meringue (macaronner).'],
                ['etape' => 4, 'description' => 'Dresser des petits cercles sur une plaque avec du papier sulfurisé.'],
                ['etape' => 5, 'description' => 'Laisser croûter 30 minutes à température ambiante.'],
                ['etape' => 6, 'description' => 'Cuire à 150°C pendant 12-15 minutes.'],
                ['etape' => 7, 'description' => 'Laisser refroidir complètement avant de garnir de ganache.'],
            ],
            'status' => 'draft',
        ]);
    }
}
