#!/usr/bin/env php
<?php

/**
 * Script de test pour la configuration Google OAuth
 * 
 * Usage: php test-google-oauth.php
 */

echo "\n";
echo "╔════════════════════════════════════════════════════════════╗\n";
echo "║     Test de configuration Google OAuth - Les Mets Chics   ║\n";
echo "╚════════════════════════════════════════════════════════════╝\n";
echo "\n";

// Charger l'environnement Laravel
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Fonction pour afficher un résultat
function checkResult($label, $value, $expected = true) {
    $status = $value === $expected ? '✓' : '✗';
    $color = $value === $expected ? "\033[32m" : "\033[31m";
    $reset = "\033[0m";
    
    echo sprintf("%-50s %s%s%s\n", $label, $color, $status, $reset);
    return $value === $expected;
}

// Fonction pour afficher une valeur
function showValue($label, $value) {
    $display = $value ? (strlen($value) > 50 ? substr($value, 0, 47) . '...' : $value) : '(vide)';
    echo sprintf("%-50s %s\n", $label, $display);
}

echo "📋 Vérification de la configuration\n";
echo str_repeat("─", 60) . "\n";

$allGood = true;

// 1. Vérifier les variables d'environnement
$clientId = env('GOOGLE_CLIENT_ID');
$clientSecret = env('GOOGLE_CLIENT_SECRET');
$redirectUri = env('GOOGLE_REDIRECT_URI');
$frontendUrl = env('FRONTEND_URL');
$appUrl = env('APP_URL');

$allGood &= checkResult('GOOGLE_CLIENT_ID défini', !empty($clientId));
$allGood &= checkResult('GOOGLE_CLIENT_SECRET défini', !empty($clientSecret));
$allGood &= checkResult('GOOGLE_REDIRECT_URI défini', !empty($redirectUri));
$allGood &= checkResult('FRONTEND_URL défini', !empty($frontendUrl));
$allGood &= checkResult('APP_URL défini', !empty($appUrl));

echo "\n";
echo "📝 Valeurs de configuration\n";
echo str_repeat("─", 60) . "\n";

showValue('GOOGLE_CLIENT_ID', $clientId);
showValue('GOOGLE_CLIENT_SECRET', $clientSecret ? str_repeat('*', 20) : '');
showValue('GOOGLE_REDIRECT_URI', $redirectUri);
showValue('FRONTEND_URL', $frontendUrl);
showValue('APP_URL', $appUrl);

echo "\n";
echo "🔧 Vérification de la configuration Laravel\n";
echo str_repeat("─", 60) . "\n";

$configClientId = config('services.google.client_id');
$configClientSecret = config('services.google.client_secret');
$configRedirect = config('services.google.redirect');
$configFrontendUrl = config('app.frontend_url');

$allGood &= checkResult('Config services.google.client_id', !empty($configClientId));
$allGood &= checkResult('Config services.google.client_secret', !empty($configClientSecret));
$allGood &= checkResult('Config services.google.redirect', !empty($configRedirect));
$allGood &= checkResult('Config app.frontend_url', !empty($configFrontendUrl));

echo "\n";
echo "🔍 Vérification de la cohérence\n";
echo str_repeat("─", 60) . "\n";

$allGood &= checkResult('Client ID cohérent', $clientId === $configClientId);
$allGood &= checkResult('Client Secret cohérent', $clientSecret === $configClientSecret);
$allGood &= checkResult('Redirect URI cohérent', $redirectUri === $configRedirect);
$allGood &= checkResult('Frontend URL cohérent', $frontendUrl === $configFrontendUrl);

echo "\n";
echo "📦 Vérification des packages\n";
echo str_repeat("─", 60) . "\n";

$composerLock = json_decode(file_get_contents(__DIR__.'/composer.lock'), true);
$packages = array_merge($composerLock['packages'] ?? [], $composerLock['packages-dev'] ?? []);

$socialiteInstalled = false;
$socialiteVersion = null;

foreach ($packages as $package) {
    if ($package['name'] === 'laravel/socialite') {
        $socialiteInstalled = true;
        $socialiteVersion = $package['version'];
        break;
    }
}

$allGood &= checkResult('Laravel Socialite installé', $socialiteInstalled);
if ($socialiteInstalled) {
    showValue('Version de Socialite', $socialiteVersion);
}

echo "\n";
echo "🌐 Vérification des URLs\n";
echo str_repeat("─", 60) . "\n";

$expectedRedirectUri = $appUrl . '/api/auth/google/callback';
$redirectUriMatch = $redirectUri === $expectedRedirectUri;

if (!$redirectUriMatch) {
    echo "⚠️  ATTENTION: L'URI de redirection ne correspond pas\n";
    echo "   Attendu  : " . $expectedRedirectUri . "\n";
    echo "   Configuré: " . $redirectUri . "\n";
    echo "\n";
}

$allGood &= checkResult('URI de redirection correcte', $redirectUriMatch);

echo "\n";
echo "🔐 Vérification de la base de données\n";
echo str_repeat("─", 60) . "\n";

try {
    $usersTable = \Illuminate\Support\Facades\Schema::hasTable('users');
    $allGood &= checkResult('Table users existe', $usersTable);
    
    if ($usersTable) {
        $hasGoogleId = \Illuminate\Support\Facades\Schema::hasColumn('users', 'google_id');
        $hasAvatar = \Illuminate\Support\Facades\Schema::hasColumn('users', 'avatar');
        $hasStatus = \Illuminate\Support\Facades\Schema::hasColumn('users', 'status');
        
        $allGood &= checkResult('Colonne google_id existe', $hasGoogleId);
        $allGood &= checkResult('Colonne avatar existe', $hasAvatar);
        $allGood &= checkResult('Colonne status existe', $hasStatus);
    }
} catch (\Exception $e) {
    echo "✗ Erreur de connexion à la base de données\n";
    echo "  " . $e->getMessage() . "\n";
    $allGood = false;
}

echo "\n";
echo "📁 Vérification des fichiers\n";
echo str_repeat("─", 60) . "\n";

$controllerExists = file_exists(__DIR__.'/app/Http/Controllers/Api/GoogleAuthController.php');
$routesExist = file_exists(__DIR__.'/routes/api.php');
$configExists = file_exists(__DIR__.'/config/services.php');

$allGood &= checkResult('GoogleAuthController existe', $controllerExists);
$allGood &= checkResult('routes/api.php existe', $routesExist);
$allGood &= checkResult('config/services.php existe', $configExists);

echo "\n";
echo "🎯 Vérification des routes\n";
echo str_repeat("─", 60) . "\n";

try {
    $routes = \Illuminate\Support\Facades\Route::getRoutes();
    $googleRoute = false;
    $callbackRoute = false;
    $testRoute = false;
    
    foreach ($routes as $route) {
        $uri = $route->uri();
        if ($uri === 'api/auth/google') $googleRoute = true;
        if ($uri === 'api/auth/google/callback') $callbackRoute = true;
        if ($uri === 'api/auth/google/test') $testRoute = true;
    }
    
    $allGood &= checkResult('Route /api/auth/google existe', $googleRoute);
    $allGood &= checkResult('Route /api/auth/google/callback existe', $callbackRoute);
    $allGood &= checkResult('Route /api/auth/google/test existe', $testRoute);
} catch (\Exception $e) {
    echo "✗ Erreur lors de la vérification des routes\n";
    echo "  " . $e->getMessage() . "\n";
    $allGood = false;
}

echo "\n";
echo str_repeat("═", 60) . "\n";

if ($allGood) {
    echo "\033[32m✓ Tous les tests sont passés !\033[0m\n";
    echo "\n";
    echo "🚀 Prochaines étapes:\n";
    echo "   1. Vérifiez Google Cloud Console:\n";
    echo "      - URI de redirection: " . $redirectUri . "\n";
    echo "      - Origines autorisées: " . $frontendUrl . "\n";
    echo "   2. Testez la configuration:\n";
    echo "      - " . $appUrl . "/api/auth/google/test\n";
    echo "   3. Testez l'authentification:\n";
    echo "      - " . $frontendUrl . "/inscription\n";
    echo "\n";
} else {
    echo "\033[31m✗ Certains tests ont échoué\033[0m\n";
    echo "\n";
    echo "🔧 Actions recommandées:\n";
    echo "   1. Vérifiez votre fichier .env\n";
    echo "   2. Exécutez: php artisan config:clear\n";
    echo "   3. Exécutez: php artisan cache:clear\n";
    echo "   4. Consultez: Docs/GOOGLE_OAUTH_DEBUG.md\n";
    echo "\n";
}

echo str_repeat("═", 60) . "\n";
echo "\n";

exit($allGood ? 0 : 1);
