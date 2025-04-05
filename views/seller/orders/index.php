<div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
    <h1 class="text-2xl font-bold text-accent-navy">Manage Orders</h1>
    
    <!-- Order stats summary -->
    <div class="flex space-x-2">
        <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-medium">
            Pending: <?= $orderStats['pending'] ?? 0 ?>
        </span>
        <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-medium">
            Processing: <?= $orderStats['processing'] ?? 0 ?>
        </span>
        <span class="px-3 py-1 bg-indigo-100 text-indigo-800 rounded-full text-xs font-medium">
            Shipped: <?= $orderStats['shipped'] ?? 0 ?>
        </span>
        <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium">
            Completed: <?= $orderStats['completed'] ?? 0 ?>
        </span>
    </div>
</div>

<!-- Filter and search controls -->
<div class="bg-white rounded-lg shadow-sm p-4 mb-6">
    <form action="/vendor/orders" method="get" class="grid grid-cols-1 md:grid-cols-5 gap-4">
        <div>
            <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
            <select id="status" name="status" class="w-full rounded-md border-gray-300 shadow-sm focus:border-accent-teal focus:ring-accent-teal">
                <option value="">All Statuses</option>
                <option value="pending" <?= $status === 'pending' ? 'selected' : '' ?>>Pending</option>
                <option value="processing" <?= $status === 'processing' ? 'selected' : '' ?>>Processing</option>
                <option value="shipped" <?= $status === 'shipped' ? 'selected' : '' ?>>Shipped</option>
                <option value="completed" <?= $status === 'completed' ? 'selected' : '' ?>>Completed</option>
                <option value="cancelled" <?= $status === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
            </select>
        </div>
        
        <div>
            <label for="date_from" class="block text-sm font-medium text-gray-700 mb-1">From Date</label>
            <input type="date" id="date_from" name="date_from" value="<?= $_GET['date_from'] ?? '' ?>" class="w-full rounded-md border-gray-300 shadow-sm focus:border-accent-teal focus:ring-accent-teal">
        </div>
        
        <div>
            <label for="date_to" class="block text-sm font-medium text-gray-700 mb-1">To Date</label>
            <input type="date" id="date_to" name="date_to" value="<?= $_GET['date_to'] ?? '' ?>" class="w-full rounded-md border-gray-300 shadow-sm focus:border-accent-teal focus:ring-accent-teal">
        </div>
        
        <div>
            <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search Order/Customer</label>
            <input type="text" id="search" name="search" value="<?= $_GET['search'] ?? '' ?>" placeholder="Order ID or customer name" class="w-full rounded-md border-gray-300 shadow-sm focus:border-accent-teal focus:ring-accent-teal">
        </div>
        
        <div class="flex items-end">
            <button type="submit" class="bg-accent-teal hover:bg-accent-ceramicblue text-white py-2 px-4 rounded-md transition-colors duration-200">
                Filter Orders
            </button>
            <a href="/vendor/orders" class="ml-2 text-accent-teal hover:text-accent-navy py-2 px-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
            </a>
        </div>
    </form>
</div>

<!-- Bulk actions -->
<div class="bg-white rounded-lg shadow-sm p-4 mb-6">
    <form id="bulkActionsForm" class="flex flex-wrap gap-4 items-center">
        <div class="flex-grow">
            <select id="bulkAction" class="rounded-md border-gray-300 shadow-sm focus:border-accent-teal focus:ring-accent-teal">
                <option value="">Bulk Actions</option>
                <option value="mark_processing">Mark as Processing</option>
                <option value="mark_shipped">Mark as Shipped</option>
                <option value="mark_completed">Mark as Completed</option>
                <option value="export_csv">Export Selected</option>
            </select>
        </div>
        
        <button type="button" id="applyBulkAction" class="bg-accent-ochre hover:bg-accent-terracotta text-white py-2 px-4 rounded-md transition-colors duration-200" disabled>
            Apply
        </button>
        
        <div class="ml-auto">
            <span id="selectedCount" class="text-sm text-gray-600">0 orders selected</span>
        </div>
    </form>
</div>

<?php if (empty($orders)): ?>
    <div class="bg-white rounded-lg shadow-sm p-8 text-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
        </svg>
        <h2 class="text-xl font-medium text-gray-600 mb-2">No Orders Found</h2>
        <p class="text-gray-500 mb-6">There are no orders matching your current filters.</p>
        <a href="/vendor/orders" class="bg-accent-teal hover:bg-accent-ceramicblue text-white py-2 px-6 rounded-md inline-flex items-center transition-colors duration-200">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
            </svg>
            View All Orders
        </a>
    </div>
<?php else: ?>
    <!-- Orders table -->
    <div class="bg-white rounded-lg shadow-sm mb-6 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-10">
                            <input type="checkbox" id="selectAll" class="rounded text-accent-teal focus:ring-accent-teal">
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Order ID
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Date
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Customer
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Total
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php foreach ($orders as $order): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <input type="checkbox" name="selected_orders[]" value="<?= $order['id'] ?>" class="order-checkbox rounded text-accent-teal focus:ring-accent-teal">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <a href="/vendor/orders/view?id=<?= $order['id'] ?>" class="text-accent-teal hover:text-accent-ceramicblue font-medium">#<?= $order['id'] ?></a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?= date('M d, Y', strtotime($order['created_at'])) ?>
                                <div class="text-xs"><?= date('h:i A', strtotime($order['created_at'])) ?></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    <?= htmlspecialchars($order['customer']['firstname'] . ' ' . $order['customer']['lastname']) ?>
                                </div>
                                <div class="text-sm text-gray-500">
                                    <?= htmlspecialchars($order['customer']['email']) ?>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <?= number_format($order['vendor_total'], 2) ?> MAD
                                <div class="text-xs text-gray-500"><?= count($order['vendor_items']) ?> items</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    <?php
                                        switch ($order['status']) {
                                            case 'paid':
                                            case 'completed':
                                                echo 'bg-green-100 text-green-800';
                                                break;
                                            case 'pending':
                                                echo 'bg-yellow-100 text-yellow-800';
                                                break;
                                            case 'processing':
                                                echo 'bg-blue-100 text-blue-800';
                                                break;
                                            case 'shipped':
                                                echo 'bg-indigo-100 text-indigo-800';
                                                break;
                                            case 'cancelled':
                                                echo 'bg-red-100 text-red-800';
                                                break;
                                            default:
                                                echo 'bg-gray-100 text-gray-800';
                                        }
                                    ?>">
                                    <?= ucfirst($order['status']) ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end space-x-2">
                                    <a href="/vendor/orders/view?id=<?= $order['id'] ?>" class="text-accent-teal hover:text-accent-ceramicblue">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>
                                    <button type="button" class="text-accent-navy hover:text-accent-ochre quick-status-btn" data-order-id="<?= $order['id'] ?>">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <?php if ($totalPages > 1): ?>
        <div class="flex justify-center my-6">
            <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                <!-- Previous Page Link -->
                <?php if ($currentPage > 1): ?>
                    <a href="/vendor/orders?page=<?= $currentPage - 1 ?>&status=<?= $status ?>" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                        <span class="sr-only">Previous</span>
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                    </a>
                <?php else: ?>
                    <span class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-gray-100 text-sm font-medium text-gray-400">
                        <span class="sr-only">Previous</span>
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                    </span>
                <?php endif; ?>
                
                <!-- Page Number Links -->
                <?php
                    $startPage = max(1, $currentPage - 2);
                    $endPage = min($totalPages, $startPage + 4);
                    if ($endPage - $startPage < 4 && $startPage > 1) {
                        $startPage = max(1, $endPage - 4);
                    }
                    
                    for ($i = $startPage; $i <= $endPage; $i++):
                ?>
                    <?php if ($i == $currentPage): ?>
                        <span class="relative inline-flex items-center px-4 py-2 border border-accent-teal bg-accent-teal bg-opacity-10 text-sm font-medium text-accent-teal">
                            <?= $i ?>
                        </span>
                    <?php else: ?>
                        <a href="/vendor/orders?page=<?= $i ?>&status=<?= $status ?>" class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">
                            <?= $i ?>
                        </a>
                    <?php endif; ?>
                <?php endfor; ?>
                
                <!-- Next Page Link -->
                <?php if ($currentPage < $totalPages): ?>
                    <a href="/vendor/orders?page=<?= $currentPage + 1 ?>&status=<?= $status ?>" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                        <span class="sr-only">Next</span>
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                        </svg>
                    </a>
                <?php else: ?>
                    <span class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-gray-100 text-sm font-medium text-gray-400">
                        <span class="sr-only">Next</span>
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                        </svg>
                    </span>
                <?php endif; ?>
            </nav>
        </div>
    <?php endif; ?>
    
    <!-- Order Count Summary -->
    <div class="text-center text-sm text-gray-500">
        Showing <?= count($orders) ?> of <?= $totalOrders ?> orders
    </div>
<?php endif; ?>

<!-- Quick Status Change Modal -->
<div id="statusModal" class="fixed inset-0 flex items-center justify-center z-50 hidden">
    <div class="fixed inset-0 bg-black bg-opacity-50"></div>
    <div class="bg-white rounded-lg p-6 max-w-md w-full relative z-10">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Update Order Status</h3>
        <form id="quickStatusForm">
            <input type="hidden" id="statusOrderId" name="order_id" value="">
            
            <div class="mb-4">
                <label for="newStatus" class="block text-sm font-medium text-gray-700 mb-1">New Status</label>
                <select id="newStatus" name="status" class="w-full rounded-md border-gray-300 shadow-sm focus:border-accent-teal focus:ring-accent-teal">
                    <option value="pending">Pending</option>
                    <option value="processing">Processing</option>
                    <option value="shipped">Shipped</option>
                    <option value="completed">Completed</option>
                    <option value="cancelled">Cancelled</option>
                </select>
            </div>
            
            <div id="trackingNumberField" class="mb-4 hidden">
                <label for="trackingNumber" class="block text-sm font-medium text-gray-700 mb-1">Tracking Number</label>
                <input type="text" id="trackingNumber" name="tracking_number" class="w-full rounded-md border-gray-300 shadow-sm focus:border-accent-teal focus:ring-accent-teal">
            </div>
            
            <div class="mb-4">
                <label for="statusNote" class="block text-sm font-medium text-gray-700 mb-1">Note (Optional)</label>
                <textarea id="statusNote" name="note" rows="2" class="w-full rounded-md border-gray-300 shadow-sm focus:border-accent-teal focus:ring-accent-teal"></textarea>
            </div>
            
            <div class="flex justify-end gap-3">
                <button type="button" id="cancelStatusUpdate" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition-colors">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 bg-accent-teal text-white rounded-md hover:bg-accent-ceramicblue transition-colors">
                    Update Status
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Bulk selection functionality
    const selectAll = document.getElementById('selectAll');
    const orderCheckboxes = document.querySelectorAll('.order-checkbox');
    const selectedCountElement = document.getElementById('selectedCount');
    const applyBulkActionButton = document.getElementById('applyBulkAction');
    const bulkActionSelect = document.getElementById('bulkAction');
    
    if(selectAll) {
        selectAll.addEventListener('change', function() {
            const isChecked = this.checked;
            orderCheckboxes.forEach(checkbox => {
                checkbox.checked = isChecked;
            });
            updateSelectedCount();
        });
    }
    
    orderCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateSelectedCount);
    });
    
    function updateSelectedCount() {
        const selectedCount = document.querySelectorAll('.order-checkbox:checked').length;
        selectedCountElement.textContent = `${selectedCount} orders selected`;
        
        if (selectedCount > 0 && bulkActionSelect.value !== '') {
            applyBulkActionButton.disabled = false;
        } else {
            applyBulkActionButton.disabled = true;
        }
    }
    
    bulkActionSelect.addEventListener('change', updateSelectedCount);
    
    // Apply bulk action
    applyBulkActionButton.addEventListener('click', function() {
        const selectedOrders = Array.from(document.querySelectorAll('.order-checkbox:checked')).map(cb => cb.value);
        const action = bulkActionSelect.value;
        
        if (selectedOrders.length === 0 || action === '') {
            return;
        }
        
        // Here you would normally send an AJAX request to your backend
        // For now, we'll just show a confirmation
        if (action === 'export_csv') {
            alert(`Exporting ${selectedOrders.length} orders as CSV`);
            // In a real implementation, you would redirect to a download endpoint
        } else {
            const statusMap = {
                'mark_processing': 'processing',
                'mark_shipped': 'shipped',
                'mark_completed': 'completed'
            };
            
            const newStatus = statusMap[action];
            
            if (confirm(`Are you sure you want to mark ${selectedOrders.length} orders as ${newStatus}?`)) {
                // Send AJAX request to update statuses
                fetch('/vendor/orders/bulk-update-status', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        order_ids: selectedOrders,
                        status: newStatus
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(`Successfully updated ${data.updated} orders to ${newStatus}`);
                        window.location.reload();
                    } else {
                        alert('Failed to update orders: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while updating orders');
                });
            }
        }
    });
    
    // Quick status update
    const quickStatusButtons = document.querySelectorAll('.quick-status-btn');
    const statusModal = document.getElementById('statusModal');
    const statusOrderIdField = document.getElementById('statusOrderId');
    const cancelStatusUpdateButton = document.getElementById('cancelStatusUpdate');
    const newStatusSelect = document.getElementById('newStatus');
    const trackingNumberField = document.getElementById('trackingNumberField');
    
    quickStatusButtons.forEach(button => {
        button.addEventListener('click', function() {
            const orderId = this.dataset.orderId;
            statusOrderIdField.value = orderId;
            statusModal.classList.remove('hidden');
        });
    });
    
    newStatusSelect.addEventListener('change', function() {
        if (this.value === 'shipped') {
            trackingNumberField.classList.remove('hidden');
        } else {
            trackingNumberField.classList.add('hidden');
        }
    });
    
    // Hide modal when cancel is clicked
    cancelStatusUpdateButton.addEventListener('click', function() {
        statusModal.classList.add('hidden');
    });
    
    // Close modal when clicking outside
    statusModal.addEventListener('click', function(e) {
        if (e.target === statusModal) {
            statusModal.classList.add('hidden');
        }
    });
    
    // Submit quick status update
    const quickStatusForm = document.getElementById('quickStatusForm');
    
    quickStatusForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(quickStatusForm);
        const formDataObj = {};
        
        formData.forEach((value, key) => {
            formDataObj[key] = value;
        });
        
        // Send AJAX request to update status
        fetch('/vendor/orders/update-status', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify(formDataObj)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                statusModal.classList.add('hidden');
                alert('Order status updated successfully');
                window.location.reload();
            } else {
                alert('Failed to update order status: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while updating order status');
        });
    });
});
</script>