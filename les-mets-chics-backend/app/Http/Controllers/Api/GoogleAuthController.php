<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
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
        try {
            Log::info('Google OAuth: Redirection initiée');
            
            // Vérifier la configuration
            if (!config('services.google.client_id') || !config('services.google.client_secret')) {
                Log::error('Google OAuth: Configuration manquante', [
                    'client_id' => config('services.google.client_id') ? 'présent' : 'manquant',
                    'client_secret' => config('services.google.client_secret') ? 'présent' : 'manquant',
                ]);
                
                $frontendUrl = config('app.frontend_url', 'http://localhost:3000');
                return redirect()->away("{$frontendUrl}/auth/callback?status=error&message=" . urlencode('Configuration Google OAuth manquante'));
            }

            return Socialite::driver('google')
                ->stateless()
                ->redirect();
                
        } catch (\Exception $e) {
            Log::error('Google OAuth: Erreur lors de la redirection', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            $frontendUrl = config('app.frontend_url', 'http://localhost:3000');
            return redirect()->away("{$frontendUrl}/auth/callback?status=error&message=" . urlencode('Erreur de configuration Google OAuth'));
        }
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
        $frontendUrl = config('app.frontend_url', 'http://localhost:3000');
        
        try {
            Log::info('Google OAuth: Callback reçu', [
                'query_params' => $request->query()
            ]);

            // Vérifier si l'utilisateur a refusé l'autorisation
            if ($request->has('error')) {
                Log::warning('Google OAuth: Autorisation refusée', [
                    'error' => $request->get('error')
                ]);
                
                return redirect()->away("{$frontendUrl}/auth/callback?status=error&message=" . urlencode('Autorisation Google refusée'));
            }

            // Vérifier si le code est présent
            if (!$request->has('code')) {
                Log::error('Google OAuth: Code d\'autorisation manquant');
                
                return redirect()->away("{$frontendUrl}/auth/callback?status=error&message=" . urlencode('Code d\'autorisation manquant'));
            }

            // Récupérer les informations de l'utilisateur Google
            $googleUser = Socialite::driver('google')->stateless()->user();
            
            Log::info('Google OAuth: Utilisateur récupéré', [
                'google_id' => $googleUser->getId(),
                'email' => $googleUser->getEmail(),
                'name' => $googleUser->getName()
            ]);
            
            // Chercher l'utilisateur par google_id ou email
            $user = User::where('google_id', $googleUser->getId())
                ->orWhere('email', $googleUser->getEmail())
                ->first();

            if ($user) {
                Log::info('Google OAuth: Utilisateur existant trouvé', [
                    'user_id' => $user->id,
                    'status' => $user->status
                ]);
                
                // Utilisateur existant - mise à jour du google_id et avatar si nécessaire
                if (!$user->google_id) {
                    $user->google_id = $googleUser->getId();
                    Log::info('Google OAuth: google_id ajouté à l\'utilisateur existant');
                }
                if (!$user->avatar && $googleUser->getAvatar()) {
                    $user->avatar = $googleUser->getAvatar();
                    Log::info('Google OAuth: Avatar Google ajouté à l\'utilisateur existant');
                }
                $user->save();

                // Vérifier le statut du compte
                if ($user->status === 'pending') {
                    Log::info('Google OAuth: Compte en attente de validation', [
                        'user_id' => $user->id
                    ]);
                    
                    return redirect()->away("{$frontendUrl}/auth/callback?status=pending&message=" . urlencode('Votre compte est en attente de validation. Vous recevrez un email une fois votre compte activé.'));
                }

                if ($user->status === 'suspended') {
                    Log::warning('Google OAuth: Tentative de connexion avec compte suspendu', [
                        'user_id' => $user->id
                    ]);
                    
                    return redirect()->away("{$frontendUrl}/auth/callback?status=error&message=" . urlencode('Votre compte a été suspendu.'));
                }

                // Créer le token pour l'utilisateur actif
                $token = $user->createToken('google-auth-token')->plainTextToken;
                
                Log::info('Google OAuth: Connexion réussie', [
                    'user_id' => $user->id
                ]);

                // Rediriger vers le frontend avec le token
                return redirect()->away("{$frontendUrl}/auth/callback?token={$token}&status=success");
            }

            // Nouvel utilisateur - créer le compte
            Log::info('Google OAuth: Création d\'un nouvel utilisateur', [
                'email' => $googleUser->getEmail()
            ]);
            
            $user = User::create([
                'name' => $googleUser->getName(),
                'email' => $googleUser->getEmail(),
                'google_id' => $googleUser->getId(),
                'avatar' => $googleUser->getAvatar(),
                'password' => Hash::make(Str::random(32)), // Mot de passe aléatoire
                'role' => 'explorateur',
                'status' => 'pending',
            ]);

            Log::info('Google OAuth: Nouvel utilisateur créé', [
                'user_id' => $user->id,
                'email' => $user->email
            ]);

            // Déclencher l'événement pour notifier les admins
            event(new \App\Events\UserRegistered($user));

            // Rediriger vers le frontend avec statut pending
            return redirect()->away("{$frontendUrl}/auth/callback?status=pending&message=" . urlencode('Votre compte est en attente de validation. Vous recevrez un email une fois votre compte activé.'));

        } catch (\Laravel\Socialite\Two\InvalidStateException $e) {
            Log::error('Google OAuth: État invalide (possible CSRF)', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->away("{$frontendUrl}/auth/callback?status=error&message=" . urlencode('Session expirée. Veuillez réessayer.'));
            
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            Log::error('Google OAuth: Erreur de communication avec Google', [
                'error' => $e->getMessage(),
                'response' => $e->hasResponse() ? $e->getResponse()->getBody()->getContents() : 'Pas de réponse'
            ]);
            
            return redirect()->away("{$frontendUrl}/auth/callback?status=error&message=" . urlencode('Erreur de communication avec Google. Vérifiez vos credentials.'));
            
        } catch (\Exception $e) {
            Log::error('Google OAuth: Erreur inattendue', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->away("{$frontendUrl}/auth/callback?status=error&message=" . urlencode('Erreur lors de l\'authentification Google: ' . $e->getMessage()));
        }
    }

    /**
     * Route de test pour vérifier la configuration Google OAuth
     */
    public function testConfig()
    {
        $config = [
            'client_id' => config('services.google.client_id') ? 'Configuré ✓' : 'Manquant ✗',
            'client_secret' => config('services.google.client_secret') ? 'Configuré ✓' : 'Manquant ✗',
            'redirect_uri' => config('services.google.redirect'),
            'frontend_url' => config('app.frontend_url'),
        ];

        Log::info('Google OAuth: Test de configuration', $config);

        return response()->json([
            'message' => 'Configuration Google OAuth',
            'config' => $config,
            'status' => (config('services.google.client_id') && config('services.google.client_secret')) ? 'OK' : 'ERREUR'
        ]);
    }
}
