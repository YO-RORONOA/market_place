<?php
/**
 * @var array $orders
 * @var int $totalOrders
 * @var array $availableStatuses
 * @var string $currentStatus
 */
?>

<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">My Orders</h1>
    
    <!-- Status filter -->
    <div class="bg-white rounded-lg shadow p-4 mb-6">
        <div class="flex flex-wrap items-center">
            <div class="w-full md:w-auto mr-4 mb-2 md:mb-0">
                <label for="status-filter" class="block text-sm font-medium text-gray-700 mb-1">Filter by Status:</label>
                <select id="status-filter" class="block w-full md:w-auto px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-accent-teal focus:border-accent-teal">
                    <option value="">All Orders</option>
                    <?php foreach ($availableStatuses as $statusKey => $statusLabel): ?>
                        <option value="<?= $statusKey ?>" <?= $currentStatus === $statusKey ? 'selected' : '' ?>>
                            <?= $statusLabel ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="w-full md:w-auto flex items-end">
                <button type="button" id="apply-filter" class="bg-accent-teal hover:bg-accent-teal-dark text-white font-bold py-2 px-4 rounded">
                    Apply Filter
                </button>
                <button type="button" id="clear-filter" class="ml-2 text-gray-600 hover:text-gray-900 font-medium py-2 px-4">
                    Clear Filter
                </button>
            </div>
        </div>
    </div>
    
    <!-- Loading indicator -->
    <div id="loading-indicator" class="hidden">
        <div class="flex justify-center items-center py-8">
            <svg class="animate-spin -ml-1 mr-3 h-8 w-8 text-accent-teal" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span class="text-gray-600 text-lg">Loading orders...</span>
        </div>
    </div>
    
    <!-- Orders table container -->
    <div id="orders-container">
        <?php if (empty($orders)): ?>
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <?php if (!empty($currentStatus)): ?>
                    <p class="text-gray-600">No orders found with status: <strong><?= $availableStatuses[$currentStatus] ?? $currentStatus ?></strong></p>
                    <button id="view-all-orders" class="inline-block mt-4 text-accent-teal hover:text-accent-teal-dark font-medium">
                        View All Orders
                    </button>
                <?php else: ?>
                    <p class="text-gray-600">You haven't placed any orders yet.</p>
                    <a href="/products" class="inline-block mt-4 bg-accent-teal hover:bg-accent-teal-dark text-white font-bold py-2 px-4 rounded">
                        Start Shopping
                    </a>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <p class="mb-4 text-gray-600" id="orders-count">
                <?php if (!empty($currentStatus)): ?>
                    Showing <?= count($orders) ?> orders with status: <strong id="current-status-label"><?= $availableStatuses[$currentStatus] ?? $currentStatus ?></strong>
                <?php else: ?>
                    Showing all <?= count($orders) ?> orders
                <?php endif; ?>
            </p>
            
            <div class="bg-white rounded-lg shadow overflow-hidden mb-6">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Order ID
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Date
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Total
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200" id="orders-table-body">
                        <?php foreach ($orders as $order): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    #<?= htmlspecialchars($order['id']) ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?= date('M d, Y', strtotime($order['created_at'])) ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    $<?= number_format($order['total_amount'], 2) ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
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
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <a href="/orders/view?id=<?= $order['id'] ?>" class="text-accent-teal hover:text-accent-teal-dark">
                                        View Details
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const statusFilter = document.getElementById('status-filter');
    const applyFilterBtn = document.getElementById('apply-filter');
    const clearFilterBtn = document.getElementById('clear-filter');
    const viewAllOrdersBtn = document.getElementById('view-all-orders');
    const ordersContainer = document.getElementById('orders-container');
    const loadingIndicator = document.getElementById('loading-indicator');
    
    // Status labels for reference
    const statusLabels = <?= json_encode($availableStatuses) ?>;
    
    // Function to update orders via AJAX
    function updateOrders(status = '') {
        // Show loading indicator
        ordersContainer.classList.add('hidden');
        loadingIndicator.classList.remove('hidden');
        
        // Create AJAX request
        const xhr = new XMLHttpRequest();
        xhr.open('GET', `/orders/ajax?status=${status}`, true);
        
        // Add custom header for AJAX request detection
        xhr.setRequestHeader('X-Ajax-Request', 'true');
        
        xhr.onload = function() {
            if (xhr.status === 200) {
                // Hide loading indicator
                loadingIndicator.classList.add('hidden');
                ordersContainer.classList.remove('hidden');
                
                try {
                    const response = JSON.parse(xhr.responseText);
                    updateOrdersTable(response, status);
                } catch (e) {
                    console.error('Error parsing JSON response:', e);
                }
            } else {
                console.error('Error fetching orders:', xhr.statusText);
            }
        };
        
        xhr.onerror = function() {
            console.error('Network error occurred');
            loadingIndicator.classList.add('hidden');
            ordersContainer.classList.remove('hidden');
        };
        
        xhr.send();
    }
    
    // Function to update the orders table with new data
    function updateOrdersTable(data, status) {
        // If there are no orders, show the empty state
        if (data.orders.length === 0) {
            let emptyStateHTML = '<div class="bg-white rounded-lg shadow p-6 mb-6">';
            
            if (status) {
                emptyStateHTML += `
                    <p class="text-gray-600">No orders found with status: <strong>${statusLabels[status] || status}</strong></p>
                    <button id="view-all-orders" class="inline-block mt-4 text-accent-teal hover:text-accent-teal-dark font-medium">
                        View All Orders
                    </button>
                `;
            } else {
                emptyStateHTML += `
                    <p class="text-gray-600">You haven't placed any orders yet.</p>
                    <a href="/products" class="inline-block mt-4 bg-accent-teal hover:bg-accent-teal-dark text-white font-bold py-2 px-4 rounded">
                        Start Shopping
                    </a>
                `;
            }
            
            emptyStateHTML += '</div>';
            ordersContainer.innerHTML = emptyStateHTML;
            
            // Re-attach event listener for "View All Orders" button
            const newViewAllBtn = document.getElementById('view-all-orders');
            if (newViewAllBtn) {
                newViewAllBtn.addEventListener('click', function() {
                    statusFilter.value = '';
                    updateOrders('');
                });
            }
            
            return;
        }
        
        // Update the count text
        let countHTML = '';
        if (status) {
            countHTML = `Showing ${data.orders.length} orders with status: <strong>${statusLabels[status] || status}</strong>`;
        } else {
            countHTML = `Showing all ${data.orders.length} orders`;
        }
        
        // Create rows HTML
        let rowsHTML = '';
        data.orders.forEach(order => {
            // Determine status style
            let statusStyle = 'bg-gray-100 text-gray-800';
            switch (order.status) {
                case 'processing':
                    statusStyle = 'bg-yellow-100 text-yellow-800';
                    break;
                case 'paid':
                case 'completed':
                    statusStyle = 'bg-green-100 text-green-800';
                    break;
                case 'shipped':
                    statusStyle = 'bg-blue-100 text-blue-800';
                    break;
                case 'cancelled':
                    statusStyle = 'bg-red-100 text-red-800';
                    break;
            }
            
            // Format date
            const orderDate = new Date(order.created_at);
            const formattedDate = orderDate.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
            
            rowsHTML += `
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        #${order.id}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        ${formattedDate}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        $${parseFloat(order.total_amount).toFixed(2)}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${statusStyle}">
                            ${order.status.charAt(0).toUpperCase() + order.status.slice(1)}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <a href="/orders/view?id=${order.id}" class="text-accent-teal hover:text-accent-teal-dark">
                            View Details
                        </a>
                    </td>
                </tr>
            `;
        });
        
        // Create the full table HTML
        const tableHTML = `
            <p class="mb-4 text-gray-600" id="orders-count">${countHTML}</p>
            
            <div class="bg-white rounded-lg shadow overflow-hidden mb-6">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Order ID
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Date
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Total
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200" id="orders-table-body">
                        ${rowsHTML}
                    </tbody>
                </table>
            </div>
        `;
        
        // Update the container
        ordersContainer.innerHTML = tableHTML;
    }
    
    // Event listener for apply filter button
    applyFilterBtn.addEventListener('click', function() {
        const status = statusFilter.value;
        updateOrders(status);
        
        // Update URL without refreshing the page
        const url = new URL(window.location);
        if (status) {
            url.searchParams.set('status', status);
        } else {
            url.searchParams.delete('status');
        }
        window.history.pushState({}, '', url);
    });
    
    // Event listener for clear filter button
    clearFilterBtn.addEventListener('click', function() {
        statusFilter.value = '';
        updateOrders('');
        
        // Update URL without refreshing the page
        const url = new URL(window.location);
        url.searchParams.delete('status');
        window.history.pushState({}, '', url);
    });
    
    // If view all orders button exists (in empty state)
    if (viewAllOrdersBtn) {
        viewAllOrdersBtn.addEventListener('click', function() {
            statusFilter.value = '';
            updateOrders('');
            
            // Update URL without refreshing the page
            const url = new URL(window.location);
            url.searchParams.delete('status');
            window.history.pushState({}, '', url);
        });
    }
});
</script>