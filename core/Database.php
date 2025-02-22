<?php

namespace App\core;


class Database
{
    public \PDO $pdo;

    public function __construct(array $config)
    {
        $dsn = $config['dsn'] ?? '';
        $user = $config['user'] ?? '';
        $password = $config['password'] ?? '';
        $this->pdo = new \PDO($dsn, $user, $password);
        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }



    public function applyMigration()
    {
        $this->createMigratingTable();
        $appliedMigrations = $this->getAppliedMigrations();

        $files = scandir(Application::$ROOT_DIR.'/migrations');
        $toApplyMigrations = array_diff($files, $appliedMigrations);
        foreach ($toApplyMigrations as $migration) {
            if($migration  === '.' || $migration === '..') {
                continue;
            }


    }

    }
public function createMigratingTable()
{
    $this->pdo->exec("CREATE TABLE IF NOT EXISTS migrations (
        id SERIAL PRIMARY KEY,
        migration VARCHAR(255),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");
}

public function getAppliedMigrations()
         {
            $statement = $this->pdo->prepare('SELECT migration FROM migrations');
            $statement->execute();
            return $statement->fetchAll(\PDO::FETCH_COLUMN);
         }



}


