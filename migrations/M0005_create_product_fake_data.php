<?php
// File: migrations/M0005_create_product_fake_data.php
use App\core\Application;
use App\migrations\Migration;

class M0005_create_product_fake_data extends Migration
{
    public function up()
    {
        $db = Application::$app->db;
        
        // First, let's make sure we have some vendors
        $vendorsData = [
            ['user_id' => 1, 'store_name' => 'Moroccan Treasures', 'description' => 'Authentic handcrafted Moroccan products'],
            ['user_id' => 2, 'store_name' => 'Tech Haven', 'description' => 'Latest technology and gadgets'],
            ['user_id' => 3, 'store_name' => 'Fashion Forward', 'description' => 'Modern apparel and accessories']
        ];
        
        // Check if vendors table exists and has data
        $vendorCheck = $db->pdo->query("SELECT COUNT(*) FROM vendors");
        if ($vendorCheck && $vendorCheck->fetchColumn() == 0) {
            foreach ($vendorsData as $vendor) {
                $db->pdo->exec("INSERT INTO vendors (user_id, store_name, description, status) 
                               VALUES ({$vendor['user_id']}, '{$vendor['store_name']}', '{$vendor['description']}', 'active')");
            }
            echo "Added sample vendors\n";
        }
        
        // Get category IDs
        $categoryQuery = $db->pdo->query("SELECT id, name FROM categories WHERE deleted_at IS NULL");
        $categories = [];
        if ($categoryQuery) {
            while ($row = $categoryQuery->fetch(\PDO::FETCH_ASSOC)) {
                $categories[$row['name']] = $row['id'];
            }
        }
        
        // If no categories found, create some
        if (empty($categories)) {
            $db->pdo->exec("INSERT INTO categories (name) VALUES ('Electronics'), ('Clothing'), ('Home & Garden'), ('Books'), ('Beauty & Health')");
            
            // Fetch the newly created categories
            $categoryQuery = $db->pdo->query("SELECT id, name FROM categories WHERE deleted_at IS NULL");
            while ($row = $categoryQuery->fetch(\PDO::FETCH_ASSOC)) {
                $categories[$row['name']] = $row['id'];
            }
            echo "Added sample categories\n";
        }
        
        // Get vendor IDs
        $vendorQuery = $db->pdo->query("SELECT id FROM vendors WHERE deleted_at IS NULL");
        $vendorIds = [];
        if ($vendorQuery) {
            while ($row = $vendorQuery->fetch(\PDO::FETCH_ASSOC)) {
                $vendorIds[] = $row['id'];
            }
        }
        
        // If no vendors found, we need to handle this
        if (empty($vendorIds)) {
            echo "Warning: No vendors found. Using default vendor ID 1.\n";
            $vendorIds = [1];
        }
        
        // Fake products data
        $productsData = [
            // Electronics
            [
                'name' => 'Smartphone X3000',
                'description' => 'Latest model with 6.5" display, 128GB storage, and 48MP camera. Features all-day battery life and water resistance.',
                'price' => 3499.99,
                'stock_quantity' => 25,
                'category_id' => $categories['Electronics'] ?? $categories[array_rand($categories)],
                'vendor_id' => $vendorIds[array_rand($vendorIds)],
                'image_path' => '/assets/img/products/electronics/phone1.jpg'
            ],
            [
                'name' => 'Wireless Headphones',
                'description' => 'Premium noise-cancelling headphones with 30-hour battery life. Perfect for travel or office use with comfortable ear cushions.',
                'price' => 899.99,
                'stock_quantity' => 40,
                'category_id' => $categories['Electronics'] ?? $categories[array_rand($categories)],
                'vendor_id' => $vendorIds[array_rand($vendorIds)],
                'image_path' => '/assets/img/products/electronics/headphones1.jpg'
            ],
            [
                'name' => 'Smart Watch',
                'description' => 'Track your health and stay connected with this stylish smartwatch. Features heart rate monitoring, GPS, and app notifications.',
                'price' => 1299.99,
                'stock_quantity' => 30,
                'category_id' => $categories['Electronics'] ?? $categories[array_rand($categories)],
                'vendor_id' => $vendorIds[array_rand($vendorIds)],
                'image_path' => '/assets/img/products/electronics/watch1.jpg'
            ],
            [
                'name' => 'Laptop Pro',
                'description' => 'Powerful laptop with 16GB RAM, 512GB SSD, and dedicated graphics. Perfect for professionals and casual gaming.',
                'price' => 8999.99,
                'stock_quantity' => 15,
                'category_id' => $categories['Electronics'] ?? $categories[array_rand($categories)],
                'vendor_id' => $vendorIds[array_rand($vendorIds)],
                'image_path' => '/assets/img/products/electronics/laptop1.jpg'
            ],
            
            // Clothing
            [
                'name' => 'Moroccan Kaftan',
                'description' => 'Authentic Moroccan kaftan with intricate embroidery. Perfect for special occasions or elegant home wear.',
                'price' => 1999.99,
                'stock_quantity' => 20,
                'category_id' => $categories['Clothing'] ?? $categories[array_rand($categories)],
                'vendor_id' => $vendorIds[array_rand($vendorIds)],
                'image_path' => '/assets/img/products/clothing/kaftan1.jpg'
            ],
            [
                'name' => 'Leather Jacket',
                'description' => 'Classic leather jacket with modern styling. Features durable construction and comfortable fit.',
                'price' => 1499.99,
                'stock_quantity' => 12,
                'category_id' => $categories['Clothing'] ?? $categories[array_rand($categories)],
                'vendor_id' => $vendorIds[array_rand($vendorIds)],
                'image_path' => '/assets/img/products/clothing/jacket1.jpg'
            ],
            [
                'name' => 'Summer Dress',
                'description' => 'Light and breezy summer dress in floral pattern. Perfect for warm days and casual outings.',
                'price' => 599.99,
                'stock_quantity' => 35,
                'category_id' => $categories['Clothing'] ?? $categories[array_rand($categories)],
                'vendor_id' => $vendorIds[array_rand($vendorIds)],
                'image_path' => '/assets/img/products/clothing/dress1.jpg'
            ],
            [
                'name' => 'Formal Suit',
                'description' => 'Tailored formal suit in classic navy blue. Includes jacket and trousers with modern slim fit.',
                'price' => 2499.99,
                'stock_quantity' => 10,
                'category_id' => $categories['Clothing'] ?? $categories[array_rand($categories)],
                'vendor_id' => $vendorIds[array_rand($vendorIds)],
                'image_path' => '/assets/img/products/clothing/suit1.jpg'
            ],
            
            // Home & Garden
            [
                'name' => 'Moroccan Tea Set',
                'description' => 'Traditional Moroccan tea set with ornate metalwork. Includes teapot, tray, and glasses for authentic tea service.',
                'price' => 799.99,
                'stock_quantity' => 15,
                'category_id' => $categories['Home & Garden'] ?? $categories[array_rand($categories)],
                'vendor_id' => $vendorIds[array_rand($vendorIds)],
                'image_path' => '/assets/img/products/home/teaset1.jpg'
            ],
            [
                'name' => 'Hand-woven Berber Rug',
                'description' => 'Authentic Berber rug handmade by skilled artisans. Features traditional patterns in natural wool.',
                'price' => 3999.99,
                'stock_quantity' => 8,
                'category_id' => $categories['Home & Garden'] ?? $categories[array_rand($categories)],
                'vendor_id' => $vendorIds[array_rand($vendorIds)],
                'image_path' => '/assets/img/products/home/rug1.jpg'
            ],
            [
                'name' => 'Ceramic Tagine',
                'description' => 'Handcrafted ceramic tagine for authentic Moroccan cooking. Beautiful enough to use as a serving dish.',
                'price' => 449.99,
                'stock_quantity' => 22,
                'category_id' => $categories['Home & Garden'] ?? $categories[array_rand($categories)],
                'vendor_id' => $vendorIds[array_rand($vendorIds)],
                'image_path' => '/assets/img/products/home/tagine1.jpg'
            ],
            [
                'name' => 'Decorative Lantern',
                'description' => 'Moroccan-style metal lantern with intricate cutout designs. Creates beautiful light patterns when lit.',
                'price' => 349.99,
                'stock_quantity' => 30,
                'category_id' => $categories['Home & Garden'] ?? $categories[array_rand($categories)],
                'vendor_id' => $vendorIds[array_rand($vendorIds)],
                'image_path' => '/assets/img/products/home/lantern1.jpg'
            ],
            
            // Books
            [
                'name' => 'Moroccan Cuisine Cookbook',
                'description' => 'Comprehensive guide to Moroccan cooking with 100+ authentic recipes. Includes techniques and ingredient information.',
                'price' => 249.99,
                'stock_quantity' => 45,
                'category_id' => $categories['Books'] ?? $categories[array_rand($categories)],
                'vendor_id' => $vendorIds[array_rand($vendorIds)],
                'image_path' => '/assets/img/products/books/cookbook1.jpg'
            ],
            [
                'name' => 'History of Morocco',
                'description' => 'Detailed historical account of Morocco from ancient times to the present. Features maps and historical photographs.',
                'price' => 299.99,
                'stock_quantity' => 25,
                'category_id' => $categories['Books'] ?? $categories[array_rand($categories)],
                'vendor_id' => $vendorIds[array_rand($vendorIds)],
                'image_path' => '/assets/img/products/books/history1.jpg'
            ],
            [
                'name' => 'Modern Fiction Bestseller',
                'description' => 'Award-winning novel exploring themes of identity and belonging. A compelling story set across multiple continents.',
                'price' => 179.99,
                'stock_quantity' => 50,
                'category_id' => $categories['Books'] ?? $categories[array_rand($categories)],
                'vendor_id' => $vendorIds[array_rand($vendorIds)],
                'image_path' => '/assets/img/products/books/fiction1.jpg'
            ],
            [
                'name' => 'Business Strategy Guide',
                'description' => 'Practical guide to modern business strategies with case studies and actionable advice from industry leaders.',
                'price' => 399.99,
                'stock_quantity' => 20,
                'category_id' => $categories['Books'] ?? $categories[array_rand($categories)],
                'vendor_id' => $vendorIds[array_rand($vendorIds)],
                'image_path' => '/assets/img/products/books/business1.jpg'
            ],
            
            // Beauty & Health
            [
                'name' => 'Argan Oil Set',
                'description' => 'Pure Moroccan argan oil set for hair and skin. Includes facial oil, hair treatment, and body lotion.',
                'price' => 599.99,
                'stock_quantity' => 40,
                'category_id' => $categories['Beauty & Health'] ?? $categories[array_rand($categories)],
                'vendor_id' => $vendorIds[array_rand($vendorIds)],
                'image_path' => '/assets/img/products/beauty/arganoil1.jpg'
            ],
            [
                'name' => 'Moroccan Hammam Set',
                'description' => 'Complete hammam experience set with black soap, kessa glove, and rhassoul clay mask for traditional Moroccan spa treatment.',
                'price' => 449.99,
                'stock_quantity' => 25,
                'category_id' => $categories['Beauty & Health'] ?? $categories[array_rand($categories)],
                'vendor_id' => $vendorIds[array_rand($vendorIds)],
                'image_path' => '/assets/img/products/beauty/hammam1.jpg'
            ],
            [
                'name' => 'Rose Water Toner',
                'description' => 'Natural rose water facial toner made from Moroccan roses. Hydrates and refreshes skin with natural fragrance.',
                'price' => 149.99,
                'stock_quantity' => 60,
                'category_id' => $categories['Beauty & Health'] ?? $categories[array_rand($categories)],
                'vendor_id' => $vendorIds[array_rand($vendorIds)],
                'image_path' => '/assets/img/products/beauty/rosewater1.jpg'
            ],
            [
                'name' => 'Essential Oil Collection',
                'description' => 'Set of 6 essential oils including lavender, eucalyptus, and orange. Perfect for aromatherapy and home fragrance.',
                'price' => 349.99,
                'stock_quantity' => 30,
                'category_id' => $categories['Beauty & Health'] ?? $categories[array_rand($categories)],
                'vendor_id' => $vendorIds[array_rand($vendorIds)],
                'image_path' => '/assets/img/products/beauty/essentialoils1.jpg'
            ],
        ];
        
        // Check if products table has data already
        $productCheck = $db->pdo->query("SELECT COUNT(*) FROM products");
        if ($productCheck && $productCheck->fetchColumn() > 0) {
            echo "Products table already has data. Skipping fake data insertion.\n";
            return;
        }
        
        // Insert products
        foreach ($productsData as $product) {
            $stmt = $db->pdo->prepare("
                INSERT INTO products (name, description, price, stock_quantity, category_id, vendor_id, image_path, status) 
                VALUES (:name, :description, :price, :stock_quantity, :category_id, :vendor_id, :image_path, 'active')
            ");
            
            $stmt->bindParam(':name', $product['name']);
            $stmt->bindParam(':description', $product['description']);
            $stmt->bindParam(':price', $product['price']);
            $stmt->bindParam(':stock_quantity', $product['stock_quantity']);
            $stmt->bindParam(':category_id', $product['category_id']);
            $stmt->bindParam(':vendor_id', $product['vendor_id']);
            $stmt->bindParam(':image_path', $product['image_path']);
            
            $stmt->execute();
        }
        
        echo "Added " . count($productsData) . " sample products\n";
    }
    
    public function down()
    {
        $db = Application::$app->db;
        
        // Remove the fake products
        $db->pdo->exec("DELETE FROM products WHERE 1=1");
        
        echo "Removed all products from database\n";
    }
}