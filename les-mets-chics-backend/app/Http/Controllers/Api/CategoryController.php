<?php

namespace App\Http\Controllers\Api;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    /**
     * @OA\Get(
     *     path="/categories",
     *     tags={"Categories"},
     *     summary="Liste des catégories",
     *     description="Retourne toutes les catégories avec le nombre de recettes publiées",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Liste des catégories",
     *         @OA\JsonContent(
     *             @OA\Property(property="categories", type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer"),
     *                     @OA\Property(property="name", type="string"),
     *                     @OA\Property(property="slug", type="string"),
     *                     @OA\Property(property="description", type="string"),
     *                     @OA\Property(property="recipes_count", type="integer")
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function index()
    {
        $categories = Category::withCount(['recipes' => function ($query) {
            $query->where('status', 'published');
        }])
            ->get()
            ->map(function ($category) {
                return [
                    'id' => $category->id,
                    'name' => $category->name,
                    'slug' => $category->slug,
                    'description' => $category->description,
                    'recipes_count' => $category->recipes_count,
                ];
            });

        return response()->json([
            'categories' => $categories,
        ], 200);
    }

    /**
     * @OA\Get(
     *     path="/categories/{slug}/recipes",
     *     tags={"Categories"},
     *     summary="Recettes par catégorie",
     *     description="Retourne toutes les recettes publiées d'une catégorie",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="slug",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response=200, description="Recettes de la catégorie"),
     *     @OA\Response(response=404, description="Catégorie non trouvée")
     * )
     */
    public function recipes($slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();

        $recipes = $category->recipes()
            ->with(['user'])
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
                    'likes_count' => $recipe->likes()->count(),
                ];
            });

        return response()->json([
            'category' => [
                'name' => $category->name,
                'slug' => $category->slug,
                'description' => $category->description,
            ],
            'recipes' => $recipes,
        ], 200);
    }
}
