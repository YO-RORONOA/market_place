<?php
// File: core/Database.php (modified)

namespace App\core;

use App\core\exception\DatabaseException;

class Database
{
    public \PDO $pdo;

    public function __construct(array $config)
    {
        $dsn = $config['dsn'] ?? '';
        $user = $config['user'] ?? '';
        $password = $config['password'] ?? '';
        
        try {
            $this->pdo = new \PDO($dsn, $user, $password);
            $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $e) {
            throw new DatabaseException(
                'Database connection failed: ' . $e->getMessage(),
                (int)$e->getCode(),
                $e
            );
        }
    }


    public function execute(string $sql, array $params = []): \PDOStatement
    {
        try {
            $statement = $this->pdo->prepare($sql);
            $statement->execute($params);
            return $statement;
        } catch (\PDOException $e) {
            throw new DatabaseException(
                'Database query failed: ' . $e->getMessage(),
                (int)$e->getCode(),
                $e
            );
        }
    }

    public function beginTransaction(): bool
    {
        try {
            return $this->pdo->beginTransaction();
        } catch (\PDOException $e) {
            throw new DatabaseException(
                'Failed to begin transaction: ' . $e->getMessage(),
                (int)$e->getCode(),
                $e
            );
        }
    }

    public function commit(): bool
    {
        try {
            return $this->pdo->commit();
        } catch (\PDOException $e) {
            throw new DatabaseException(
                'Failed to commit transaction: ' . $e->getMessage(),
                (int)$e->getCode(),
                $e
            );
        }
    }


    public function rollBack(): bool
    {
        try {
            return $this->pdo->rollBack();
        } catch (\PDOException $e) {
            throw new DatabaseException(
                'Failed to rollback transaction: ' . $e->getMessage(),
                (int)$e->getCode(),
                $e
            );
        }
    }


    public function createMigratingTable()
    {
        try {
            $this->pdo->exec("CREATE TABLE IF NOT EXISTS migrations (
                id SERIAL PRIMARY KEY,
                migration VARCHAR(255),
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )");
        } catch (\PDOException $e) {
            throw new DatabaseException(
                'Failed to create migrations table: ' . $e->getMessage(),
                (int)$e->getCode(),
                $e
            );
        }
    }

    public function getAppliedMigrations(): array
    {
        try {
            $statement = $this->pdo->prepare('SELECT migration FROM migrations');
            $statement->execute();
            return $statement->fetchAll(\PDO::FETCH_COLUMN);
        } catch (\PDOException $e) {
            throw new DatabaseException(
                'Failed to get applied migrations: ' . $e->getMessage(),
                (int)$e->getCode(),
                $e
            );
        }
    }


    public function saveMigrations(array $migrations): void
    {
        try {
            $str = implode(",", array_map(fn($m) => "('$m')", $migrations));
            $statement = $this->pdo->prepare("INSERT INTO migrations (migration) VALUES $str");
            $statement->execute();
        } catch (\PDOException $e) {
            throw new DatabaseException(
                'Failed to save migrations: ' . $e->getMessage(),
                (int)$e->getCode(),
                $e
            );
        }
    }


    public function applyMigration(): void
    {
        $newMigrations = [];
        
        try {
            $this->createMigratingTable();
            $appliedMigrations = $this->getAppliedMigrations();

            $files = scandir(Application::$ROOT_DIR.'/migrations');
            $toApplyMigrations = array_diff($files, $appliedMigrations);
            
            foreach ($toApplyMigrations as $migration) {
                if ($migration === '.' || $migration === '..') {
                    continue;
                }

                require_once Application::$ROOT_DIR.'/migrations/'.$migration;
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
                $this->log("All migrations are applied");
            }
        } catch (\Exception $e) {
            throw new DatabaseException(
                'Failed to apply migrations: ' . $e->getMessage(),
                (int)$e->getCode(),
                $e
            );
        }
    }


    protected function log(string $message): void
    {
        echo '['. date('Y-m-d H:i:s') .'] - '. $message . PHP_EOL;
    }
}