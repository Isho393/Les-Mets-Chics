# Script PowerShell pour installer le certificat SSL CA Bundle
# Usage: .\install-ssl-certificate.ps1

Write-Host ""
Write-Host "╔════════════════════════════════════════════════════════════╗" -ForegroundColor Cyan
Write-Host "║   Installation du certificat SSL CA Bundle pour PHP       ║" -ForegroundColor Cyan
Write-Host "╚════════════════════════════════════════════════════════════╝" -ForegroundColor Cyan
Write-Host ""

# Détecter le chemin de php.ini
Write-Host "🔍 Détection de la configuration PHP..." -ForegroundColor Yellow
$phpIniPath = php --ini | Select-String "Loaded Configuration File" | ForEach-Object { $_.ToString().Split(":")[1].Trim() }

if (-not $phpIniPath -or -not (Test-Path $phpIniPath)) {
    Write-Host "❌ Impossible de trouver le fichier php.ini" -ForegroundColor Red
    Write-Host "   Exécutez: php --ini" -ForegroundColor Yellow
    exit 1
}

Write-Host "✓ Fichier php.ini trouvé: $phpIniPath" -ForegroundColor Green

# Déterminer le dossier PHP
$phpDir = Split-Path -Parent $phpIniPath
$certPath = Join-Path $phpDir "cacert.pem"

Write-Host ""
Write-Host "📥 Téléchargement du certificat CA Bundle..." -ForegroundColor Yellow

try {
    # Télécharger le certificat
    Invoke-WebRequest -Uri "https://curl.se/ca/cacert.pem" -OutFile $certPath -UseBasicParsing
    Write-Host "✓ Certificat téléchargé: $certPath" -ForegroundColor Green
} catch {
    Write-Host "❌ Erreur lors du téléchargement: $_" -ForegroundColor Red
    Write-Host ""
    Write-Host "📝 Téléchargez manuellement depuis: https://curl.se/ca/cacert.pem" -ForegroundColor Yellow
    Write-Host "   Et sauvegardez-le dans: $certPath" -ForegroundColor Yellow
    exit 1
}

Write-Host ""
Write-Host "🔧 Configuration de php.ini..." -ForegroundColor Yellow

# Lire le contenu de php.ini
$phpIniContent = Get-Content $phpIniPath -Raw

# Vérifier si curl.cainfo est déjà configuré
if ($phpIniContent -match "curl\.cainfo\s*=\s*`".*`"") {
    Write-Host "⚠️  curl.cainfo est déjà configuré dans php.ini" -ForegroundColor Yellow
} else {
    # Créer une sauvegarde
    $backupPath = "$phpIniPath.backup." + (Get-Date -Format "yyyyMMdd_HHmmss")
    Copy-Item $phpIniPath $backupPath
    Write-Host "✓ Sauvegarde créée: $backupPath" -ForegroundColor Green
    
    # Modifier php.ini
    $phpIniContent = $phpIniContent -replace ";curl\.cainfo\s*=.*", "curl.cainfo = `"$certPath`""
    $phpIniContent = $phpIniContent -replace ";openssl\.cafile\s*=.*", "openssl.cafile = `"$certPath`""
    
    # Si les lignes n'existent pas, les ajouter
    if ($phpIniContent -notmatch "curl\.cainfo") {
        $phpIniContent += "`r`n`r`n; SSL Certificate`r`ncurl.cainfo = `"$certPath`"`r`n"
    }
    if ($phpIniContent -notmatch "openssl\.cafile") {
        $phpIniContent += "openssl.cafile = `"$certPath`"`r`n"
    }
    
    # Écrire le fichier
    Set-Content -Path $phpIniPath -Value $phpIniContent -NoNewline
    Write-Host "✓ php.ini configuré" -ForegroundColor Green
}

Write-Host ""
Write-Host "🧪 Vérification de la configuration..." -ForegroundColor Yellow

# Vérifier curl.cainfo
$curlCaInfo = php -r "echo ini_get('curl.cainfo');"
if ($curlCaInfo) {
    Write-Host "✓ curl.cainfo = $curlCaInfo" -ForegroundColor Green
} else {
    Write-Host "⚠️  curl.cainfo n'est pas configuré" -ForegroundColor Yellow
}

# Vérifier openssl.cafile
$opensslCaFile = php -r "echo ini_get('openssl.cafile');"
if ($opensslCaFile) {
    Write-Host "✓ openssl.cafile = $opensslCaFile" -ForegroundColor Green
} else {
    Write-Host "⚠️  openssl.cafile n'est pas configuré" -ForegroundColor Yellow
}

Write-Host ""
Write-Host "🧪 Test de connexion HTTPS..." -ForegroundColor Yellow

try {
    $testResult = php -r "echo @file_get_contents('https://www.google.com') ? 'OK' : 'ERREUR';"
    if ($testResult -eq "OK") {
        Write-Host "✓ Connexion HTTPS fonctionne !" -ForegroundColor Green
    } else {
        Write-Host "⚠️  Connexion HTTPS échouée" -ForegroundColor Yellow
    }
} catch {
    Write-Host "⚠️  Impossible de tester la connexion HTTPS" -ForegroundColor Yellow
}

Write-Host ""
Write-Host "════════════════════════════════════════════════════════════" -ForegroundColor Cyan
Write-Host "✓ Installation terminée !" -ForegroundColor Green
Write-Host ""
Write-Host "📝 Prochaines étapes:" -ForegroundColor Yellow
Write-Host "   1. Redémarrez votre serveur Laravel (Ctrl+C puis php artisan serve)" -ForegroundColor White
Write-Host "   2. Supprimez CURL_VERIFY_SSL=false de votre .env" -ForegroundColor White
Write-Host "   3. Testez Google OAuth: http://localhost:3000/inscription" -ForegroundColor White
Write-Host ""
Write-Host "════════════════════════════════════════════════════════════" -ForegroundColor Cyan
Write-Host ""
