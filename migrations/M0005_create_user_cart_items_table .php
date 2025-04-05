<?php
use App\core\Application;
use App\migrations\Migration;

class M0005_create_user_cart_items_table extends Migration
{
    public function up()
    {
        $db = Application::$app->db;
        
        $checkTableSql = "SELECT EXISTS (
            SELECT FROM information_schema.tables 
            WHERE table_name = 'user_cart_items'
        )";
        
        $tableExists = $db->pdo->query($checkTableSql)->fetchColumn();
        
        if (!$tableExists) {
            $sql = "CREATE TABLE IF NOT EXISTS user_cart_items (
                id SERIAL PRIMARY KEY,
                user_id INT NOT NULL,
                product_id INT NOT NULL,
                quantity INT NOT NULL DEFAULT 1,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
                FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
                UNIQUE(user_id, product_id)
            )";
            
            $db->pdo->exec($sql);
            echo "Created user_cart_items table." . PHP_EOL;
        } else {
            echo "user_cart_items table already exists, skipping creation." . PHP_EOL;
        }
    }
    
    public function down()
    {
        $db = Application::$app->db;
        
        $sql = "DROP TABLE IF EXISTS user_cart_items";
        $db->pdo->exec($sql);
        
        echo "Dropped user_cart_items table." . PHP_EOL;
    }
}