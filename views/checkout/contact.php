<?php $title = 'Checkout'; ?>

<div class="bg-white rounded-lg shadow-sm overflow-hidden">
    <div class="p-6 md:p-8">
        <h1 class="text-3xl font-bold text-accent-navy mb-8">Checkout</h1>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Order Summary -->
            <div>
                <h2 class="text-xl font-bold mb-4">Order Summary</h2>
                <div class="bg-gray-50 p-4 rounded-lg mb-6">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b">
                                <th class="text-left py-2">Product</th>
                                <th class="text-center py-2">Qty</th>
                                <th class="text-right py-2">Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($cartItems as $item): ?>
                            <tr class="border-b">
                                <td class="py-3">
                                    <div class="flex items-center">
                                        <div class="w-12 h-12 flex-shrink-0 mr-3 bg-gray-100 rounded overflow-hidden">
                                            <?php if ($item->image_path): ?>
                                                <img src="<?= htmlspecialchars($item->image_path) ?>" alt="<?= htmlspecialchars($item->name) ?>" class="w-full h-full object-cover">
                                            <?php else: ?>
                                                <div class="w-full h-full flex items-center justify-center">
                                                    <span class="text-gray-400 text-xs">No image</span>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="text-sm">
                                            <?= htmlspecialchars($item->name) ?>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3 text-center"><?= $item->quantity ?></td>
                                <td class="py-3 text-right"><?= number_format($item->price, 2) ?> MAD</td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr class="border-t">
                                <th colspan="2" class="text-left py-3">Subtotal:</th>
                                <td class="text-right py-3 font-medium"><?= number_format($cartTotal, 2) ?> MAD</td>
                            </tr>
                            <tr>
                                <th colspan="2" class="text-left py-2">Shipping:</th>
                                <td class="text-right py-2">Calculated at next step</td>
                            </tr>
                            <tr class="border-t">
                                <th colspan="2" class="text-left py-3 text-lg font-bold">Total:</th>
                                <td class="text-right py-3 text-lg font-bold text-accent-terracotta"><?= number_format($cartTotal, 2) ?> MAD</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                
                <a href="/cart" class="inline-flex items-center text-accent-teal hover:text-accent-navy transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Return to Cart
                </a>
            </div>
            
            <!-- Payment -->
            <div>
                <h2 class="text-xl font-bold mb-4">Payment Information</h2>
                <div class="bg-gray-50 p-6 rounded-lg">
                    <p class="mb-6">
                        We use Stripe to process payments securely. You will be redirected to Stripe to complete your payment.
                    </p>
                    
                    <div class="flex items-center justify-between mb-6">
                        <span>Payment method:</span>
                        <div class="flex items-center">
                            <img src="https://cdn.jsdelivr.net/gh/cferdinandi/payment-icons/src/svg/visa.svg" alt="Visa" class="h-8 mr-2">
                            <img src="https://cdn.jsdelivr.net/gh/cferdinandi/payment-icons/src/svg/mastercard.svg" alt="Mastercard" class="h-8 mr-2">
                            <img src="https://cdn.jsdelivr.net/gh/cferdinandi/payment-icons/src/svg/amex.svg" alt="American Express" class="h-8">
                        </div>
                    </div>
                    
                    <form action="/checkout/process" method="post">
                        <button type="submit" class="w-full py-3 px-6 bg-accent-ochre text-white font-medium text-center rounded-md hover:bg-accent-terracotta transition">
                            Proceed to Payment
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>