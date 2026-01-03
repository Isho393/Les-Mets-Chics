<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * @OA\Post(
     *     path="/register",
     *     tags={"Authentication"},
     *     summary="Inscription d'un nouvel utilisateur",
     *     description="Crée un compte avec role='explorateur' et status='pending'. L'utilisateur doit attendre l'approbation d'un admin.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","email","password","password_confirmation"},
     *             @OA\Property(property="name", type="string", example="Jean Dupont"),
     *             @OA\Property(property="email", type="string", format="email", example="jean@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="password123"),
     *             @OA\Property(property="password_confirmation", type="string", format="password", example="password123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Inscription réussie",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Inscription réussie. Votre compte est en attente de validation par un administrateur."),
     *             @OA\Property(property="user", type="object",
     *                 @OA\Property(property="name", type="string", example="Jean Dupont"),
     *                 @OA\Property(property="email", type="string", example="jean@example.com"),
     *                 @OA\Property(property="status", type="string", example="pending")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=422, description="Erreur de validation")
     * )
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $avatarPath = null;
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'avatar' => $avatarPath,
            'role' => 'explorateur',
            'status' => 'pending',
        ]);

        // Déclencher l'événement pour notifier les admins
        event(new \App\Events\UserRegistered($user));

        return response()->json([
            'message' => 'Inscription réussie. Votre compte est en attente de validation. Vous recevrez un email une fois votre compte activé.',
            'user' => [
                'name' => $user->name,
                'email' => $user->email,
                'status' => $user->status,
                'avatar' => $avatarPath ? asset('storage/' . $avatarPath) : null,
            ],
        ], 201);
    }

    /**
     * @OA\Post(
     *     path="/login",
     *     tags={"Authentication"},
     *     summary="Connexion utilisateur",
     *     description="Authentifie l'utilisateur et retourne un token Sanctum si le compte est actif",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email","password"},
     *             @OA\Property(property="email", type="string", format="email", example="jean@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="password")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Connexion réussie",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Connexion réussie."),
     *             @OA\Property(property="token", type="string", example="1|abc123..."),
     *             @OA\Property(property="user", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Jean Dupont"),
     *                 @OA\Property(property="email", type="string", example="jean@example.com"),
     *                 @OA\Property(property="role", type="string", example="explorateur"),
     *                 @OA\Property(property="status", type="string", example="active")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=401, description="Identifiants incorrects"),
     *     @OA\Response(response=403, description="Compte en attente ou suspendu")
     * )
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Email ou mot de passe incorrect.',
            ], 401);
        }

        // Check if account is pending
        if ($user->status === 'pending') {
            return response()->json([
                'message' => 'Votre compte est en attente de validation par un administrateur.',
            ], 403);
        }

        // Check if account is suspended
        if ($user->status === 'suspended') {
            return response()->json([
                'message' => 'Votre compte a été suspendu.',
            ], 403);
        }

        // Create token
        $token = $user->createToken('auth-token')->plainTextToken;

        $avatarUrl = null;
        if ($user->avatar) {
            // Si c'est une URL Google (commence par http)
            if (str_starts_with($user->avatar, 'http')) {
                $avatarUrl = $user->avatar;
            } else {
                // Si c'est un fichier local
                $avatarUrl = asset('storage/' . $user->avatar);
            }
        }

        return response()->json([
            'message' => 'Connexion réussie.',
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'status' => $user->status,
                'avatar' => $avatarUrl,
            ],
        ], 200);
    }

    /**
     * @OA\Post(
     *     path="/logout",
     *     tags={"Authentication"},
     *     summary="Déconnexion",
     *     description="Révoque le token actuel de l'utilisateur",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Déconnexion réussie",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Déconnexion réussie.")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Non authentifié")
     * )
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Déconnexion réussie.',
        ], 200);
    }

    /**
     * @OA\Get(
     *     path="/user",
     *     tags={"Authentication"},
     *     summary="Informations utilisateur",
     *     description="Retourne les informations de l'utilisateur authentifié",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Informations utilisateur",
     *         @OA\JsonContent(
     *             @OA\Property(property="user", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Jean Dupont"),
     *                 @OA\Property(property="email", type="string", example="jean@example.com"),
     *                 @OA\Property(property="role", type="string", example="explorateur"),
     *                 @OA\Property(property="status", type="string", example="active")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=401, description="Non authentifié")
     * )
     */
    public function user(Request $request)
    {
        $user = $request->user();
        $avatarUrl = null;
        
        if ($user->avatar) {
            // Si c'est une URL Google (commence par http)
            if (str_starts_with($user->avatar, 'http')) {
                $avatarUrl = $user->avatar;
            } else {
                // Si c'est un fichier local
                $avatarUrl = asset('storage/' . $user->avatar);
            }
        }

        return response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'status' => $user->status,
                'avatar' => $avatarUrl,
            ],
        ], 200);
    }

    /**
     * @OA\Put(
     *     path="/user/profile",
     *     tags={"Authentication"},
     *     summary="Mise à jour du profil",
     *     description="Met à jour le nom et l'avatar de l'utilisateur",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="Jean Dupont"),
     *             @OA\Property(property="avatar", type="string", format="binary")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Profil mis à jour",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Profil mis à jour avec succès."),
     *             @OA\Property(property="user", type="object")
     *         )
     *     ),
     *     @OA\Response(response=422, description="Erreur de validation")
     * )
     */
    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user->name = $request->name;

        // Gérer l'upload de l'avatar
        if ($request->hasFile('avatar')) {
            // Supprimer l'ancien avatar s'il existe et n'est pas une URL Google
            if ($user->avatar && !str_starts_with($user->avatar, 'http')) {
                \Storage::disk('public')->delete($user->avatar);
            }
            
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $avatarPath;
        }

        $user->save();

        $avatarUrl = null;
        if ($user->avatar) {
            if (str_starts_with($user->avatar, 'http')) {
                $avatarUrl = $user->avatar;
            } else {
                $avatarUrl = asset('storage/' . $user->avatar);
            }
        }

        return response()->json([
            'message' => 'Profil mis à jour avec succès.',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'status' => $user->status,
                'avatar' => $avatarUrl,
            ],
        ], 200);
    }

    /**
     * @OA\Put(
     *     path="/user/password",
     *     tags={"Authentication"},
     *     summary="Changement de mot de passe",
     *     description="Change le mot de passe de l'utilisateur",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"current_password","password","password_confirmation"},
     *             @OA\Property(property="current_password", type="string", format="password"),
     *             @OA\Property(property="password", type="string", format="password"),
     *             @OA\Property(property="password_confirmation", type="string", format="password")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Mot de passe changé",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Mot de passe changé avec succès.")
     *         )
     *     ),
     *     @OA\Response(response=422, description="Erreur de validation")
     * )
     */
    public function updatePassword(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Vérifier le mot de passe actuel
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'message' => 'Le mot de passe actuel est incorrect.',
                'errors' => [
                    'current_password' => ['Le mot de passe actuel est incorrect.']
                ]
            ], 422);
        }

        // Mettre à jour le mot de passe
        $user->password = Hash::make($request->password);
        $user->save();

        return response()->json([
            'message' => 'Mot de passe changé avec succès.',
        ], 200);
    }
}
