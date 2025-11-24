<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CreateDatabase extends Command
{
    protected $signature = 'db:create {name?}';
    protected $description = 'Create a new PostgreSQL database';

    public function handle()
    {
        $dbName = $this->argument('name') ?? config('database.connections.pgsql.database');
        
        $this->info("Tentative de création de la base de données: $dbName");
        
        try {
            // Connexion temporaire à la base postgres
            $pdo = new \PDO(
                sprintf(
                    'pgsql:host=%s;port=%s;dbname=postgres',
                    config('database.connections.pgsql.host'),
                    config('database.connections.pgsql.port')
                ),
                config('database.connections.pgsql.username'),
                config('database.connections.pgsql.password')
            );
            
            // Vérifier si la base existe
            $stmt = $pdo->query("SELECT 1 FROM pg_database WHERE datname = '$dbName'");
            
            if ($stmt->rowCount() === 0) {
                $pdo->exec("CREATE DATABASE $dbName");
                $this->info("✅ Base de données '$dbName' créée avec succès!");
            } else {
                $this->info("ℹ️  La base de données '$dbName' existe déjà.");
            }
            
            return 0;
            
        } catch (\PDOException $e) {
            $this->error("❌ Erreur: " . $e->getMessage());
            $this->newLine();
            $this->warn("Utilisez pgAdmin pour créer la base manuellement:");
            $this->line("1. Ouvrez pgAdmin");
            $this->line("2. Clic droit sur 'Databases' > Create > Database");
            $this->line("3. Nom: $dbName");
            
            return 1;
        }
    }
}
