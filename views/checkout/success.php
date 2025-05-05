<?php $title = 'Order Confirmation'; ?>

<div class="bg-white rounded-lg shadow-sm overflow-hidden">
    <div class="p-6 md:p-8 text-center">
        <div class="mb-6 text-green-500">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
        
        <h1 class="text-3xl font-bold text-accent-navy mb-4">Thank You for Your Order!</h1>
        
        <p class="text-lg mb-6">
            Your payment was successful and your order has been received.
        </p>
        
        <div class="bg-gray-50 p-4 rounded-lg mx-auto max-w-md mb-8">
            <p class="mb-2">
                <span class="font-medium">Order Reference:</span> 
                <span class="text-accent-navy"><?= htmlspecialchars(substr($sessionId, 0, 12)) ?></span>
            </p>
            <p>
                <span class="font-medium">Status:</span> 
                <span class="text-green-600 font-medium">Payment Successful</span>
            </p>
        </div>
        
        <p class="mb-6">
            We've sent a confirmation email with your order details.
            You can track your order from your account dashboard.
        </p>
        
        <div class="flex flex-col sm:flex-row justify-center gap-4">
            <a href="/orders" class="py-2 px-6 bg-accent-navy text-white font-medium rounded-md hover:bg-accent-teal transition">
                View Your Orders
            </a>
            <a href="/products" class="py-2 px-6 bg-accent-ochre text-white font-medium rounded-md hover:bg-accent-terracotta transition">
                Continue Shopping
            </a>
        </div>
    </div>
</div>