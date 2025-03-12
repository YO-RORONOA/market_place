<?php
// drop_tables.php

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

$pdo = new PDO($config['db']['dsn'], $config['db']['user'], $config['db']['password']);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Disable foreign key checks
$pdo->exec("SET session_replication_role = 'replica';");

// Drop all tables
$tables = ['products', 'categories', 'password_resets', 'users', 'roles', 'migrations'];

foreach ($tables as $table) {
    $pdo->exec("DROP TABLE IF EXISTS $table CASCADE;");
    echo "Dropped table $table" . PHP_EOL;
}

// Re-enable foreign key checks
$pdo->exec("SET session_replication_role = 'origin';");

echo "All tables have been dropped." . PHP_EOL;