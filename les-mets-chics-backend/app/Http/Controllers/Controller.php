<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(
 *     title="Les Mets Chics API",
 *     version="1.0.0",
 *     description="API REST pour le blog gastronomique Les Mets Chics. Permet la gestion des recettes, catégories, utilisateurs, likes et commentaires.",
 *     @OA\Contact(
 *         email="admin@lesmetchics.com",
 *         name="Les Mets Chics"
 *     )
 * )
 * 
 * @OA\Server(
 *     url="http://localhost:8000/api",
 *     description="Serveur de développement local"
 * )
 * 
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="Sanctum Token",
 *     description="Utilisez le token obtenu via /login"
 * )
 * 
 * @OA\Tag(
 *     name="Authentication",
 *     description="Endpoints d'authentification (inscription, connexion, déconnexion)"
 * )
 * 
 * @OA\Tag(
 *     name="Recipes",
 *     description="Gestion des recettes gastronomiques"
 * )
 * 
 * @OA\Tag(
 *     name="Categories",
 *     description="Gestion des catégories (origines culinaires)"
 * )
 * 
 * @OA\Tag(
 *     name="Likes",
 *     description="Système de likes sur les recettes"
 * )
 * 
 * @OA\Tag(
 *     name="Comments",
 *     description="Système de commentaires avec modération"
 * )
 */
class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}
