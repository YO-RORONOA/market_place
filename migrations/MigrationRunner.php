<?php

namespace App\migrations;

use App\core\Database;
use App\migrations;




class MigrationRunner
{
    private Database $db;
    private string $migrationsDir;
    
    public function __construct(Database $db, string $migrationsDir)
    {
        $this->db = $db;
        $this->migrationsDir = $migrationsDir;
    }
    
    public function createMigrationsTable()
    {
        $this->db->pdo->exec("CREATE TABLE IF NOT EXISTS migrations (
            id SERIAL PRIMARY KEY,
            migration VARCHAR(255),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )");
    }
    
    public function getAppliedMigrations(): array
    {
        $statement = $this->db->pdo->prepare('SELECT migration FROM migrations');
        $statement->execute();
        return $statement->fetchAll(\PDO::FETCH_COLUMN);
    }
    
    public function applyMigrations()
    {
        $this->createMigrationsTable();
        $appliedMigrations = $this->getAppliedMigrations();
        
        $files = scandir($this->migrationsDir);
        $toApplyMigrations = array_diff($files, $appliedMigrations);
        $newMigrations = [];
        
        foreach ($toApplyMigrations as $migration) {
            if ($migration === '.' || $migration === '..') {
                continue;
            }
            
            require_once $this->migrationsDir . '/' . $migration;
            $className = pathinfo($migration, PATHINFO_FILENAME);
            $instance = new $className();
            
            $this->log("Applying migration $migration");
            $instance->up();
            $this->log("Applied $migration");
            
            $newMigrations[] = $migration;
        }
        
        if (!empty($newMigrations)) {
            $this->saveMigrations($newMigrations);
        } else {
            $this->log('All migrations are applied');
        }
    }
    
    public function saveMigrations(array $migrations)
    {
        $str = implode(",", array_map(fn($m) => "('$m')", $migrations));
        $statement = $this->db->pdo->prepare("INSERT INTO migrations (migration) VALUES $str");
        $statement->execute();
    }
    
    public function rollback(int $steps = 1)
    {
        $appliedMigrations = $this->getAppliedMigrations();
        $migrationsToRollback = array_slice(array_reverse($appliedMigrations), 0, $steps);
        
        foreach ($migrationsToRollback as $migration) {
            require_once $this->migrationsDir . '/' . $migration;
            $className = pathinfo($migration, PATHINFO_FILENAME);
            $instance = new $className();
            
            $this->log("Rolling back migration $migration");
            $instance->down();
            $this->log("Rolled back $migration");
            
            $statement = $this->db->pdo->prepare("DELETE FROM migrations WHERE migration = :migration");
            $statement->bindValue(':migration', $migration);
            $statement->execute();
        }
    }
    
    private function log(string $message)
    {
        echo '[' . date('Y-m-d H:i:s') . '] - ' . $message . PHP_EOL;
    }
}