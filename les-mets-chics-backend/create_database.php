<?php

/**
 * Script pour créer la base de données PostgreSQL
 * Exécutez avec: php create_database.php
 */

$host = '127.0.0.1';
$port = '5432';
$dbname = 'les_mets_chics';
$username = 'postgres';
$password = 'root'; // Mettez votre mot de passe PostgreSQL ici

try {
    // Connexion à la base postgres par défaut
    $pdo = new PDO("pgsql:host=$host;port=$port;dbname=postgres", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Vérifier si la base existe déjà
    $stmt = $pdo->query("SELECT 1 FROM pg_database WHERE datname = '$dbname'");
    
    if ($stmt->rowCount() === 0) {
        // Créer la base de données
        $pdo->exec("CREATE DATABASE $dbname");
        echo "✅ Base de données '$dbname' créée avec succès!\n";
    } else {
        echo "ℹ️  La base de données '$dbname' existe déjà.\n";
    }
    
} catch (PDOException $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
    echo "\n";
    echo "📝 Instructions:\n";
    echo "1. Assurez-vous que PostgreSQL est démarré\n";
    echo "2. Modifiez le mot de passe dans ce script (ligne 12)\n";
    echo "3. Ou créez la base manuellement avec pgAdmin:\n";
    echo "   - Ouvrez pgAdmin\n";
    echo "   - Clic droit sur 'Databases' > Create > Database\n";
    echo "   - Nom: les_mets_chics\n";
    exit(1);
}
