<?php
/**
 * @var array $order
 * @var array $items
 * @var bool $canCancel
 * @var array $orderProgress
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
    
    <!-- Order Status Timeline -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h2 class="text-lg font-medium mb-6">Order Status</h2>
        
        <div class="relative">
            <!-- Timeline -->
            <div class="hidden sm:block absolute left-0 inset-y-0 h-full w-1 bg-gray-200 transform translate-x-6"></div>
            
            <!-- Steps -->
            <div class="space-y-8">
                <?php foreach ($orderProgress as $index => $step): ?>
                    <div class="relative flex items-start">
                        <!-- Timeline dot -->
                        <div class="h-12 flex items-center">
                            <div class="relative z-10 flex items-center justify-center w-12 h-12 rounded-full 
                                <?php if ($step['current']): ?>
                                    <?= isset($step['cancelled']) ? 'bg-red-100 text-red-600' : 'bg-accent-teal bg-opacity-20 border-2 border-accent-teal text-accent-teal' ?>
                                <?php elseif ($step['completed']): ?>
                                    bg-accent-teal text-white
                                <?php else: ?>
                                    bg-gray-200 text-gray-400
                                <?php endif; ?>">
                                <?php if ($step['completed'] && !$step['current']): ?>
                                    <!-- Checkmark icon for completed -->
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                <?php elseif (isset($step['cancelled'])): ?>
                                    <!-- X icon for cancelled -->
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                <?php else: ?>
                                    <!-- Number for other steps -->
                                    <?= $index + 1 ?>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <!-- Step content -->
                        <div class="ml-4">
                            <h3 class="text-lg font-medium 
                                <?php if ($step['current']): ?>
                                    <?= isset($step['cancelled']) ? 'text-red-600' : 'text-accent-teal' ?>
                                <?php elseif ($step['completed']): ?>
                                    text-gray-900
                                <?php else: ?>
                                    text-gray-500
                                <?php endif; ?>">
                                <?= htmlspecialchars($step['name']) ?>
                            </h3>
                            <p class="mt-1 text-sm text-gray-500"><?= htmlspecialchars($step['description']) ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        
        <?php if ($canCancel): ?>
            <div class="mt-8 pt-6 border-t border-gray-200">
                <p class="text-gray-600 mb-4">You can still cancel this order since it hasn't been shipped yet.</p>
                <button id="cancel-order-btn" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    Cancel Order
                </button>
            </div>
        <?php endif; ?>
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

<!-- Cancel Order Confirmation Modal -->
<?php if ($canCancel): ?>
<div id="cancel-modal" class="fixed inset-0 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div id="modal-backdrop" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        
        <!-- Center modal -->
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        
        <!-- Modal panel -->
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                        <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                            Cancel Order #<?= htmlspecialchars($order['id']) ?>
                        </h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500">
                                Are you sure you want to cancel this order? This action cannot be undone.
                                <?php if ($order['status'] === 'paid' || $order['status'] === 'processing'): ?>
                                    A refund will be processed to your original payment method.
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button id="confirm-cancel" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                    Yes, Cancel Order
                </button>
                <button id="cancel-modal-close" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    No, Keep Order
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Get DOM elements
    const cancelOrderBtn = document.getElementById('cancel-order-btn');
    const cancelModal = document.getElementById('cancel-modal');
    const modalBackdrop = document.getElementById('modal-backdrop');
    const cancelModalClose = document.getElementById('cancel-modal-close');
    const confirmCancel = document.getElementById('confirm-cancel');
    
    // Function to show modal
    function showModal() {
        cancelModal.classList.remove('hidden');
    }
    
    // Function to hide modal
    function hideModal() {
        cancelModal.classList.add('hidden');
    }
    
    // Show modal when cancel button is clicked
    if (cancelOrderBtn) {
        cancelOrderBtn.addEventListener('click', showModal);
    }
    
    // Hide modal when backdrop or close button is clicked
    modalBackdrop.addEventListener('click', hideModal);
    cancelModalClose.addEventListener('click', hideModal);
    
    // Handle order cancellation
    confirmCancel.addEventListener('click', function() {
        // Show loading state
        confirmCancel.innerHTML = '<svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Processing...';
        confirmCancel.disabled = true;
        cancelModalClose.disabled = true;
        
        // Send AJAX request to cancel order
        const xhr = new XMLHttpRequest();
        xhr.open('GET', '/orders/cancel?id=<?= $order['id'] ?>', true);
        xhr.setRequestHeader('X-Ajax-Request', 'true');
        
        xhr.onload = function() {
            if (xhr.status === 200) {
                try {
                    const response = JSON.parse(xhr.responseText);
                    
                    if (response.success) {
                        // Show success message and reload the page
                        hideModal();
                        window.location.reload();
                    } else {
                        showError(response.message || 'Failed to cancel order');
                    }
                } catch (e) {
                    showError('Invalid response from server');
                }
            } else {
                showError('Failed to cancel order: ' + xhr.statusText);
            }
        };
        
        xhr.onerror = function() {
            showError('Network error occurred');
        };
        
        xhr.send();
    });
    
    // Function to show error in modal
    function showError(message) {
        // Reset button
        confirmCancel.innerHTML = 'Yes, Cancel Order';
        confirmCancel.disabled = false;
        cancelModalClose.disabled = false;
        
        // Show error message
        const modalContent = document.querySelector('#modal-title').parentNode;
        
        // Create error element if it doesn't exist
        let errorElement = document.getElementById('cancel-error');
        if (!errorElement) {
            errorElement = document.createElement('div');
            errorElement.id = 'cancel-error';
            errorElement.className = 'mt-3 text-sm text-red-600';
            modalContent.appendChild(errorElement);
        }
        
        errorElement.textContent = message;
    }
});
</script>
<?php endif; ?>