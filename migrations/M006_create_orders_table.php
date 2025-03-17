<?php
// File: migrations/M0006_update_orders_table.php
use App\core\Application;
use App\migrations\Migration;

class M0006_update_orders_table extends Migration
{
    public function up()
    {
        $db = Application::$app->db;
        
        // First, check if the orders table exists
        $checkTableSql = "SELECT EXISTS (
            SELECT FROM information_schema.tables 
            WHERE table_name = 'orders'
        )";
        
        $tableExists = $db->pdo->query($checkTableSql)->fetchColumn();
        
        if (!$tableExists) {
            // Create orders table if it doesn't exist
            $createTableSql = "CREATE TABLE IF NOT EXISTS orders (
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
            
            $db->pdo->exec($createTableSql);
        } else {
            // Add payment_intent_id column if it doesn't exist
            $checkColumnSql = "SELECT EXISTS (
                SELECT FROM information_schema.columns 
                WHERE table_name = 'orders' AND column_name = 'payment_intent_id'
            )";
            
            $columnExists = $db->pdo->query($checkColumnSql)->fetchColumn();
            
            if (!$columnExists) {
                $addColumnSql = "ALTER TABLE orders 
                                ADD COLUMN payment_intent_id VARCHAR(255) NULL,
                                ADD COLUMN payment_method VARCHAR(50) NOT NULL DEFAULT 'stripe'";
                $db->pdo->exec($addColumnSql);
            }
        }
        
        // Create order_items table if it doesn't exist
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
        
        // Drop order_items table first (due to foreign key constraints)
        $db->pdo->exec("DROP TABLE IF EXISTS order_items");
        
        // Drop columns from orders table
        $checkColumnSql = "SELECT EXISTS (
            SELECT FROM information_schema.columns 
            WHERE table_name = 'orders' AND column_name = 'payment_intent_id'
        )";
        
        $columnExists = $db->pdo->query($checkColumnSql)->fetchColumn();
        
        if ($columnExists) {
            $db->pdo->exec("ALTER TABLE orders DROP COLUMN payment_intent_id, DROP COLUMN payment_method");
        }
    }
}