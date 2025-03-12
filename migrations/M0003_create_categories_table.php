<?php

use App\core\Application;
use App\migrations\Migration;



class M0003_create_categories_table extends Migration
{

    public function up(): void
    {
        $db = Application::$app->db;

        $sql = "CREATE TABLE IF NOT EXISTS categories(
        id SERIAL PRIMARY KEY,
        name VARCHAR(255) NOT NULL UNIQUE,
        parent_id INT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        deleted_at TIMESTAMP NULL,
        FOREIGN KEY (parent_id) REFERENCES categories(id)
        )";

        $db->pdo->exec($sql);

        $sql = "INSERT INTO categories (name) VALUES
        ('Electronics'),
        ('clothing'),
        ('Home & Garden'),
        ('Books'),
        ('Beauty & Health')";
        
        $db->pdo->exec($sql);
    }


    public function down() : void {
        $db = Application::$app->db;
        $sql = "DROP TABLE IF EXISTS categories;";
        $db->pdo->exec($sql);
        
    }
}