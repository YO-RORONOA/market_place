// views/products/_product_card.php
<div class="bg-white rounded-lg shadow-sm overflow-hidden transition transform hover:shadow-md hover:-translate-y-1">
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