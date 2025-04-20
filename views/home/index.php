<?php
/**
 * Dynamic home page for YOU/Market
 * This would replace the hardcoded content in views/home/index.php
 */

use App\repositories\ProductRepository;
use App\repositories\CategoryRepository;

// Initialize repositories to fetch dynamic data
$productRepository = new ProductRepository();
$categoryRepository = new CategoryRepository();

// Get featured/popular products (assuming we can filter by some criteria)
$newArrivals = $productRepository->findAll(['status' => 'active'], false, 'created_at DESC', 8);
$popularProducts = $productRepository->findAll(['status' => 'active'], false, 'created_at DESC', 5);

// Get product categories
$categories = $categoryRepository->getMainCategories();

// Get featured categories (you might want to add a "featured" field to your categories table)
$featuredCategories = array_slice($categories, 0, 4);
?>
<!-- Hero Banner Section -->
<div class="relative rounded-lg overflow-hidden mb-8 shadow-sm">
  <div class="aspect-w-16 aspect-h-7 relative">
    <img src="/assets/images/heroBanner.jpg"  alt="Special Deals" class="w-full h-auto object-cover rounded-lg" 
         >
    <div class="absolute inset-0 bg-gradient-to-r from-accent-navy/70 to-transparent flex flex-col justify-center p-8 md:p-12">
      <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold text-white mb-2">Best Deals Online</h1>
      <h2 class="text-2xl md:text-3xl lg:text-4xl font-bold text-white mb-4">LATEST TECHNOLOGY</h2>
      <p class="text-white text-lg md:text-xl mb-8">UP TO 40% OFF</p>
      <a href="/products" class="bg-accent-ochre hover:bg-accent-terracotta text-white font-medium py-2 px-6 rounded-md inline-block transition w-max">
        Shop Now
      </a>
    </div>
  </div>
</div>

<!-- Benefits Bar -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-10">
  <div class="bg-white p-4 rounded-lg shadow-sm flex items-center">
    <div class="p-3 rounded-full bg-accent-teal/10 mr-3">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-accent-teal" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
      </svg>
    </div>
    <div>
      <h3 class="font-medium text-accent-navy">24Hr Delivery</h3>
      <p class="text-xs text-gray-500">For selected metropolitan areas</p>
    </div>
  </div>
  
  <div class="bg-white p-4 rounded-lg shadow-sm flex items-center">
    <div class="p-3 rounded-full bg-accent-ceramicblue/10 mr-3">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-accent-ceramicblue" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
      </svg>
    </div>
    <div>
      <h3 class="font-medium text-accent-navy">Secure Package</h3>
      <p class="text-xs text-gray-500">Get what you pay for</p>
    </div>
  </div>
  
  <div class="bg-white p-4 rounded-lg shadow-sm flex items-center">
    <div class="p-3 rounded-full bg-accent-ochre/10 mr-3">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-accent-ochre" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
      </svg>
    </div>
    <div>
      <h3 class="font-medium text-accent-navy">Secure Payment</h3>
      <p class="text-xs text-gray-500">100% secure payments</p>
    </div>
  </div>
  
  <div class="bg-white p-4 rounded-lg shadow-sm flex items-center">
    <div class="p-3 rounded-full bg-accent-terracotta/10 mr-3">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-accent-terracotta" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 15v-1a4 4 0 00-4-4H8m0 0l3 3m-3-3l3-3m9 14V5a2 2 0 00-2-2H6a2 2 0 00-2 2v16l4-2 4 2 4-2 4 2z" />
      </svg>
    </div>
    <div>
      <h3 class="font-medium text-accent-navy">1 Week Refund</h3>
      <p class="text-xs text-gray-500">Money Back Guarantee</p>
    </div>
  </div>
</div>

<!-- Best Deals Section - Dynamic Products -->
<div class="mb-10">
  <div class="flex justify-between items-center mb-4">
    <h2 class="text-2xl font-bold text-accent-navy">Grab the best deal on Smartphones</h2>
    <a href="/products?category=1" class="text-accent-teal hover:text-accent-ceramicblue text-sm font-medium">View all</a>
  </div>
  
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6">
    <?php
    // Find products in the smartphone category (assuming category_id = 1)
    $smartphoneProducts = $productRepository->findByCategory(1, 5);
    
    if (empty($smartphoneProducts)) {
        // Fallback if no smartphone products found
        $smartphoneProducts = array_slice($popularProducts, 0, 5);
    }
    
    foreach ($smartphoneProducts as $product): 
    ?>
    <div class="bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-md transition group">
      <div class="p-2 flex justify-center bg-gray-50">
        <img src="<?= htmlspecialchars($product['image_path'] ?? '') ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="h-40 object-contain"
             onerror="this.src='/api/placeholder/200/200'; this.onerror=null;">
      </div>
      <div class="p-4">
        <h3 class="font-medium text-accent-navy group-hover:text-accent-teal transition-colors truncate"><?= htmlspecialchars($product['name']) ?></h3>
        <div class="flex justify-between items-center mt-2 mb-3">
          <p class="font-bold text-accent-terracotta"><?= number_format($product['price'], 2) ?> MAD</p>
          <?php if (isset($product['original_price']) && $product['original_price'] > $product['price']): ?>
          <p class="text-sm text-gray-500 line-through"><?= number_format($product['original_price'], 2) ?> MAD</p>
          <?php endif; ?>
        </div>
        <form action="/cart/add" method="post" class="add-to-cart-form">
          <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
          <input type="hidden" name="quantity" value="1">
          <button type="submit" class="w-full py-2 bg-accent-ochre text-white rounded-md text-center block hover:bg-accent-terracotta transition text-sm">
            Add to Cart
          </button>
        </form>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
</div>

<!-- Category Showcase Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
  <?php
  // Get first featured category for the main banner
  $mainCategory = $featuredCategories[0] ?? $categories[0] ?? null;
  ?>
  
  <!-- Left column: Main category (e.g. Laptops & Computers) -->
  <?php if ($mainCategory): ?>
  <div class="relative rounded-lg overflow-hidden h-60 shadow-sm">
    <img src="/assets/images/buy_physical_books.webp" class="w-full h-full object-cover"
         onerror="this.src='/api/placeholder/600/400'; this.onerror=null;">
    <div class="absolute inset-0 bg-gradient-to-t from-accent-navy/80 to-transparent p-6 flex flex-col justify-end">
      <h3 class="text-white text-2xl font-bold mb-2"><?= htmlspecialchars($mainCategory['name']) ?></h3>
      <p class="text-white text-sm mb-4">Starting from $599.99</p>
      <a href="/products?category=<?= $mainCategory['id'] ?>" class="bg-white hover:bg-gray-100 text-accent-navy font-medium py-2 px-4 rounded-md inline-block transition w-max text-sm">
        Shop Now
      </a>
    </div>
  </div>
  <?php endif; ?>
  
  <!-- Right column: Stacked layout -->
  <div class="grid grid-rows-2 gap-6">
    <?php
    // Get next two featured categories for the smaller banners
    $secondCategory = $featuredCategories[1] ?? $categories[1] ?? null;
    $thirdCategory = $featuredCategories[2] ?? $categories[2] ?? null;
    
    if ($secondCategory):
    ?>
    <!-- Second Category (e.g. Camera & Media) -->
    <div class="relative rounded-lg overflow-hidden shadow-sm">
      <img src="/assets/images/clothingWallpaper.jpg" alt="<?= htmlspecialchars($secondCategory['name']) ?>" class="w-full h-full object-cover"
           onerror="this.src='/api/placeholder/600/200'; this.onerror=null;">
      <div class="absolute inset-0 bg-gradient-to-t from-accent-navy/80 to-transparent p-6 flex flex-col justify-end">
        <h3 class="text-white text-xl font-bold mb-2"><?= htmlspecialchars($secondCategory['name']) ?></h3>
        <p class="text-white text-xs mb-3">Starting from $249.99</p>
        <a href="/products?category=<?= $secondCategory['id'] ?>" class="bg-white hover:bg-gray-100 text-accent-navy font-medium py-1.5 px-3 rounded-md inline-block transition w-max text-xs">
          Shop Now
        </a>
      </div>
    </div>
    <?php endif; ?>
    
    <?php if ($thirdCategory): ?>
    <!-- Third Category (e.g. Mobile Phones) -->
    <div class="relative rounded-lg overflow-hidden shadow-sm">
      <img src="/assets/images/healthWallpaper.jpg" class="w-full h-full object-cover"
           onerror="this.src='/api/placeholder/600/200'; this.onerror=null;">
      <div class="absolute inset-0 bg-gradient-to-t from-accent-navy/80 to-transparent p-6 flex flex-col justify-end">
        <h3 class="text-white text-xl font-bold mb-2"><?= htmlspecialchars($thirdCategory['name']) ?></h3>
        <p class="text-white text-xs mb-3">Starting from $299.99</p>
        <a href="/products?category=<?= $thirdCategory['id'] ?>" class="bg-white hover:bg-gray-100 text-accent-navy font-medium py-1.5 px-3 rounded-md inline-block transition w-max text-xs">
          Shop Now
        </a>
      </div>
    </div>
    <?php endif; ?>
  </div>
</div>

<!-- Fourth Category Banner (e.g. Gaming Category) -->
<?php
$fourthCategory = $featuredCategories[3] ?? $categories[3] ?? null;
if ($fourthCategory):
?>
<div class="relative rounded-lg overflow-hidden h-60 shadow-sm mb-10">
  <img src="/assets/images/electronics.jpg" alt="<?= htmlspecialchars($fourthCategory['name']) ?>" class="w-full h-full object-cover"
       onerror="this.src='/api/placeholder/1200/400'; this.onerror=null;">
  <div class="absolute inset-0 bg-gradient-to-r from-accent-navy/80 to-transparent p-8 flex flex-col justify-center">
    <h3 class="text-white text-2xl font-bold mb-2"><?= htmlspecialchars($fourthCategory['name']) ?></h3>
    <p class="text-white text-sm mb-4">Starting from $59.99</p>
    <a href="/products?category=<?= $fourthCategory['id'] ?>" class="bg-white hover:bg-gray-100 text-accent-navy font-medium py-2 px-4 rounded-md inline-block transition w-max text-sm">
      Shop Now
    </a>
  </div>
</div>
<?php endif; ?>

<!-- New Arrivals Section -->
<div class="mb-10">
  <div class="flex justify-between items-center mb-4">
    <h2 class="text-2xl font-bold text-accent-navy">New Arrivals</h2>
    <a href="/products?sort=new" class="text-accent-teal hover:text-accent-ceramicblue text-sm font-medium">View all</a>
  </div>
  
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
    <?php foreach (array_slice($newArrivals, 0, 4) as $product): ?>
    <div class="bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-md transition group">
      <div class="p-2 flex justify-center bg-gray-50">
      <img src="<?= htmlspecialchars($product['image_path'] ?? '') ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="h-40 object-contain"
      onerror="this.src='/api/placeholder/200/200'; this.onerror=null;">
      </div>
      <div class="p-4">
        <div class="flex justify-between items-start mb-1">
          <h3 class="font-medium text-accent-navy group-hover:text-accent-teal transition-colors truncate"><?= htmlspecialchars($product['name']) ?></h3>
          <span class="bg-accent-ceramicblue text-white text-xs px-2 py-0.5 rounded-full">New</span>
        </div>
        <div class="flex justify-between items-center mt-2 mb-3">
          <p class="font-bold text-accent-terracotta"><?= number_format($product['price'], 2) ?> MAD</p>
        </div>
        <form action="/cart/add" method="post" class="add-to-cart-form">
          <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
          <input type="hidden" name="quantity" value="1">
          <button type="submit" class="w-full py-2 bg-accent-ochre text-white rounded-md text-center block hover:bg-accent-terracotta transition text-sm">
            Add to Cart
          </button>
        </form>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
</div>

<!-- Featured Categories Section -->
<div class="mb-10">
  <h2 class="text-2xl font-bold text-accent-navy mb-6">Shop by Category</h2>
  
  <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-6">
    <?php foreach (array_slice($categories, 0, 6) as $index => $category): ?>
    <a href="/products?category=<?= $category['id'] ?>" class="bg-white rounded-lg shadow-sm p-4 text-center hover:shadow-md transition">
      <div class="w-16 h-16 mx-auto 
        <?php 
        // Alternate colors for categories
        $colorClasses = [
          'bg-accent-teal/10 text-accent-teal',
          'bg-accent-ceramicblue/10 text-accent-ceramicblue',
          'bg-accent-ochre/10 text-accent-ochre',
          'bg-accent-terracotta/10 text-accent-terracotta',
          'bg-accent-navy/10 text-accent-navy',
          'bg-gray-200 text-gray-700'
        ];
        echo $colorClasses[$index % 6]; 
        ?> 
        rounded-full flex items-center justify-center mb-4">
        <!-- You would ideally have category icons stored in your database -->
        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="<?= getCategoryIconPath($index) ?>" />
        </svg>
      </div>
      <h3 class="font-medium text-accent-navy"><?= htmlspecialchars($category['name']) ?></h3>
    </a>
    <?php endforeach; ?>
  </div>
</div>

<!-- Popular Brands Section -->
<div class="mb-10">
  <h2 class="text-2xl font-bold text-accent-navy mb-6">Popular Brands</h2>
  
  <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-6 gap-4">
    <?php
    // You would ideally fetch these from a brands table
    $brands = [
      ['name' => 'Apple', 'image' => '/assets/images/apple.png'],
      ['name' => 'Samsung', 'image' => '/assets/images/Samsung_Logo.png'],
      ['name' => 'Sony', 'image' => '/assets/images/Sony-Logo.wine.png'],
      ['name' => 'Microsoft', 'image' => '/assets/images/microsoft.png'],
      ['name' => 'Google', 'image' => '/assets/images/Google.webp'],
      ['name' => 'Dell', 'image' => '/assets/images/dell.png']
    ];
    
    foreach ($brands as $brand):
    ?>
    <div class="bg-white p-6 rounded-lg shadow-sm flex items-center justify-center h-24">
      <img src="<?= $brand['image'] ?>" alt="<?= $brand['name'] ?>" class="max-h-12 grayscale hover:grayscale-0 transition"
           onerror="this.src='/api/placeholder/120/60'; this.onerror=null;">
    </div>
    <?php endforeach; ?>
  </div>
</div>

<!-- Newsletter Subscription -->
<div class="bg-gradient-to-r from-accent-navy to-accent-teal rounded-lg shadow-sm p-8 mb-10 text-white">
  <div class="max-w-3xl mx-auto text-center">
    <h2 class="text-2xl font-bold mb-2">Subscribe to our Newsletter</h2>
    <p class="mb-6">Get the latest updates about new products and upcoming sales</p>
    <form class="flex flex-col sm:flex-row gap-2 max-w-lg mx-auto">
      <input type="email" placeholder="Your email address" class="flex-grow py-3 px-4 rounded-md focus:outline-none text-gray-800">
      <button type="submit" class="bg-accent-ochre hover:bg-accent-terracotta text-white font-medium py-3 px-6 rounded-md transition">
        Subscribe
      </button>
    </form>
  </div>
</div>

<!-- Testimonials -->
<div class="mb-10">
  <h2 class="text-2xl font-bold text-accent-navy mb-6">Customer Reviews</h2>
  
  <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <?php
    // You would ideally fetch these from a testimonials table
    $testimonials = [
      [
        'name' => 'Sarah Johnson',
        'rating' => 5,
        'comment' => "I'm extremely satisfied with my purchase. The delivery was fast, and the product quality exceeded my expectations. Definitely will shop here again!"
      ],
      [
        'name' => 'Michael Rodriguez',
        'rating' => 4,
        'comment' => "The customer service is outstanding. They were very helpful when I had questions about my order and made the whole experience smooth and enjoyable."
      ],
      [
        'name' => 'Emily Chen',
        'rating' => 5,
        'comment' => "The prices are competitive and the shipping is fast. I received my package within 2 days of ordering. Everything was well packaged and in perfect condition."
      ]
    ];
    
    foreach ($testimonials as $testimonial):
    ?>
    <div class="bg-white rounded-lg shadow-sm p-6">
      <div class="flex text-accent-ochre mb-3">
        <?php for ($i = 1; $i <= 5; $i++): ?>
          <?php if ($i <= $testimonial['rating']): ?>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
              <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
            </svg>
          <?php else: ?>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-300" viewBox="0 0 20 20" fill="currentColor">
              <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
            </svg>
          <?php endif; ?>
        <?php endfor; ?>
      </div>
      <p class="text-gray-600 italic mb-4"><?= htmlspecialchars($testimonial['comment']) ?></p>
      <div class="flex items-center">
        <div class="w-10 h-10 rounded-full bg-gray-200 mr-3 flex-shrink-0"></div>
        <div>
          <h4 class="font-medium text-accent-navy"><?= htmlspecialchars($testimonial['name']) ?></h4>
          <p class="text-xs text-gray-500">Verified Customer</p>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
</div>

<!-- Why Choose Us Section -->
<div class="mb-10">
  <h2 class="text-2xl font-bold text-accent-navy mb-6">Why Choose YOU/Market</h2>
  
  <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <!-- Feature 1 -->
    <div class="bg-white rounded-lg shadow-sm p-6">
      <div class="w-12 h-12 rounded-full bg-accent-teal/10 flex items-center justify-center mb-4">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-accent-teal" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
      </div>
      <h3 class="text-lg font-medium text-accent-navy mb-2">Best Prices</h3>
      <p class="text-gray-600">We work directly with manufacturers to bring you the best prices on high-quality electronics and gadgets.</p>
    </div>
    
    <!-- Feature 2 -->
    <div class="bg-white rounded-lg shadow-sm p-6">
      <div class="w-12 h-12 rounded-full bg-accent-ochre/10 flex items-center justify-center mb-4">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-accent-ochre" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
        </svg>
      </div>
      <h3 class="text-lg font-medium text-accent-navy mb-2">Genuine Products</h3>
      <p class="text-gray-600">All our products are 100% genuine with manufacturer warranty and after-sales support.</p>
    </div>
    
    <!-- Feature 3 -->
    <div class="bg-white rounded-lg shadow-sm p-6">
      <div class="w-12 h-12 rounded-full bg-accent-ceramicblue/10 flex items-center justify-center mb-4">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-accent-ceramicblue" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
        </svg>
      </div>
      <h3 class="text-lg font-medium text-accent-navy mb-2">Global Shipping</h3>
      <p class="text-gray-600">We deliver to over 100 countries worldwide with fast, reliable shipping and real-time tracking.</p>
    </div>
  </div>
</div>

<?php
/**
 * Helper function to get icon path for category
 * In a real application, you would store icons in your database
 * @param int $index Category index
 * @return string SVG path
 */
function getCategoryIconPath(int $index): string {
    $paths = [
        // Smartphones icon
        "M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z",
        // Laptops icon
        "M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z",
        // Cameras icon
        "M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z M15 13a3 3 0 11-6 0 3 3 0 016 0z",
        // Gaming icon
        "M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z",
        // Accessories icon
        "M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2",
        // More icon
        "M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z"
    ];
    
    return $paths[$index % count($paths)];
}
?>