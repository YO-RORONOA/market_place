<?php $title = 'Shopping Cart'; ?>

<div class="bg-white rounded-lg shadow-sm overflow-hidden">
    <div class="p-6 md:p-8">
        <h1 class="text-3xl font-bold text-accent-navy mb-8">Shopping Cart</h1>
        
        <?php if (empty($cartItems)): ?>
            <div class="text-center py-8">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <p class="text-gray-600 mb-6">Your cart is empty</p>
                <a href="/products" class="inline-block py-2 px-6 bg-accent-ochre text-white font-medium rounded-md hover:bg-accent-terracotta transition">
                    Start Shopping
                </a>
            </div>
        <?php else: ?>
            <div class="cart-content">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b">
                                <th class="text-left py-4 px-2">Product</th>
                                <th class="text-center py-4 px-2">Price</th>
                                <th class="text-center py-4 px-2">Quantity</th>
                                <th class="text-right py-4 px-2">Total</th>
                                <th class="text-right py-4 px-2">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($cartItems as $item): ?>
                            <tr class="border-b cart-item-row" data-product-id="<?= $item->product_id ?>">
                                <td class="py-4 px-2">
                                    <div class="flex items-center">
                                        <div class="w-16 h-16 flex-shrink-0 mr-4 bg-gray-100 rounded overflow-hidden">
                                            <?php if ($item->image_path): ?>
                                                <img src="<?= htmlspecialchars($item->image_path) ?>" alt="<?= htmlspecialchars($item->name) ?>" class="w-full h-full object-cover">
                                            <?php else: ?>
                                                <div class="w-full h-full flex items-center justify-center">
                                                    <span class="text-gray-400 text-xs">No image</span>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <div>
                                            <a href="/products/view?id=<?= $item->product_id ?>" class="text-accent-navy hover:text-accent-teal font-medium">
                                                <?= htmlspecialchars($item->name) ?>
                                            </a>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-2 text-center"><?= number_format($item->price, 2) ?> MAD</td>
                                <td class="py-4 px-2 text-center">
                                    <form action="/cart/update" method="post" class="flex items-center justify-center">
                                        <input type="hidden" name="product_id" value="<?= $item->product_id ?>">
                                        <div class="flex items-center border border-gray-300 rounded-md quantity-controls">
                                            <button type="submit" name="quantity" value="<?= max(1, $item->quantity - 1) ?>" class="px-2 py-1 text-gray-600 hover:bg-gray-100 quantity-btn decrease-btn">-</button>
                                            <span class="w-8 text-center quantity-display"><?= $item->quantity ?></span>
                                            <button type="submit" name="quantity" value="<?= $item->quantity + 1 ?>" class="px-2 py-1 text-gray-600 hover:bg-gray-100 quantity-btn increase-btn">+</button>
                                        </div>
                                    </form>
                                </td>
                                <td class="py-4 px-2 text-right font-medium item-total"><?= number_format($item->getTotal(), 2) ?> MAD</td>
                                <td class="py-4 px-2 text-right">
                                    <a href="/cart/remove?id=<?= $item->product_id ?>" class="text-red-500 hover:text-red-700 remove-cart-item">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-8 flex flex-col md:flex-row md:justify-between">
                    <div class="mb-4 md:mb-0">
                        <a href="/cart/clear" class="inline-flex items-center text-gray-600 hover:text-red-500 clear-cart-link">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Clear Cart
                        </a>
                    </div>
                    
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <div class="flex justify-between mb-2">
                            <span class="font-medium">Subtotal:</span>
                            <span class="font-bold cart-subtotal"><?= number_format($cartTotal, 2) ?> MAD</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-medium">Shipping:</span>
                            <span>Calculated at checkout</span>
                        </div>
                        <div class="border-t border-gray-200 my-4"></div>
                        <div class="flex justify-between">
                            <span class="text-lg font-bold">Total:</span>
                            <span class="text-lg font-bold text-accent-terracotta cart-total"><?= number_format($cartTotal, 2) ?> MAD</span>
                        </div>
                        
                        <div class="mt-6">
                            <a href="/checkout" class="block w-full py-3 px-6 bg-accent-ochre text-white font-medium text-center rounded-md hover:bg-accent-terracotta transition">
                                Proceed to Checkout
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>