<?php
// File: migrations/M0003_create_products_table.php
use App\core\Application;
use App\migrations\Migration;

class M0005_create_products_table extends Migration
{
    public function up()
    {
        $db = Application::$app->db;
        
        // Create products table
        $sql = "CREATE TABLE IF NOT EXISTS products (
            id SERIAL PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            description TEXT NOT NULL,
            price DECIMAL(10,2) NOT NULL,
            stock_quantity INT NOT NULL DEFAULT 0,
            category_id INT NOT NULL,
            vendor_id INT NOT NULL,
            image_path VARCHAR(255) NULL,
            status VARCHAR(50) NOT NULL DEFAULT 'active',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            deleted_at TIMESTAMP NULL,
            FOREIGN KEY (category_id) REFERENCES categories(id),
            FOREIGN KEY (vendor_id) REFERENCES users(id)
        )";
        
        $db->pdo->exec($sql);
        
        // Insert fake products for testing
        $this->insertTestData($db);
    }
    
    private function insertTestData($db)
    {
        // Create product categories first if they don't exist
        $categorySql = "INSERT INTO categories (name, parent_id) VALUES 
            ('Moroccan Pottery', NULL),
            ('Textiles', NULL),
            ('Jewelry', NULL),
            ('Spices', NULL),
            ('Home Decor', NULL)
        ON CONFLICT (name) DO NOTHING";
        
        $db->pdo->exec($categorySql);
        
        // Get category IDs for reference
        $categories = $db->pdo->query("SELECT id, name FROM categories LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);
        $categoryMap = [];
        foreach ($categories as $category) {
            $categoryMap[$category['name']] = $category['id'];
        }
        
        // Get a vendor ID (assuming role_id 2 is vendor)
        $vendorQuery = $db->pdo->query("SELECT id FROM users WHERE role_id = 2 LIMIT 1");
        $vendorId = $vendorQuery->fetch(PDO::FETCH_ASSOC)['id'] ?? 1;
        
        // Prepare product data
        $products = [
            // Moroccan Pottery products
            [
                'name' => 'Handcrafted Moroccan Ceramic Plate',
                'description' => 'Beautiful handcrafted ceramic plate with traditional Moroccan patterns. Each piece is unique and handmade by skilled artisans from Fez.',
                'price' => 39.99,
                'stock_quantity' => 25,
                'category_id' => $categoryMap['Moroccan Pottery'] ?? 1,
                'vendor_id' => $vendorId,
                'image_path' => '/uploads/products/moroccan-plate.jpg',
                'status' => 'active'
            ],
            [
                'name' => 'Blue Moroccan Ceramic Tagine',
                'description' => 'Authentic ceramic tagine pot with intricate blue patterns. Perfect for slow-cooking Moroccan dishes or as a decorative piece.',
                'price' => 64.99,
                'stock_quantity' => 15,
                'category_id' => $categoryMap['Moroccan Pottery'] ?? 1,
                'vendor_id' => $vendorId,
                'image_path' => '/uploads/products/blue-tagine.jpg',
                'status' => 'active'
            ],
            
            // Textiles products
            [
                'name' => 'Handwoven Moroccan Throw Blanket',
                'description' => 'Soft and cozy handwoven blanket made from 100% cotton. Features traditional Moroccan patterns and tassels.',
                'price' => 89.99,
                'stock_quantity' => 20,
                'category_id' => $categoryMap['Textiles'] ?? 2,
                'vendor_id' => $vendorId,
                'image_path' => '/uploads/products/moroccan-blanket.jpg',
                'status' => 'active'
            ],
            [
                'name' => 'Moroccan Berber Pillow Cover',
                'description' => 'Authentic Berber pillow cover with geometric patterns. Handmade by women artisans from the Atlas Mountains.',
                'price' => 45.99,
                'stock_quantity' => 30,
                'category_id' => $categoryMap['Textiles'] ?? 2,
                'vendor_id' => $vendorId,
                'image_path' => '/uploads/products/berber-pillow.jpg',
                'status' => 'active'
            ],
            
            // Jewelry products
            [
                'name' => 'Silver Moroccan Filigree Earrings',
                'description' => 'Elegant silver filigree earrings handcrafted by artisans in Marrakech. These lightweight earrings showcase traditional Moroccan craftsmanship.',
                'price' => 75.99,
                'stock_quantity' => 18,
                'category_id' => $categoryMap['Jewelry'] ?? 3,
                'vendor_id' => $vendorId,
                'image_path' => '/uploads/products/silver-earrings.jpg',
                'status' => 'active'
            ],
            [
                'name' => 'Berber Amber Necklace',
                'description' => 'Traditional Berber necklace featuring amber beads and silver accents. Each piece is unique and carries cultural significance.',
                'price' => 129.99,
                'stock_quantity' => 8,
                'category_id' => $categoryMap['Jewelry'] ?? 3,
                'vendor_id' => $vendorId,
                'image_path' => '/uploads/products/amber-necklace.jpg',
                'status' => 'active'
            ],
            
            // Spices products
            [
                'name' => 'Premium Moroccan Ras el Hanout Spice Blend',
                'description' => 'Authentic blend of over 20 spices including cardamom, nutmeg, cinnamon, and rose petals. Essential for Moroccan cuisine.',
                'price' => 19.99,
                'stock_quantity' => 50,
                'category_id' => $categoryMap['Spices'] ?? 4,
                'vendor_id' => $vendorId,
                'image_path' => '/uploads/products/ras-el-hanout.jpg',
                'status' => 'active'
            ],
            [
                'name' => 'Moroccan Saffron Threads',
                'description' => 'Premium quality saffron threads from the Taliouine region of Morocco. Known for its distinct flavor and aroma.',
                'price' => 24.99,
                'stock_quantity' => 35,
                'category_id' => $categoryMap['Spices'] ?? 4,
                'vendor_id' => $vendorId,
                'image_path' => '/uploads/products/saffron.jpg',
                'status' => 'active'
            ],
            
            // Home Decor products
            [
                'name' => 'Moroccan Mosaic Wall Mirror',
                'description' => 'Handcrafted wall mirror with intricate mosaic pattern frame. Each piece is meticulously assembled by artisans in Fez.',
                'price' => 149.99,
                'stock_quantity' => 10,
                'category_id' => $categoryMap['Home Decor'] ?? 5,
                'vendor_id' => $vendorId,
                'image_path' => '/uploads/products/mosaic-mirror.jpg',
                'status' => 'active'
            ],
            [
                'name' => 'Handmade Moroccan Leather Pouf',
                'description' => 'Authentic Moroccan leather pouf, handmade by skilled artisans. Perfect as an ottoman, extra seating, or decorative piece.',
                'price' => 119.99,
                'stock_quantity' => 12,
                'category_id' => $categoryMap['Home Decor'] ?? 5,
                'vendor_id' => $vendorId,
                'image_path' => '/uploads/products/leather-pouf.jpg',
                'status' => 'active'
            ]
        ];
        
        // Insert products
        $insertSql = "INSERT INTO products (name, description, price, stock_quantity, category_id, vendor_id, image_path, status) VALUES ";
        $values = [];
        $params = [];
        
        foreach ($products as $index => $product) {
            $paramPrefix = "p" . $index;
            $values[] = "(:{$paramPrefix}_name, :{$paramPrefix}_desc, :{$paramPrefix}_price, :{$paramPrefix}_stock, 
                        :{$paramPrefix}_category, :{$paramPrefix}_vendor, :{$paramPrefix}_image, :{$paramPrefix}_status)";
            
            $params["{$paramPrefix}_name"] = $product['name'];
            $params["{$paramPrefix}_desc"] = $product['description'];
            $params["{$paramPrefix}_price"] = $product['price'];
            $params["{$paramPrefix}_stock"] = $product['stock_quantity'];
            $params["{$paramPrefix}_category"] = $product['category_id'];
            $params["{$paramPrefix}_vendor"] = $product['vendor_id'];
            $params["{$paramPrefix}_image"] = $product['image_path'];
            $params["{$paramPrefix}_status"] = $product['status'];
        }
        
        $insertSql .= implode(", ", $values);
        $stmt = $db->pdo->prepare($insertSql);
        
        foreach ($params as $key => $value) {
            $stmt->bindValue(":{$key}", $value);
        }
        
        $stmt->execute();
    }
    
    public function down()
    {
        $db = Application::$app->db;
        $sql = "DROP TABLE IF EXISTS products;";
        $db->pdo->exec($sql);
    }
}