<?php $title = "Welcome to YOU/Market"; ?>

<!-- Hero Section -->
<div class="relative overflow-hidden bg-white mb-8">
    <!-- Hero Image with Overlay -->
    <div class="relative h-[500px] w-full overflow-hidden">
        <img src="/assets/images/hero-banner.jpg" alt="YOU/Market - Discover Unique Products" class="absolute inset-0 w-full h-full object-cover">
        <div class="absolute inset-0 bg-gradient-to-r from-accent-navy/80 to-transparent"></div>
        
        <!-- Hero Content -->
        <div class="absolute inset-0 flex items-center">
            <div class="container mx-auto px-4">
                <div class="max-w-lg">
                    <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">Discover Unique Artisan Products</h1>
                    <p class="text-lg text-white/90 mb-8">Shop our curated collection of handcrafted items from talented artisans around the world.</p>
                    <div class="flex flex-wrap gap-4">
                        <a href="/products" class="px-6 py-3 bg-accent-ochre hover:bg-accent-terracotta text-white font-medium rounded-md transition shadow-lg">
                            Shop Now
                        </a>
                        <a href="/vendor/register" class="px-6 py-3 bg-white hover:bg-gray-100 text-accent-navy font-medium rounded-md transition shadow-lg">
                            Become a Seller
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Categories Section -->
<section class="mb-12">
    <div class="container mx-auto px-4">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-accent-navy">Shop by Category</h2>
            <a href="/products" class="text-accent-teal hover:text-accent-navy transition flex items-center">
                View All
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                </svg>
            </a>
        </div>
        
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
            <?php foreach ($categories as $category): ?>
            <a href="/products?category=<?= $category['id'] ?>" class="group">
                <div class="bg-white rounded-lg shadow-sm overflow-hidden transition transform hover:shadow-md hover:-translate-y-1">
                    <div class="aspect-square bg-gray-50 flex items-center justify-center p-6">
                        <?php if (!empty($category['image_path'])): ?>
                            <img src="<?= htmlspecialchars($category['image_path']) ?>" alt="<?= htmlspecialchars($category['name']) ?>" class="max-h-full max-w-full object-contain">
                        <?php else: ?>
                            <div class="w-16 h-16 rounded-full bg-accent-teal/10 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-accent-teal" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                                </svg>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="p-3 text-center">
                        <h3 class="font-medium text-gray-800 group-hover:text-accent-teal transition-colors">
                            <?= htmlspecialchars($category['name']) ?>
                        </h3>
                    </div>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Featured Products Carousel -->
<section class="bg-gray-50 py-12 mb-12">
    <div class="container mx-auto px-4">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-accent-navy">Featured Products</h2>
            <a href="/products?featured=1" class="text-accent-teal hover:text-accent-navy transition flex items-center">
                View All
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                </svg>
            </a>
        </div>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            <?php foreach ($popularProducts as $index => $product): ?>
                <?php if ($index < 8): ?>
                <div class="bg-white rounded-lg shadow-sm overflow-hidden transition transform hover:shadow-md hover:-translate-y-1 group">
                    <a href="/products/view?id=<?= $product['id'] ?>" class="block relative">
                        <div class="h-52 overflow-hidden bg-gray-50">
                            <?php if (!empty($product['image_path'])): ?>
                                <img 
                                    src="<?= htmlspecialchars($product['image_path']) ?>" 
                                    alt="<?= htmlspecialchars($product['name']) ?>" 
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                >
                            <?php else: ?>
                                <div class="w-full h-full flex items-center justify-center bg-gray-100">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Featured Badge -->
                        <div class="absolute top-2 right-2">
                            <span class="px-2 py-1 text-xs font-medium bg-accent-ochre text-white rounded-md">
                                Featured
                            </span>
                        </div>
                    </a>
                    
                    <div class="p-4">
                        <a href="/products/view?id=<?= $product['id'] ?>">
                            <h3 class="font-semibold text-lg mb-1 text-accent-navy group-hover:text-accent-teal transition-colors">
                                <?= htmlspecialchars($product['name']) ?>
                            </h3>
                        </a>
                        
                        <div class="flex justify-between items-center mb-2">
                            <p class="text-accent-terracotta font-bold"><?= number_format($product['price'], 2) ?> MAD</p>
                            
                            <!-- Stock Badge -->
                            <?php if (isset($product['stock_quantity']) && $product['stock_quantity'] > 0): ?>
                                <span class="px-2 py-0.5 text-xs bg-green-100 text-green-800 rounded-full">
                                    In Stock
                                </span>
                            <?php else: ?>
                                <span class="px-2 py-0.5 text-xs bg-gray-100 text-gray-800 rounded-full">
                                    Out of Stock
                                </span>
                            <?php endif; ?>
                        </div>
                        
                        <form action="/cart/add" method="post" class="mt-4">
                            <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                            <input type="hidden" name="quantity" value="1">
                            <button type="submit" class="w-full py-2 bg-accent-ochre text-white rounded-md hover:bg-accent-terracotta transition flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                Add to Cart
                            </button>
                        </form>
                    </div>
                </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- New Arrivals Section -->
<section class="mb-12">
    <div class="container mx-auto px-4">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-accent-navy">New Arrivals</h2>
            <a href="/products?sort=newest" class="text-accent-teal hover:text-accent-navy transition flex items-center">
                View All
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                </svg>
            </a>
        </div>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            <?php foreach ($newArrivals as $index => $product): ?>
                <?php if ($index < 4): ?>
                <div class="bg-white rounded-lg shadow-sm overflow-hidden transition transform hover:shadow-md hover:-translate-y-1 group">
                    <a href="/products/view?id=<?= $product['id'] ?>" class="block relative">
                        <div class="h-52 overflow-hidden bg-gray-50">
                            <?php if (!empty($product['image_path'])): ?>
                                <img 
                                    src="<?= htmlspecialchars($product['image_path']) ?>" 
                                    alt="<?= htmlspecialchars($product['name']) ?>" 
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                >
                            <?php else: ?>
                                <div class="w-full h-full flex items-center justify-center bg-gray-100">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <!-- New Badge -->
                        <div class="absolute top-2 right-2">
                            <span class="px-2 py-1 text-xs font-medium bg-accent-ceramicblue text-white rounded-md">
                                New
                            </span>
                        </div>
                    </a>
                    
                    <div class="p-4">
                        <a href="/products/view?id=<?= $product['id'] ?>">
                            <h3 class="font-semibold text-lg mb-1 text-accent-navy group-hover:text-accent-teal transition-colors">
                                <?= htmlspecialchars($product['name']) ?>
                            </h3>
                        </a>
                        
                        <div class="flex justify-between items-center mb-2">
                            <p class="text-accent-terracotta font-bold"><?= number_format($product['price'], 2) ?> MAD</p>
                            
                            <!-- Stock Badge -->
                            <?php if (isset($product['stock_quantity']) && $product['stock_quantity'] > 0): ?>
                                <span class="px-2 py-0.5 text-xs bg-green-100 text-green-800 rounded-full">
                                    In Stock
                                </span>
                            <?php else: ?>
                                <span class="px-2 py-0.5 text-xs bg-gray-100 text-gray-800 rounded-full">
                                    Out of Stock
                                </span>
                            <?php endif; ?>
                        </div>
                        
                        <form action="/cart/add" method="post" class="mt-4">
                            <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                            <input type="hidden" name="quantity" value="1">
                            <button type="submit" class="w-full py-2 bg-accent-ochre text-white rounded-md hover:bg-accent-terracotta transition flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                Add to Cart
                            </button>
                        </form>
                    </div>
                </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Category Product Sections -->
<section class="mb-12">
    <div class="container mx-auto px-4">
        <?php foreach ($categoryProducts as $categoryId => $categoryData): ?>
            <div class="mb-12">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-accent-navy"><?= htmlspecialchars($categoryData['name']) ?></h2>
                    <a href="/products?category=<?= $categoryId ?>" class="text-accent-teal hover:text-accent-navy transition flex items-center">
                        View All
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                        </svg>
                    </a>
                </div>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    <?php foreach ($categoryData['products'] as $product): ?>
                    <div class="bg-white rounded-lg shadow-sm overflow-hidden transition transform hover:shadow-md hover:-translate-y-1 group">
                        <a href="/products/view?id=<?= $product['id'] ?>" class="block relative">
                            <div class="h-52 overflow-hidden bg-gray-50">
                                <?php if (!empty($product['image_path'])): ?>
                                    <img 
                                        src="<?= htmlspecialchars($product['image_path']) ?>" 
                                        alt="<?= htmlspecialchars($product['name']) ?>" 
                                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                    >
                                <?php else: ?>
                                    <div class="w-full h-full flex items-center justify-center bg-gray-100">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </a>
                        
                        <div class="p-4">
                            <a href="/products/view?id=<?= $product['id'] ?>">
                                <h3 class="font-semibold text-lg mb-1 text-accent-navy group-hover:text-accent-teal transition-colors">
                                    <?= htmlspecialchars($product['name']) ?>
                                </h3>
                            </a>
                            
                            <div class="flex justify-between items-center mb-2">
                                <p class="text-accent-terracotta font-bold"><?= number_format($product['price'], 2) ?> MAD</p>
                                
                                <!-- Stock Badge -->
                                <?php if (isset($product['stock_quantity']) && $product['stock_quantity'] > 0): ?>
                                    <span class="px-2 py-0.5 text-xs bg-green-100 text-green-800 rounded-full">
                                        In Stock
                                    </span>
                                <?php else: ?>
                                    <span class="px-2 py-0.5 text-xs bg-gray-100 text-gray-800 rounded-full">
                                        Out of Stock
                                    </span>
                                <?php endif; ?>
                            </div>
                            
                            <form action="/cart/add" method="post" class="mt-4">
                                <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="w-full py-2 bg-accent-ochre text-white rounded-md hover:bg-accent-terracotta transition flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                    Add to Cart
                                </button>
                            </form>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- Newsletter & Promotion Section -->
<section class="py-12 mb-12 bg-gradient-to-r from-accent-navy to-accent-ceramicblue text-white">
    <div class="container mx-auto px-4">
        <div class="flex flex-col md:flex-row items-center justify-between">
            <div class="mb-8 md:mb-0 md:max-w-xl">
                <h2 class="text-2xl font-bold mb-4">Join Our Newsletter</h2>
                <p class="mb-6">Stay updated with our latest products, artisan stories, and exclusive offers.</p>
                
                <form class="flex w-full max-w-md">
                    <input 
                        type="email" 
                        placeholder="Your email address" 
                        class="flex-grow py-3 px-4 rounded-l-md focus:outline-none text-gray-800"
                    >
                    <button type="submit" class="bg-accent-ochre hover:bg-accent-terracotta py-3 px-6 rounded-r-md transition">
                        Subscribe
                    </button>
                </form>
            </div>
            
            <div class="text-center md:text-right">
                <h3 class="text-xl font-semibold mb-2">Become an Artisan</h3>
                <p class="mb-4 md:max-w-xs">Share your handcrafted products with customers around the world.</p>
                <a href="/vendor/register" class="inline-block px-6 py-3 bg-white text-accent-navy hover:bg-gray-100 font-medium rounded-md transition">
                    Start Selling
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Trust Features Section -->
<section class="mb-12">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white p-6 rounded-lg shadow-sm flex items-start">
                <div class="mr-4 text-accent-teal">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-lg mb-2 text-accent-navy">Unique Products</h3>
                    <p class="text-gray-600">Discover one-of-a-kind items made by talented artisans.</p>
                </div>
            </div>
            
            <div class="bg-white p-6 rounded-lg shadow-sm flex items-start">
                <div class="mr-4 text-accent-terracotta">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-lg mb-2 text-accent-navy">Fast Delivery</h3>
                    <p class="text-gray-600">Quick and reliable shipping to get your products to you on time.</p>
                </div>
            </div>
            
            <div class="bg-white p-6 rounded-lg shadow-sm flex items-start">
                <div class="mr-4 text-accent-ochre">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-lg mb-2 text-accent-navy">Secure Payments</h3>
                    <p class="text-gray-600">Your transactions are protected with our secure payment system.</p>
                </div>
            </div>
            
            <div class="bg-white p-6 rounded-lg shadow-sm flex items-start">
                <div class="mr-4 text-accent-ceramicblue">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-lg mb-2 text-accent-navy">Customer Support</h3>
                    <p class="text-gray-600">Our team is here to help you with any questions or concerns.</p>
                </div>
            </div>
        </div>
    </div>
</section>