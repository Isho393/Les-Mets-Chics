<?php

namespace App\Http\Controllers\Api;

use App\Models\Recipe;
use App\Models\Comment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\ContentModerationService;

class CommentController extends Controller
{
    /**
     * @OA\Post(
     *     path="/recipes/{slug}/comments",
     *     tags={"Comments"},
     *     summary="Ajouter un commentaire",
     *     description="Ajoute un commentaire à une recette. Le commentaire doit être approuvé par un admin avant d'être visible.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="slug",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"body"},
     *             @OA\Property(property="body", type="string", example="Super recette !")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Commentaire créé",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Commentaire envoyé ! Il sera visible après validation par un administrateur."),
     *             @OA\Property(property="comment", type="object",
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="body", type="string"),
     *                 @OA\Property(property="is_approved", type="boolean", example=false),
     *                 @OA\Property(property="created_at", type="string")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=422, description="Erreur de validation"),
     *     @OA\Response(response=404, description="Recette non trouvée")
     * )
     */
    public function store(Request $request, $slug)
    {
        $request->validate([
            'body' => 'required|string|min:3|max:1000',
        ]);

        // Vérifier le contenu avec le service de modération
        $moderationService = new ContentModerationService();
        if ($moderationService->containsBannedWords($request->body)) {
            return response()->json([
                'message' => $moderationService->getErrorMessage(),
            ], 422);
        }

        $recipe = Recipe::where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        $comment = Comment::create([
            'user_id' => $request->user()->id,
            'recipe_id' => $recipe->id,
            'body' => $request->body,
            'is_approved' => true, // Approuvé automatiquement si pas de mots interdits
        ]);

        // Charger la relation user pour la réponse
        $comment->load('user');

        return response()->json([
            'message' => 'Commentaire publié avec succès !',
            'comment' => [
                'id' => $comment->id,
                'body' => $comment->body,
                'is_approved' => $comment->is_approved,
                'created_at' => $comment->created_at->diffForHumans(),
                'user' => [
                    'name' => $comment->user->name,
                ],
            ],
        ], 201);
    }

    /**
     * @OA\Get(
     *     path="/recipes/{slug}/comments",
     *     tags={"Comments"},
     *     summary="Liste des commentaires",
     *     description="Retourne la liste des commentaires approuvés pour une recette",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="slug",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Liste des commentaires",
     *         @OA\JsonContent(
     *             @OA\Property(property="comments", type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer"),
     *                     @OA\Property(property="body", type="string"),
     *                     @OA\Property(property="created_at", type="string"),
     *                     @OA\Property(property="user", type="object",
     *                         @OA\Property(property="name", type="string")
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(response=404, description="Recette non trouvée")
     * )
     */
    public function index($slug)
    {
        $recipe = Recipe::where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        $comments = $recipe->comments()
            ->with('user')
            ->latest()
            ->get()
            ->map(function ($comment) {
                return [
                    'id' => $comment->id,
                    'body' => $comment->body,
                    'created_at' => $comment->created_at->diffForHumans(),
                    'user' => [
                        'name' => $comment->user->name,
                    ],
                ];
            });

        return response()->json([
            'comments' => $comments,
        ], 200);
    }
}
