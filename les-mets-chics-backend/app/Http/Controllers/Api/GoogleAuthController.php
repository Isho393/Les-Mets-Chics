<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class GoogleAuthController extends Controller
{
    /**
     * @OA\Get(
     *     path="/auth/google",
     *     tags={"Authentication"},
     *     summary="Redirection vers Google OAuth",
     *     description="Redirige l'utilisateur vers la page d'authentification Google",
     *     @OA\Response(
     *         response=302,
     *         description="Redirection vers Google"
     *     )
     * )
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->stateless()->redirect();
    }

    /**
     * @OA\Get(
     *     path="/auth/google/callback",
     *     tags={"Authentication"},
     *     summary="Callback Google OAuth",
     *     description="Gère le retour de Google après authentification. Crée un compte si l'email n'existe pas, sinon connecte l'utilisateur existant.",
     *     @OA\Parameter(
     *         name="code",
     *         in="query",
     *         required=true,
     *         description="Code d'autorisation Google",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Authentification réussie",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="token", type="string"),
     *             @OA\Property(property="user", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Compte en attente de validation"
     *     )
     * )
     */
    public function handleGoogleCallback(Request $request)
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();
            
            // Chercher l'utilisateur par google_id ou email
            $user = User::where('google_id', $googleUser->getId())
                ->orWhere('email', $googleUser->getEmail())
                ->first();

            if ($user) {
                // Utilisateur existant - mise à jour du google_id et avatar si nécessaire
                if (!$user->google_id) {
                    $user->google_id = $googleUser->getId();
                }
                if (!$user->avatar && $googleUser->getAvatar()) {
                    $user->avatar = $googleUser->getAvatar();
                }
                $user->save();

                // Vérifier le statut du compte
                if ($user->status === 'pending') {
                    return response()->json([
                        'message' => 'Votre compte est en attente de validation. Vous recevrez un email une fois votre compte activé.',
                        'status' => 'pending'
                    ], 403);
                }

                if ($user->status === 'suspended') {
                    return response()->json([
                        'message' => 'Votre compte a été suspendu.',
                        'status' => 'suspended'
                    ], 403);
                }

                // Créer le token pour l'utilisateur actif
                $token = $user->createToken('google-auth-token')->plainTextToken;

                // Rediriger vers le frontend avec le token
                $frontendUrl = env('FRONTEND_URL', 'http://localhost:3000');
                return redirect()->away("{$frontendUrl}/auth/callback?token={$token}&status=success");
            }

            // Nouvel utilisateur - créer le compte
            $user = User::create([
                'name' => $googleUser->getName(),
                'email' => $googleUser->getEmail(),
                'google_id' => $googleUser->getId(),
                'avatar' => $googleUser->getAvatar(),
                'password' => Hash::make(Str::random(32)), // Mot de passe aléatoire
                'role' => 'explorateur',
                'status' => 'pending',
            ]);

            // Déclencher l'événement pour notifier les admins
            event(new \App\Events\UserRegistered($user));

            // Rediriger vers le frontend avec statut pending
            $frontendUrl = env('FRONTEND_URL', 'http://localhost:3000');
            return redirect()->away("{$frontendUrl}/auth/callback?status=pending&message=" . urlencode('Votre compte est en attente de validation. Vous recevrez un email une fois votre compte activé.'));

        } catch (\Exception $e) {
            $frontendUrl = env('FRONTEND_URL', 'http://localhost:3000');
            return redirect()->away("{$frontendUrl}/auth/callback?status=error&message=" . urlencode('Erreur lors de l\'authentification Google'));
        }
    }
}
