<?php
// File: migrations/M0005_create_orders_table.php
use App\core\Application;
use App\migrations\Migration;

class M0005_create_orders_table extends Migration
{
    public function up()
    {
        $db = Application::$app->db;
        
        $sql = "CREATE TABLE IF NOT EXISTS orders (
            id SERIAL PRIMARY KEY,
            user_id INT NOT NULL,
            total_amount DECIMAL(10,2) NOT NULL,
            status VARCHAR(50) NOT NULL DEFAULT 'pending',
            payment_intent_id VARCHAR(255) NULL,
            payment_method VARCHAR(50) NOT NULL DEFAULT 'stripe',
            shipping_address TEXT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            deleted_at TIMESTAMP NULL,
            FOREIGN KEY (user_id) REFERENCES users(id)
        )";
        
        $db->pdo->exec($sql);
        
        $sql = "CREATE TABLE IF NOT EXISTS order_items (
            id SERIAL PRIMARY KEY,
            order_id INT NOT NULL,
            product_id INT NOT NULL,
            quantity INT NOT NULL,
            price DECIMAL(10,2) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (order_id) REFERENCES orders(id),
            FOREIGN KEY (product_id) REFERENCES products(id)
        )";
        
        $db->pdo->exec($sql);
    }
    
    public function down()
    {
        $db = Application::$app->db;
        
        $sql = "DROP TABLE IF EXISTS order_items;";
        $db->pdo->exec($sql);
        
        $sql = "DROP TABLE IF EXISTS orders;";
        $db->pdo->exec($sql);
    }
}