<?php
/**
 * User: TheCodeholic
 * Date: 7/7/2020
 * Time: 9:57 AM
 */

 use App\controllers\SiteController;
 use App\core\Application;
 use App\controllers\AuthController;
 use App\migrations\MigrationRunner;

require_once __DIR__.'/vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();



$config = [
    'db' => [
        'dsn' => $_ENV['DB_DSN'],
        'user' => $_ENV['DB_USER'],
        'password' => $_ENV['DB_PASSWORD'],
    ]
    ];
    
$app = new Application(__DIR__, $config);

$migrationRunner = new MigrationRunner($app->db, __DIR__.'/migrations');



$migrationRunner->applyMigrations();