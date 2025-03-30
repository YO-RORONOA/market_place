<?php

use App\core\Application;
use App\migrations\Migration;


class M0003_create_products_table extends Migration
{
    public function up(): void  
    {
        $db = Application::$app->db;

        $sql = "CREATE TABLE IF NOT EXISTS products(
        id SERIAL PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        description TEXT,
        price DECIMAL (10,2) NOT NULL,
        stock_quantity INT NOT NULL DEFAULT 0,
        category_id INT NOT NULL,
        vendor_id INT NOT NULL,
        image_path VARCHAR(255),
        status VARCHAR(50) NOT NULL DEFAULT 'active',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        deleted_at TIMESTAMP NULL,
        FOREIGN KEY (category_id) REFERENCES categories(id),
        FOREIGN KEY (vendor_id) REFERENCES users(id)
        )";
        $db->pdo->exec($sql);
    }


    public function down(): void
    {
        $db = Application::$app->db;

        $sql = "DROP TABLE IF EXISTS products;";
        $db->pdo->exec($sql);
    }
    
}