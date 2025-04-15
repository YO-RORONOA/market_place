<?php $title = isset($category) ? $category['name'] : (isset($search) ? "Search: $search" : "All Products"); ?>

<div class="flex flex-col lg:flex-row gap-6">
    <!-- Dynamic Category Filter - Left Sidebar -->
    <div class="w-full lg:w-1/4">
        <div class="bg-white rounded-lg shadow-sm overflow-hidden sticky top-24">
            <div class="p-5 border-b border-gray-100">
                <h2 class="text-xl font-bold text-accent-navy flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    Categories
                </h2>
            </div>

            <div class="p-4">
                <!-- All Products Option -->
                <a href="/products" 
                   class="category-link flex items-center px-3 py-3 rounded-md mb-2 <?= !isset($category) ? 'bg-accent-teal text-white font-medium' : 'text-gray-700 hover:bg-gray-50' ?>"
                   data-category-id="">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 <?= !isset($category) ? 'text-white' : 'text-accent-teal' ?>" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                    </svg>
                    All Products
                    <span class="ml-auto bg-gray-100 text-gray-700 rounded-full px-2 py-1 text-xs">
                        <?= isset($totalProducts) ? $totalProducts : count($products) ?>
                    </span>
                </a>
                
                <!-- Category List -->
                <div class="space-y-1 mt-3 category-list">
                    <?php foreach ($categories as $cat): ?>
                        <a href="/products?category=<?= $cat['id'] ?>" 
                           class="category-link flex items-center px-3 py-2.5 rounded-md <?= (isset($category) && $category['id'] == $cat['id']) ? 'bg-accent-teal text-white font-medium' : 'text-gray-700 hover:bg-gray-50' ?>"
                           data-category-id="<?= $cat['id'] ?>">
                            <span class="w-2 h-2 rounded-full mr-3 <?= (isset($category) && $category['id'] == $cat['id']) ? 'bg-white' : 'bg-accent-ochre' ?>"></span>
                            <?= htmlspecialchars($cat['name']) ?>
                            <?php if (isset($cat['product_count'])): ?>
                                <span class="ml-auto <?= (isset($category) && $category['id'] == $cat['id']) ? 'bg-white text-accent-teal' : 'bg-gray-100 text-gray-700' ?> rounded-full px-2 py-0.5 text-xs">
                                    <?= $cat['product_count'] ?>
                                </span>
                            <?php endif; ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <!-- New Arrivals and Featured Buttons -->
            <div class="p-4 border-t border-gray-100">
                <h3 class="text-sm font-semibold uppercase text-gray-500 mb-3">Quick Filters</h3>
                <a href="/products?sort=new" class="flex items-center px-3 py-2.5 rounded-md text-gray-700 hover:bg-gray-50 mb-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 text-accent-ceramicblue" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    New Arrivals
                </a>
                <a href="/products?featured=1" class="flex items-center px-3 py-2.5 rounded-md text-gray-700 hover:bg-gray-50">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 text-accent-terracotta" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.783-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                    </svg>
                    Featured Products
                </a>
            </div>
        </div>
    </div>

    <!-- Main Content (Product Grid) -->
    <div class="w-full lg:w-3/4">
        <!-- Page Title and Info -->
        <div class="mb-6">
            <div class="flex flex-wrap justify-between items-center">
                <h1 class="text-3xl font-bold text-accent-navy">
                    <?= $title ?>
                </h1>
                
                <div class="text-sm text-gray-500 mt-2 sm:mt-0">
                    Showing <?= count($products) ?> of <?= $totalProducts ?> products
                </div>
            </div>
            
            <?php if (isset($category) && !empty($category['description'])): ?>
                <p class="text-gray-600 mt-2"><?= htmlspecialchars($category['description']) ?></p>
            <?php endif; ?>

            <!-- Sorting Options -->
            <div class="mt-4 flex flex-wrap gap-2">
                <a href="<?= $_SERVER['REQUEST_URI'] ?>" class="px-3 py-1.5 bg-white rounded-md shadow-sm text-sm text-gray-700 border border-gray-200 hover:bg-gray-50">
                    <span class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        Refresh Results
                    </span>
                </a>
                <a href="<?= preg_replace('/([?&])sort=[^&]+(&|$)/', '$1', $_SERVER['REQUEST_URI']) . (strpos($_SERVER['REQUEST_URI'], '?') !== false ? '&' : '?') ?>sort=price_asc" class="px-3 py-1.5 bg-white rounded-md shadow-sm text-sm text-gray-700 border border-gray-200 hover:bg-gray-50">
                    Price: Low to High
                </a>
                <a href="<?= preg_replace('/([?&])sort=[^&]+(&|$)/', '$1', $_SERVER['REQUEST_URI']) . (strpos($_SERVER['REQUEST_URI'], '?') !== false ? '&' : '?') ?>sort=price_desc" class="px-3 py-1.5 bg-white rounded-md shadow-sm text-sm text-gray-700 border border-gray-200 hover:bg-gray-50">
                    Price: High to Low
                </a>
                <a href="<?= preg_replace('/([?&])sort=[^&]+(&|$)/', '$1', $_SERVER['REQUEST_URI']) . (strpos($_SERVER['REQUEST_URI'], '?') !== false ? '&' : '?') ?>sort=newest" class="px-3 py-1.5 bg-white rounded-md shadow-sm text-sm text-gray-700 border border-gray-200 hover:bg-gray-50">
                    Newest First
                </a>
            </div>
        </div>

        <?php if (empty($products)): ?>
            <div class="bg-white rounded-lg shadow-sm p-8 text-center">
                <div class="inline-block p-4 rounded-full bg-gray-100 mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h3 class="text-xl font-medium text-gray-800 mb-2">No products found</h3>
                <p class="text-gray-600 mb-6">We couldn't find any products matching your criteria.</p>
                <a href="/products" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-accent-teal hover:bg-accent-navy focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-accent-teal">
                    View all products
                </a>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 products-container" 
                 data-category="<?= isset($category) ? $category['id'] : '' ?>" 
                 data-search="<?= isset($search) ? htmlspecialchars($search) : '' ?>">
                <?php foreach ($products as $product): ?>
                <div class="bg-white rounded-lg shadow-sm overflow-hidden transition transform hover:shadow-md hover:-translate-y-1 product-card group">
                    <a href="/products/view?id=<?= $product['id'] ?>" class="block relative">
                        <div class="h-52 overflow-hidden bg-gray-50">
                            <?php if ($product['image_path']): ?>
                                <img 
                                    src="<?= htmlspecialchars($product['image_path']) ?>" 
                                    alt="<?= htmlspecialchars($product['name']) ?>" 
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                >
                                <!-- Quick View Button -->
                                <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                    <div class="bg-white bg-opacity-90 px-4 py-2 rounded-full shadow-md text-accent-teal text-sm font-medium">
                                        Quick View
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="w-full h-full flex items-center justify-center bg-gray-100">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Category Badge -->
                        <?php if (!empty($product['category_name'])): ?>
                        <div class="absolute top-2 left-2">
                            <span class="px-2 py-1 text-xs font-medium bg-accent-navy bg-opacity-80 text-white rounded-md">
                                <?= htmlspecialchars($product['category_name'] ?? '') ?>
                            </span>
                        </div>
                        <?php endif; ?>
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
                        
                        <p class="text-gray-600 text-sm mt-2 line-clamp-2">
                            <?= htmlspecialchars(substr($product['description'], 0, 100)) . (strlen($product['description']) > 100 ? '...' : '') ?>
                        </p>
                        
                        <div class="flex items-center justify-between mt-4">
                            <form action="/cart/add" method="post" class="add-to-cart-form flex-grow">
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
                </div>
                <?php endforeach; ?>
            </div>
            
            <!-- Pagination -->
            <div class="pagination-wrapper mt-8">
                <?php if ($totalPages > 1): ?>
                <div class="flex justify-center">
                    <div class="flex flex-wrap gap-2 pagination-container">
                        <?php if ($currentPage > 1): ?>
                            <a href="?page=<?= $currentPage - 1 ?><?= isset($category) ? '&category='.$category['id'] : '' ?><?= isset($search) ? '&search='.urlencode($search) : '' ?>" 
                               class="pagination-link flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md hover:bg-gray-50 shadow-sm"
                               data-page="<?= $currentPage - 1 ?>">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                </svg>
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
                               class="pagination-link px-4 py-2 <?= $i === $currentPage ? 'bg-accent-teal text-white font-medium' : 'bg-white text-gray-700 hover:bg-gray-50' ?> border border-gray-300 rounded-md shadow-sm"
                               data-page="<?= $i ?>">
                                <?= $i ?>
                            </a>
                        <?php endfor; ?>
                        
                        <?php if ($currentPage < $totalPages): ?>
                            <a href="?page=<?= $currentPage + 1 ?><?= isset($category) ? '&category='.$category['id'] : '' ?><?= isset($search) ? '&search='.urlencode($search) : '' ?>" 
                               class="pagination-link flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md hover:bg-gray-50 shadow-sm"
                               data-page="<?= $currentPage + 1 ?>">
                                Next
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Template for product card (will be cloned by JavaScript) -->
<template id="product-card-template">
    <div class="bg-white rounded-lg shadow-sm overflow-hidden transition transform hover:shadow-md hover:-translate-y-1 product-card group">
        <a href="/products/view?id=" class="product-link block relative">
            <div class="h-52 overflow-hidden bg-gray-50">
                <img src="" alt="" class="product-image w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                <div class="w-full h-full flex items-center justify-center bg-gray-100 no-image-placeholder" style="display: none;">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <!-- Quick View Button -->
                <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                    <div class="bg-white bg-opacity-90 px-4 py-2 rounded-full shadow-md text-accent-teal text-sm font-medium">
                        Quick View
                    </div>
                </div>
            </div>
        </a>
        
        <div class="p-4">
            <a href="/products/view?id=" class="product-link">
                <h3 class="product-name font-semibold text-lg mb-1 text-accent-navy group-hover:text-accent-teal transition-colors"></h3>
            </a>
            
            <div class="flex justify-between items-center mb-2">
                <p class="product-price text-accent-terracotta font-bold"></p>
                
                <!-- Stock Badge -->
                <span class="stock-badge px-2 py-0.5 text-xs bg-green-100 text-green-800 rounded-full">
                    In Stock
                </span>
            </div>
            
            <p class="product-description text-gray-600 text-sm mt-2 line-clamp-2"></p>
            
            <div class="flex items-center justify-between mt-4">
                <form action="/cart/add" method="post" class="add-to-cart-form flex-grow">
                    <input type="hidden" name="product_id" value="" class="product-id-input">
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
    </div>
</template>