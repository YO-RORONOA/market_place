<?php
use App\core\Application;
use App\migrations\Migration;

class M0002_create_vendors_table extends Migration
{
    public function up()
    {
        $db = Application::$app->db;
        
        $sql = "CREATE TABLE IF NOT EXISTS vendors (
            id SERIAL PRIMARY KEY,
            user_id INT NOT NULL UNIQUE,
            store_name VARCHAR(255) NOT NULL,
            description TEXT,
            logo_path VARCHAR(255),
            status VARCHAR(50) NOT NULL DEFAULT 'active',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            deleted_at TIMESTAMP NULL,
            FOREIGN KEY (user_id) REFERENCES users(id)
        )";
        
        $db->pdo->exec($sql);
    }
    
    public function down()
    {
        $db = Application::$app->db;
        $sql = "DROP TABLE IF EXISTS vendors;";
        $db->pdo->exec($sql);
    }
}