<?php

namespace App\Http\Controllers\Api;

use App\Models\Recipe;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RecipeController extends Controller
{
    /**
     * @OA\Get(
     *     path="/recipes",
     *     tags={"Recipes"},
     *     summary="Liste des recettes publiées",
     *     description="Retourne toutes les recettes publiées avec leurs auteurs et catégories. Accessible sans authentification.",
     *     @OA\Response(
     *         response=200,
     *         description="Liste des recettes",
     *         @OA\JsonContent(
     *             @OA\Property(property="recipes", type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="title", type="string", example="Coq au Vin"),
     *                     @OA\Property(property="slug", type="string", example="coq-au-vin"),
     *                     @OA\Property(property="description", type="string"),
     *                     @OA\Property(property="main_image_url", type="string"),
     *                     @OA\Property(property="prep_time", type="integer", example=30),
     *                     @OA\Property(property="cook_time", type="integer", example=120),
     *                     @OA\Property(property="servings", type="integer", example=4),
     *                     @OA\Property(property="author", type="object",
     *                         @OA\Property(property="name", type="string", example="Chef Pierre")
     *                     ),
     *                     @OA\Property(property="category", type="object",
     *                         @OA\Property(property="name", type="string", example="Française"),
     *                         @OA\Property(property="slug", type="string", example="francaise")
     *                     ),
     *                     @OA\Property(property="likes_count", type="integer", example=42),
     *                     @OA\Property(property="comments_count", type="integer", example=5)
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function index()
    {
        $recipes = Recipe::with(['user', 'category'])
            ->where('status', 'published')
            ->latest()
            ->get()
            ->map(function ($recipe) {
                return [
                    'id' => $recipe->id,
                    'title' => $recipe->title,
                    'slug' => $recipe->slug,
                    'description' => $recipe->description,
                    'main_image_url' => $recipe->main_image_url,
                    'prep_time' => $recipe->prep_time,
                    'cook_time' => $recipe->cook_time,
                    'servings' => $recipe->servings,
                    'author' => [
                        'name' => $recipe->user->name,
                    ],
                    'category' => [
                        'name' => $recipe->category->name,
                        'slug' => $recipe->category->slug,
                    ],
                    'likes_count' => $recipe->likes()->count(),
                    'comments_count' => $recipe->comments()->where('is_approved', true)->count(),
                ];
            });

        return response()->json([
            'recipes' => $recipes,
        ], 200);
    }

    /**
     * @OA\Get(
     *     path="/recipes/{slug}",
     *     tags={"Recipes"},
     *     summary="Détails d'une recette",
     *     description="Retourne les détails complets d'une recette incluant vidéo, ingrédients et étapes. Nécessite authentification.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="slug",
     *         in="path",
     *         required=true,
     *         description="Slug de la recette",
     *         @OA\Schema(type="string", example="coq-au-vin")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Détails de la recette",
     *         @OA\JsonContent(
     *             @OA\Property(property="recipe", type="object",
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="title", type="string"),
     *                 @OA\Property(property="slug", type="string"),
     *                 @OA\Property(property="description", type="string"),
     *                 @OA\Property(property="main_image_url", type="string"),
     *                 @OA\Property(property="video_url", type="string", nullable=true),
     *                 @OA\Property(property="ingredients", type="array", @OA\Items(type="object")),
     *                 @OA\Property(property="steps", type="array", @OA\Items(type="object")),
     *                 @OA\Property(property="likes_count", type="integer"),
     *                 @OA\Property(property="comments", type="array", @OA\Items(type="object"))
     *             )
     *         )
     *     ),
     *     @OA\Response(response=404, description="Recette non trouvée"),
     *     @OA\Response(response=401, description="Non authentifié")
     * )
     */
    public function show($slug)
    {
        $recipe = Recipe::with(['user', 'category', 'comments' => function ($query) {
            $query->where('is_approved', true)->with('user')->latest();
        }])
            ->where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        return response()->json([
            'recipe' => [
                'id' => $recipe->id,
                'title' => $recipe->title,
                'slug' => $recipe->slug,
                'description' => $recipe->description,
                'main_image_url' => $recipe->main_image_url,
                'video_url' => $recipe->video_url,
                'prep_time' => $recipe->prep_time,
                'cook_time' => $recipe->cook_time,
                'servings' => $recipe->servings,
                'ingredients' => $recipe->ingredients,
                'steps' => $recipe->steps,
                'author' => [
                    'id' => $recipe->user->id,
                    'name' => $recipe->user->name,
                ],
                'category' => [
                    'id' => $recipe->category->id,
                    'name' => $recipe->category->name,
                    'slug' => $recipe->category->slug,
                ],
                'likes_count' => $recipe->likes()->count(),
                'comments' => $recipe->comments->map(function ($comment) {
                    return [
                        'id' => $comment->id,
                        'body' => $comment->body,
                        'created_at' => $comment->created_at->diffForHumans(),
                        'user' => [
                            'name' => $comment->user->name,
                        ],
                    ];
                }),
            ],
        ], 200);
    }
}
