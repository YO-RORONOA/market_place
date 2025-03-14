<?php $title = 'Order Cancelled'; ?>

<div class="bg-white rounded-lg shadow-sm overflow-hidden">
    <div class="p-6 md:p-8 text-center">
        <div class="mb-6 text-gray-400">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
        
        <h1 class="text-3xl font-bold text-accent-navy mb-4">Order Cancelled</h1>
        
        <p class="text-lg mb-6">
            Your payment was cancelled and no charges were made.
        </p>
        
        <p class="mb-8">
            If you experienced any issues during checkout or have questions about your order,
            please don't hesitate to contact our customer support team.
        </p>
        
        <div class="flex flex-col sm:flex-row justify-center gap-4">
            <a href="/cart" class="py-2 px-6 bg-accent-navy text-white font-medium rounded-md hover:bg-accent-teal transition">
                Return to Cart
            </a>
            <a href="/products" class="py-2 px-6 bg-accent-ochre text-white font-medium rounded-md hover:bg-accent-terracotta transition">
                Continue Shopping
            </a>
        </div>
    </div>
</div>