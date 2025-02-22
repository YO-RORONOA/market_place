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
        $newMmigrations = [];
        $this->createMigratingTable();
        $appliedMigrations = $this->getAppliedMigrations();

        $files = scandir(Application::$ROOT_DIR.'/migrations');
        $toApplyMigrations = array_diff($files, $appliedMigrations);
        foreach ($toApplyMigrations as $migration) {
            if($migration  === '.' || $migration === '..') {
                continue;
            }

            require_once Application::$ROOT_DIR.'/migrations/'.$migration;
            $className = pathinfo($migration, PATHINFO_FILENAME);
            $instance = new $className();
            $this->log("Alying migration $migration".PHP_EOL);
            $instance->up();
            $this->log("Applied $migration".PHP_EOL);
            $newMmigrations[] = $migration;

    }

    if(!empty($newMmigrations)) {
        $this->saveMigrations($newMmigrations);
    }
    else{
        $this->log("All migrations are applied");
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

         public function saveMigrations(array $migrations)
         {
            $str = implode(",",array_map(fn($m) => "('$m')", $migrations));
            

            $statment = $this->pdo->prepare("INSERT INTO migrations (migration) VALUES
            $str");
            $statment->execute();
         }


         protected function log($message)
         {
            echo '['. date('Y-m-d H:i:s') .'] - '. $message . PHP_EOL;
         }

}

  
