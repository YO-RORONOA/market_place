<?php $title = isset($category) ? $category['name'] : (isset($search) ? "Search: $search" : "All Products"); ?>

<!-- Hero Banner for Ads -->
<div class="mb-8 bg-gradient-to-r from-accent-terracotta via-accent-ochre to-accent-teal rounded-lg shadow-md overflow-hidden">
    <div class="py-6 px-8 text-white relative">
        <div class="absolute top-2 right-2 text-xs px-2 py-1 bg-white text-accent-navy rounded-full">Ad</div>
        <h2 class="text-2xl font-bold mb-2">Discover Amazing Deals</h2>
        <p class="mb-4 max-w-lg">Shop our exclusive collection with special discounts for a limited time only!</p>
        <a href="#" class="inline-block px-6 py-2 bg-white text-accent-navy font-medium rounded-md hover:bg-gray-100 transition">
            Shop Now
        </a>
    </div>
</div>

<div class="flex flex-col lg:flex-row lg:space-x-8">
    <!-- Main Content (Product Grid) -->
    <div class="w-full lg:w-3/4">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-accent-navy mb-2">
                <?= $title ?>
            </h1>
            
            <?php if (isset($category)): ?>
                <p class="text-gray-600"><?= htmlspecialchars($category['description'] ?? '') ?></p>
            <?php endif; ?>
            
            <div class="mt-4">
                <form action="/products" method="get" class="flex product-search-form">
                    <input 
                        type="text" 
                        name="search" 
                        value="<?= isset($search) ? htmlspecialchars($search) : '' ?>"
                        placeholder="Search products..." 
                        class="flex-grow px-4 py-2 border border-gray-300 rounded-l-md focus:outline-none focus:ring-accent-teal focus:border-accent-teal"
                    >
                    <button type="submit" class="px-4 py-2 bg-accent-teal text-white rounded-r-md hover:bg-accent-navy transition">
                        Search
                    </button>
                </form>
            </div>
        </div>

        <?php if (empty($products)): ?>
            <div class="bg-white rounded-lg shadow-sm p-8 text-center">
                <p class="text-gray-600 mb-4">No products found.</p>
                <a href="/products" class="text-accent-teal hover:underline">View all products</a>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 products-container" 
                 data-category="<?= isset($category) ? $category['id'] : '' ?>" 
                 data-search="<?= isset($search) ? htmlspecialchars($search) : '' ?>">
                <?php 
                $productCount = 0;
                foreach ($products as $product): 
                    $productCount++;
                ?>
                <div class="bg-white rounded-lg shadow-sm overflow-hidden transition transform hover:shadow-md hover:-translate-y-1 product-card">
                    <a href="/products/view?id=<?= $product['id'] ?>">
                        <div class="h-48 overflow-hidden">
                            <?php if ($product['image_path']): ?>
                                <img 
                                    src="<?= htmlspecialchars($product['image_path']) ?>" 
                                    alt="<?= htmlspecialchars($product['name']) ?>" 
                                    class="w-full h-full object-cover"
                                >
                            <?php else: ?>
                                <div class="w-full h-full flex items-center justify-center bg-gray-200">
                                    <span class="text-gray-400">No image</span>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="p-4">
                            <h3 class="font-semibold text-lg mb-1 text-accent-navy"><?= htmlspecialchars($product['name']) ?></h3>
                            <p class="text-accent-terracotta font-bold"><?= number_format($product['price'], 2) ?> MAD</p>
                            <p class="text-gray-600 text-sm mt-2 line-clamp-2"><?= htmlspecialchars($product['description']) ?></p>
                        </div>
                    </a>
                    
                    <div class="px-4 pb-4">
                        <form action="/cart/add" method="post" class="add-to-cart-form">
                            <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                            <input type="hidden" name="quantity" value="1">
                            <button type="submit" class="w-full py-2 bg-accent-ochre text-white rounded-md hover:bg-accent-terracotta transition">
                                Add to Cart
                            </button>
                        </form>
                    </div>
                </div>

                <?php
                // Insert interstitial ad after every 6 products
                if ($productCount % 6 === 0 && $productCount < count($products)): 
                ?>
                <div class="col-span-1 sm:col-span-2 lg:col-span-3 bg-gray-50 rounded-lg shadow-sm overflow-hidden my-2 ad-interstitial">
                    <div class="p-4 flex items-center">
                        <div class="relative flex-shrink-0 mr-4 w-24 h-24 bg-gradient-to-r from-accent-ceramicblue to-accent-navy rounded-md">
                            <div class="absolute top-1 left-1 text-xs px-1 bg-white text-accent-navy rounded-full">Ad</div>
                        </div>
                        <div>
                            <h3 class="font-medium text-accent-navy">Special Offer</h3>
                            <p class="text-gray-600 text-sm">Discover our top-rated products with exclusive discounts.</p>
                            <a href="#" class="mt-2 inline-block text-sm text-accent-teal hover:underline">Learn more</a>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
                <?php endforeach; ?>
            </div>
            
            <!-- Pagination -->
            <div class="pagination-wrapper mt-8">
                <?php if ($totalPages > 1): ?>
                <div class="flex justify-center">
                    <div class="flex space-x-1 pagination-container">
                        <?php if ($currentPage > 1): ?>
                            <a href="?page=<?= $currentPage - 1 ?><?= isset($category) ? '&category='.$category['id'] : '' ?><?= isset($search) ? '&search='.urlencode($search) : '' ?>" 
                               class="pagination-link px-4 py-2 bg-white border border-gray-300 rounded-md hover:bg-gray-50"
                               data-page="<?= $currentPage - 1 ?>">
                                Previous
                            </a>
                        <?php endif; ?>
                        
                        <?php 
                        $start = max(1, $currentPage - 2);
                        $end = min($totalPages, $start + 4);
                        $start = max(1, $end - 4);
                        
                        for ($i = $start; $i <= $end; $i++): 
                        ?>
                            <a href="?page=<?= $i ?><?= isset($category) ? '&category='.$category['id'] : '' ?><?= isset($search) ? '&search='.urlencode($search) : '' ?>" 
                               class="pagination-link px-4 py-2 <?= $i === $currentPage ? 'bg-accent-teal text-white' : 'bg-white text-gray-700 hover:bg-gray-50' ?> border border-gray-300 rounded-md"
                               data-page="<?= $i ?>">
                                <?= $i ?>
                            </a>
                        <?php endfor; ?>
                        
                        <?php if ($currentPage < $totalPages): ?>
                            <a href="?page=<?= $currentPage + 1 ?><?= isset($category) ? '&category='.$category['id'] : '' ?><?= isset($search) ? '&search='.urlencode($search) : '' ?>" 
                               class="pagination-link px-4 py-2 bg-white border border-gray-300 rounded-md hover:bg-gray-50"
                               data-page="<?= $currentPage + 1 ?>">
                                Next
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
    
    <!-- Right Sidebar with Categories and Ad Space -->
    <div class="w-full lg:w-1/4 mt-8 lg:mt-0">
        <!-- Categories Section -->
        <div class="bg-white rounded-lg shadow-sm p-4 mb-6">
            <h2 class="text-xl font-semibold mb-4 text-accent-navy">Categories</h2>
            <ul class="space-y-2 category-list">
                <?php foreach ($categories as $cat): ?>
                <li>
                    <a 
                        href="/products?category=<?= $cat['id'] ?>" 
                        class="category-link block px-3 py-2 rounded-md <?= (isset($category) && $category['id'] == $cat['id']) ? 'bg-accent-teal text-white' : 'hover:bg-gray-100' ?>"
                        data-category-id="<?= $cat['id'] ?>"
                    >
                        <?= htmlspecialchars($cat['name']) ?>
                    </a>
                </li>
                <?php endforeach; ?>
            </ul>
        </div>
        
        <!-- Ad Space -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="relative">
                <div class="absolute top-2 right-2 text-xs px-2 py-1 bg-white text-accent-navy rounded-full">Ad</div>
                <div class="bg-gradient-to-br from-accent-ochre to-accent-terracotta h-40 flex items-center justify-center">
                    <div class="text-white text-center p-4">
                        <h3 class="font-bold text-lg mb-2">Featured Deals</h3>
                        <p class="text-sm mb-3">Limited time offers on selected items</p>
                        <a href="#" class="inline-block px-4 py-1 bg-white text-accent-terracotta rounded-full text-sm font-medium hover:bg-gray-100 transition">
                            View Offers
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Template for product card (will be cloned by JavaScript) -->
<template id="product-card-template">
    <div class="bg-white rounded-lg shadow-sm overflow-hidden transition transform hover:shadow-md hover:-translate-y-1 product-card">
        <a href="/products/view?id=" class="product-link">
            <div class="h-48 overflow-hidden">
                <img src="" alt="" class="product-image w-full h-full object-cover">
                <div class="w-full h-full flex items-center justify-center bg-gray-200 no-image-placeholder" style="display: none;">
                    <span class="text-gray-400">No image</span>
                </div>
            </div>
            
            <div class="p-4">
                <h3 class="product-name font-semibold text-lg mb-1 text-accent-navy"></h3>
                <p class="product-price text-accent-terracotta font-bold"></p>
                <p class="product-description text-gray-600 text-sm mt-2 line-clamp-2"></p>
            </div>
        </a>
        
        <div class="px-4 pb-4">
            <form action="/cart/add" method="post" class="add-to-cart-form">
                <input type="hidden" name="product_id" value="" class="product-id-input">
                <input type="hidden" name="quantity" value="1">
                <button type="submit" class="w-full py-2 bg-accent-ochre text-white rounded-md hover:bg-accent-terracotta transition">
                    Add to Cart
                </button>
            </form>
        </div>
    </div>
</template>

<!-- Template for interstitial ad (will be cloned by JavaScript) -->
<template id="ad-interstitial-template">
    <div class="col-span-1 sm:col-span-2 lg:col-span-3 bg-gray-50 rounded-lg shadow-sm overflow-hidden my-2 ad-interstitial">
        <div class="p-4 flex items-center">
            <div class="relative flex-shrink-0 mr-4 w-24 h-24 bg-gradient-to-r from-accent-ceramicblue to-accent-navy rounded-md">
                <div class="absolute top-1 left-1 text-xs px-1 bg-white text-accent-navy rounded-full">Ad</div>
            </div>
            <div>
                <h3 class="font-medium text-accent-navy">Special Offer</h3>
                <p class="text-gray-600 text-sm">Discover our top-rated products with exclusive discounts.</p>
                <a href="#" class="mt-2 inline-block text-sm text-accent-teal hover:underline">Learn more</a>
            </div>
        </div>
    </div>
</template>