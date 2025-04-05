<?php
/**
 * @var array $order
 * @var array $items
 */
?>

<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-start mb-6">
        <div>
            <h1 class="text-2xl font-bold">Order #<?= htmlspecialchars($order['id']) ?></h1>
            <p class="text-gray-600 mt-1">Placed on <?= date('F j, Y', strtotime($order['created_at'])) ?></p>
        </div>
        <a href="/orders" class="inline-flex items-center py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
            &larr; Back to Orders
        </a>
    </div>
    
    <!-- Order Status -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h2 class="text-lg font-medium mb-4">Order Status</h2>
        <div class="flex items-center">
            <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full 
                <?php
                    switch ($order['status']) {
                        case 'processing':
                            echo 'bg-yellow-100 text-yellow-800';
                            break;
                        case 'paid':
                        case 'completed':
                            echo 'bg-green-100 text-green-800';
                            break;
                        case 'shipped':
                            echo 'bg-blue-100 text-blue-800';
                            break;
                        case 'cancelled':
                            echo 'bg-red-100 text-red-800';
                            break;
                        default:
                            echo 'bg-gray-100 text-gray-800';
                    }
                ?>">
                <?= ucfirst(htmlspecialchars($order['status'])) ?>
            </span>
            
            <?php if ($order['status'] === 'shipped'): ?>
                <span class="ml-4 text-gray-600">
                    Your order has been shipped and is on its way!
                </span>
            <?php elseif ($order['status'] === 'processing'): ?>
                <span class="ml-4 text-gray-600">
                    Your order is being processed and will be shipped soon.
                </span>
            <?php elseif ($order['status'] === 'paid' || $order['status'] === 'completed'): ?>
                <span class="ml-4 text-gray-600">
                    Thank you for your payment! Your order is confirmed.
                </span>
            <?php elseif ($order['status'] === 'cancelled'): ?>
                <span class="ml-4 text-gray-600">
                    This order has been cancelled.
                </span>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Order Items -->
    <div class="bg-white rounded-lg shadow overflow-hidden mb-6">
        <h2 class="text-lg font-medium p-6 border-b border-gray-200">Order Items</h2>
        
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Product
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Price
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Quantity
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Total
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php foreach ($items as $item): ?>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <?php if (!empty($item['image_path'])): ?>
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <img class="h-10 w-10 rounded-full object-cover" src="<?= htmlspecialchars($item['image_path']) ?>" alt="<?= htmlspecialchars($item['name']) ?>">
                                    </div>
                                <?php endif; ?>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">
                                        <?= htmlspecialchars($item['name']) ?>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            $<?= number_format($item['price'], 2) ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <?= $item['quantity'] ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            $<?= number_format($item['price'] * $item['quantity'], 2) ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    
    <!-- Order Summary -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h2 class="text-lg font-medium mb-4">Order Summary</h2>
        
        <div class="border-t border-b border-gray-200 py-4 flex justify-between">
            <span class="text-gray-600">Subtotal</span>
            <span class="font-medium">$<?= number_format($order['total_amount'], 2) ?></span>
        </div>
        <div class="border-b border-gray-200 py-4 flex justify-between">
            <span class="text-gray-600">Shipping</span>
            <span class="font-medium">$0.00</span>
        </div>
        <div class="py-4 flex justify-between">
            <span class="text-lg font-medium">Total</span>
            <span class="text-lg font-bold">$<?= number_format($order['total_amount'], 2) ?></span>
        </div>
    </div>
    
    <!-- Shipping Information -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-lg font-medium mb-4">Shipping Information</h2>
        <p class="text-gray-700 whitespace-pre-line">
            <?= htmlspecialchars($order['shipping_address']) ?>
        </p>
    </div>
</div>