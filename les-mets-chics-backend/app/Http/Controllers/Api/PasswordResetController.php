<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\PasswordResetMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class PasswordResetController extends Controller
{
    /**
     * Envoyer un email de réinitialisation de mot de passe
     */
    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            // Pour des raisons de sécurité, on ne révèle pas si l'email existe ou non
            return response()->json([
                'message' => 'Si cet email existe dans notre système, vous recevrez un lien de réinitialisation.',
            ], 200);
        }

        // Supprimer les anciens tokens pour cet email
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        // Créer un nouveau token
        $token = Str::random(64);

        DB::table('password_reset_tokens')->insert([
            'email' => $request->email,
            'token' => Hash::make($token),
            'created_at' => now(),
        ]);

        // Créer l'URL de réinitialisation (frontend)
        $frontendUrl = env('FRONTEND_URL', 'http://localhost:3000');
        $resetUrl = $frontendUrl . '/reinitialiser-mot-de-passe?token=' . $token . '&email=' . urlencode($request->email);

        // Envoyer l'email
        try {
            Mail::to($user->email)->send(new PasswordResetMail($resetUrl, $user->name));
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erreur lors de l\'envoi de l\'email. Veuillez réessayer.',
            ], 500);
        }

        return response()->json([
            'message' => 'Si cet email existe dans notre système, vous recevrez un lien de réinitialisation.',
        ], 200);
    }

    /**
     * Réinitialiser le mot de passe
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'token' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Vérifier si l'utilisateur existe
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'message' => 'Email invalide.',
            ], 422);
        }

        // Récupérer le token de réinitialisation
        $resetRecord = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$resetRecord) {
            return response()->json([
                'message' => 'Token de réinitialisation invalide ou expiré.',
            ], 422);
        }

        // Vérifier si le token correspond
        if (!Hash::check($request->token, $resetRecord->token)) {
            return response()->json([
                'message' => 'Token de réinitialisation invalide.',
            ], 422);
        }

        // Vérifier si le token n'est pas expiré (60 minutes)
        $createdAt = \Carbon\Carbon::parse($resetRecord->created_at);
        if ($createdAt->addMinutes(60)->isPast()) {
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();
            return response()->json([
                'message' => 'Le token de réinitialisation a expiré. Veuillez faire une nouvelle demande.',
            ], 422);
        }

        // Mettre à jour le mot de passe
        $user->password = Hash::make($request->password);
        $user->save();

        // Supprimer le token utilisé
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        // Révoquer tous les tokens d'accès existants pour forcer une nouvelle connexion
        $user->tokens()->delete();

        return response()->json([
            'message' => 'Votre mot de passe a été réinitialisé avec succès. Vous pouvez maintenant vous connecter.',
        ], 200);
    }
}
