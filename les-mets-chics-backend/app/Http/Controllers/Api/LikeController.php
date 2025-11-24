<?php

namespace App\Http\Controllers\Api;

use App\Models\Recipe;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LikeController extends Controller
{
    /**
     * @OA\Post(
     *     path="/recipes/{slug}/like",
     *     tags={"Likes"},
     *     summary="Liker/Unliker une recette",
     *     description="Ajoute ou retire un like sur une recette pour l'utilisateur connecté",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="slug",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Action réussie",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Recette aimée !"),
     *             @OA\Property(property="liked", type="boolean", example=true),
     *             @OA\Property(property="likes_count", type="integer", example=42)
     *         )
     *     ),
     *     @OA\Response(response=404, description="Recette non trouvée")
     * )
     */
    public function toggle(Request $request, $slug)
    {
        $recipe = Recipe::where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        $user = $request->user();

        // Check if user has already liked this recipe
        $existingLike = $recipe->likes()->where('user_id', $user->id)->exists();

        if ($existingLike) {
            // Remove like
            $recipe->likes()->detach($user->id);
            $liked = false;
            $message = 'Like retiré.';
        } else {
            // Add like
            $recipe->likes()->attach($user->id);
            $liked = true;
            $message = 'Recette aimée !';
        }

        return response()->json([
            'message' => $message,
            'liked' => $liked,
            'likes_count' => $recipe->likes()->count(),
        ], 200);
    }

    /**
     * @OA\Get(
     *     path="/recipes/{slug}/like/check",
     *     tags={"Likes"},
     *     summary="Vérifier le like",
     *     description="Vérifie si l'utilisateur connecté a déjà liké la recette",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="slug",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="État du like",
     *         @OA\JsonContent(
     *             @OA\Property(property="liked", type="boolean", example=true),
     *             @OA\Property(property="likes_count", type="integer", example=42)
     *         )
     *     ),
     *     @OA\Response(response=404, description="Recette non trouvée")
     * )
     */
    public function check(Request $request, $slug)
    {
        $recipe = Recipe::where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        $liked = $recipe->likes()->where('user_id', $request->user()->id)->exists();

        return response()->json([
            'liked' => $liked,
            'likes_count' => $recipe->likes()->count(),
        ], 200);
    }
}
