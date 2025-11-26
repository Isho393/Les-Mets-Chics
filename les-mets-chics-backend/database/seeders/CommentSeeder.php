<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Recipe;
use App\Models\User;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::where('role', '!=', 'admin')->get();
        $recipes = Recipe::all();

        if ($users->isEmpty() || $recipes->isEmpty()) {
            return;
        }

        // Commentaires pour Coq au Vin
        $coqAuVin = Recipe::where('slug', 'coq-au-vin-traditionnel')->first();
        if ($coqAuVin) {
            Comment::create([
                'user_id' => $users->random()->id,
                'recipe_id' => $coqAuVin->id,
                'body' => 'Recette absolument délicieuse ! Le poulet était fondant et la sauce parfaite.',
                'is_approved' => true,
            ]);

            Comment::create([
                'user_id' => $users->random()->id,
                'recipe_id' => $coqAuVin->id,
                'body' => 'J\'ai suivi la recette à la lettre, un régal ! Merci pour ce partage.',
                'is_approved' => true,
            ]);

            Comment::create([
                'user_id' => $users->random()->id,
                'recipe_id' => $coqAuVin->id,
                'body' => 'Combien de temps peut-on conserver ce plat au réfrigérateur ?',
                'is_approved' => true,
            ]);
        }

        // Commentaires pour Ramen
        $ramen = Recipe::where('slug', 'ramen-tonkotsu-maison')->first();
        if ($ramen) {
            Comment::create([
                'user_id' => $users->random()->id,
                'recipe_id' => $ramen->id,
                'body' => 'Le bouillon est incroyable ! 8 heures de cuisson mais ça vaut vraiment le coup.',
                'is_approved' => true,
            ]);

            Comment::create([
                'user_id' => $users->random()->id,
                'recipe_id' => $ramen->id,
                'body' => 'Peut-on utiliser des os de poulet à la place ?',
                'is_approved' => true,
            ]);
        }
    }
}
