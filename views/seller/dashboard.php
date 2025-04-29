<!-- Mobile-specific header with hamburger menu -->
<div class="md:hidden mb-6">
    <div class="flex justify-between items-center">
        <h1 class="text-xl font-bold text-accent-navy">Vendor Dashboard</h1>
        <button id="sidebarToggle" class="p-2 rounded-md text-gray-500 hover:text-accent-navy hover:bg-gray-100 focus:outline-none">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
    </div>
</div>
<!-- Desktop heading - hidden on mobile -->
<h1 class="text-2xl font-bold text-accent-navy mb-6 hidden md:block">Welcome, <?= htmlspecialchars($vendor->store_name) ?></h1>

<!-- Stats Cards -->
<div class="grid grid-cols-1 gap-4 mb-6 md:grid-cols-3 md:gap-6 md:mb-8">
    <!-- Products Card -->
    <div class="bg-gradient-to-br from-accent-ochre to-accent-terracotta rounded-lg p-4 text-white">
        <div class="flex justify-between items-start">
            <div>
                <h3 class="text-base font-semibold opacity-90 mb-1">Products</h3>
                <p class="text-2xl font-bold"><?= $stats['productCount'] ?? 0 ?></p>
            </div>
            <div class="p-1 bg-white bg-opacity-20 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                </svg>
            </div>
        </div>
        <div class="mt-3">
            <a href="/vendor/products" class="text-xs text-white hover:text-white hover:underline font-medium flex items-center">
                View All Products
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </a>
        </div>
    </div>
    
    <!-- Orders Card -->
    <div class="bg-gradient-to-br from-accent-teal to-accent-ceramicblue rounded-lg p-4 text-white">
        <div class="flex justify-between items-start">
            <div>
                <h3 class="text-base font-semibold opacity-90 mb-1">Orders</h3>
                <p class="text-2xl font-bold"><?= $stats['orderCount'] ?? 0 ?></p>
            </div>
            <div class="p-1 bg-white bg-opacity-20 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
            </div>
        </div>
        <div class="mt-3">
            <a href="/vendor/orders" class="text-xs text-white hover:text-white hover:underline font-medium flex items-center">
                View All Orders
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </a>
        </div>
    </div>
    
    <!-- Revenue Card -->
    <div class="bg-gradient-to-br from-accent-navy to-purple-700 rounded-lg p-4 text-white">
        <div class="flex justify-between items-start">
            <div>
                <h3 class="text-base font-semibold opacity-90 mb-1">Revenue</h3>
                <p class="text-2xl font-bold"><?= number_format($stats['revenue'] ?? 0, 2) ?> MAD</p>
            </div>
            <div class="p-1 bg-white bg-opacity-20 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>
        <div class="mt-3">
            <a href="/seller/statistics" class="text-xs text-white hover:text-white hover:underline font-medium flex items-center">
                View Analytics
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </a>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="mb-6">
    <h2 class="text-lg font-semibold text-accent-navy mb-3">Quick Actions</h2>
    
    <div class="grid grid-cols-2 gap-3 md:grid-cols-3 md:gap-4">
        <a href="/vendor/products/create" class="bg-white border border-gray-200 rounded-lg p-3 flex flex-col items-center hover:bg-gray-50 transition text-center">
            <div class="p-2 bg-accent-ochre bg-opacity-10 rounded-full mb-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-accent-ochre" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
            </div>
            <h3 class="font-medium text-accent-navy text-sm">Add Product</h3>
        </a>
        
        <a href="/vendor/orders" class="bg-white border border-gray-200 rounded-lg p-3 flex flex-col items-center hover:bg-gray-50 transition text-center">
            <div class="p-2 bg-accent-teal bg-opacity-10 rounded-full mb-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-accent-teal" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
            </div>
            <h3 class="font-medium text-accent-navy text-sm">View Orders</h3>
        </a>
        
        <a href="/vendor/settings" class="bg-white border border-gray-200 rounded-lg p-3 flex flex-col items-center hover:bg-gray-50 transition text-center">
            <div class="p-2 bg-accent-navy bg-opacity-10 rounded-full mb-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-accent-navy" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </div>
            <h3 class="font-medium text-accent-navy text-sm">Store Settings</h3>
        </a>
    </div>
<!-- Recent Orders (Collapsible on mobile) -->
<div class="mb-6">
    <div class="flex justify-between items-center mb-3">
        <h2 class="text-lg font-semibold text-accent-navy">Recent Orders</h2>
        <button id="toggleOrders" class="md:hidden text-accent-teal focus:outline-none">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
        </button>
    </div>
    
    <div id="ordersContent" class="bg-white rounded-lg shadow-sm overflow-hidden">
        <!-- Orders for mobile view -->
        <div class="md:hidden divide-y divide-gray-200">
            <?php if (empty($recentOrders)): ?>
                <div class="p-4 text-center text-gray-500">
                    No orders found.
                </div>
            <?php else: ?>
                <?php foreach ($recentOrders as $order): ?>
                    <div class="p-4">
                        <div class="flex justify-between">
                            <span class="text-sm font-medium text-accent-navy">#ORD-<?= $order['id'] ?></span>
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
                        </div>
                        <div class="mt-2 flex justify-between text-sm text-gray-500">
                            <span><?= date('Y-m-d', strtotime($order['created_at'])) ?></span>
                            <span class="font-medium"><?= number_format($order['total_amount'], 2) ?> MAD</span>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        
        <!-- Orders for desktop view -->
        <table class="min-w-full divide-y divide-gray-200 hidden md:table">
            <thead class="bg-gray-100">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Order ID
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Date
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Status
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Total
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php if (empty($recentOrders)): ?>
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                            No orders found.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($recentOrders as $order): ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <a href="/vendor/orders/view?id=<?= $order['id'] ?>" class="text-sm text-accent-navy hover:text-accent-teal">
                                    #ORD-<?= $order['id'] ?>
                                </a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?= date('Y-m-d', strtotime($order['created_at'])) ?>
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
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?= number_format($order['total_amount'], 2) ?> MAD
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    
    <div class="mt-2 text-right">
        <a href="/vendor/orders" class="text-xs text-accent-teal hover:text-accent-navy font-medium inline-flex items-center">
            View All Orders
            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </a>
    </div>
</div>

<!-- Top Products Section -->
<div class="mb-6">
    <div class="flex justify-between items-center mb-3">
        <h2 class="text-lg font-semibold text-accent-navy">Top Products</h2>
        <button id="toggleProducts" class="md:hidden text-accent-teal focus:outline-none">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
        </button>
    </div>
    
    <div id="productsContent" class="bg-white rounded-lg shadow-sm p-4">
        <div class="space-y-4">
            <?php if (empty($topProducts)): ?>
                <div class="text-center text-gray-500">
                    No products found.
                </div>
            <?php else: ?>
                <?php foreach ($topProducts as $index => $product): ?>
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-gray-200 rounded-md overflow-hidden flex-shrink-0">
                            <?php if (!empty($product['image_path'])): ?>
                                <img src="<?= htmlspecialchars($product['image_path']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="w-full h-full object-cover">
                            <?php else: ?>
                                <div class="w-full h-full flex items-center justify-center bg-gray-200 text-gray-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="ml-3 flex-grow">
                            <h3 class="text-xs font-medium text-accent-navy"><?= htmlspecialchars($product['name']) ?></h3>
                            <div class="flex justify-between items-center mt-1">
                                <span class="text-xs text-gray-500"><?= number_format($product['price'] ?? 0, 2) ?> MAD</span>
                                <span class="text-xs font-medium text-accent-teal"><?= $product['total_sold'] ?? 0 ?> sold</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-1.5 mt-1.5">
                                <div class="bg-accent-teal h-1.5 rounded-full" style="width: <?= min(100, intval($product['percentage'] ?? 0)) ?>%"></div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        
        <div class="mt-4 text-right">
            <a href="/seller/statistics" class="text-xs text-accent-teal hover:text-accent-navy font-medium inline-flex items-center">
                View Analytics
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </a>
        </div>
    </div>
</div>

<!-- Monthly Sales Chart -->
<div class="mb-6">
    <div class="flex justify-between items-center mb-3">
        <h2 class="text-lg font-semibold text-accent-navy">Monthly Sales</h2>
    </div>
    
    <div class="bg-white rounded-lg shadow-sm p-4">
        <?php if (empty($monthlySales)): ?>
            <div class="text-center text-gray-500 py-8">
                No sales data available yet.
            </div>
        <?php else: ?>
            <div class="h-64">
                <canvas id="salesChart"></canvas>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php if (!empty($monthlySales)): ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('salesChart').getContext('2d');
    
    // Prepare data from PHP
    const months = <?= json_encode(array_column($monthlySales, 'month')) ?>;
    const sales = <?= json_encode(array_column($monthlySales, 'total_sales')) ?>;
    const orderCounts = <?= json_encode(array_column($monthlySales, 'order_count')) ?>;
    
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: months,
            datasets: [
                {
                    label: 'Sales (MAD)',
                    data: sales,
                    backgroundColor: 'rgba(26, 127, 134, 0.7)',
                    borderColor: 'rgb(26, 127, 134)',
                    borderWidth: 1
                },
                {
                    label: 'Orders',
                    data: orderCounts,
                    type: 'line',
                    fill: false,
                    borderColor: 'rgb(201, 82, 39)',
                    tension: 0.1,
                    yAxisID: 'y1'
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Sales (MAD)'
                    }
                },
                y1: {
                    beginAtZero: true,
                    position: 'right',
                    grid: {
                        drawOnChartArea: false
                    },
                    title: {
                        display: true,
                        text: 'Order Count'
                    }
                }
            }
        }
    });
});
</script>
<?php endif; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Mobile nav functionality
    const sidebarToggle = document.getElementById('sidebarToggle');
    const mobileSidebar = document.getElementById('mobileSidebar');
    const sidebarBackdrop = document.getElementById('sidebarBackdrop');
    const closeSidebar = document.getElementById('closeSidebar');
    
    if (sidebarToggle && mobileSidebar) {
        // Open sidebar when hamburger is clicked
        sidebarToggle.addEventListener('click', function() {
            mobileSidebar.classList.remove('-translate-x-full');
            document.body.classList.add('overflow-hidden'); // Prevent scrolling when sidebar is open
        });
        
        // Close sidebar functions
        const closeSidebarFunction = function() {
            mobileSidebar.classList.add('-translate-x-full');
            document.body.classList.remove('overflow-hidden');
        };
        
        if (closeSidebar) {
            closeSidebar.addEventListener('click', closeSidebarFunction);
        }
        
        if (sidebarBackdrop) {
            sidebarBackdrop.addEventListener('click', closeSidebarFunction);
        }
    }
    
    // Collapsible sections on mobile
    const toggleOrders = document.getElementById('toggleOrders');
    const ordersContent = document.getElementById('ordersContent');
    const toggleProducts = document.getElementById('toggleProducts');
    const productsContent = document.getElementById('productsContent');
    
    if (toggleOrders && ordersContent) {
        toggleOrders.addEventListener('click', function() {
            ordersContent.classList.toggle('hidden');
            // Toggle icon
            const path = this.querySelector('svg path');
            if (path) {
                if (path.getAttribute('d').includes('M19 9l-7 7-7-7')) {
                    path.setAttribute('d', 'M5 15l7-7 7 7');
                } else {
                    path.setAttribute('d', 'M19 9l-7 7-7-7');
                }
            }
        });
    }
    
    if (toggleProducts && productsContent) {
        toggleProducts.addEventListener('click', function() {
            productsContent.classList.toggle('hidden');
            // Toggle icon
            const path = this.querySelector('svg path');
            if (path) {
                if (path.getAttribute('d').includes('M19 9l-7 7-7-7')) {
                    path.setAttribute('d', 'M5 15l7-7 7 7');
                } else {
                    path.setAttribute('d', 'M19 9l-7 7-7-7');
                }
            }
        });
    }
});
</script>
    
    <!-- Mobile Navigation Menu (hidden by default) -->
    <div id="mobileSidebar" class="fixed inset-0 flex z-40 transform -translate-x-full lg:hidden" role="dialog" aria-modal="true">
        <!-- Background overlay -->
        <div id="sidebarBackdrop" class="fixed inset-0 bg-gray-600 bg-opacity-75"></div>
        
        <!-- Sidebar panel -->
        <div class="relative flex-1 flex flex-col max-w-xs w-full bg-white pt-5 pb-4">
            <!-- Close button -->
            <div class="absolute top-0 right-0 -mr-12 pt-2">
                <button id="closeSidebar" class="ml-1 flex items-center justify-center h-10 w-10 rounded-full focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white">
                    <span class="sr-only">Close sidebar</span>
                    <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <!-- Sidebar content -->
            <div class="mt-5 flex-1 h-0 overflow-y-auto">
                <nav class="px-2">
                    <div class="space-y-1">
                        <a href="/vendor/dashboard" class="group flex items-center px-2 py-2 text-base font-medium rounded-md text-accent-navy bg-gray-100">
                            <svg class="mr-4 h-6 w-6 text-accent-teal" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                            Dashboard
                        </a>
                        
                        <a href="/vendor/products" class="group flex items-center px-2 py-2 text-base font-medium rounded-md text-gray-600 hover:bg-gray-50 hover:text-accent-navy">
                            <svg class="mr-4 h-6 w-6 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                            Products
                        </a>
                        
                        <a href="/vendor/orders" class="group flex items-center px-2 py-2 text-base font-medium rounded-md text-gray-600 hover:bg-gray-50 hover:text-accent-navy">
                            <svg class="mr-4 h-6 w-6 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            Orders
                        </a>
                        
                        <a href="/seller/statistics" class="group flex items-center px-2 py-2 text-base font-medium rounded-md text-gray-600 hover:bg-gray-50 hover:text-accent-navy">
                            <svg class="mr-4 h-6 w-6 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                            Analytics
                        </a>
                        
                        <a href="/vendor/settings" class="group flex items-center px-2 py-2 text-base font-medium rounded-md text-gray-600 hover:bg-gray-50 hover:text-accent-navy">
                            <svg class="mr-4 h-6 w-6 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Settings
                        </a>
                        
                        <a href="/products" class="group flex items-center px-2 py-2 text-base font-medium rounded-md text-gray-600 hover:bg-gray-50 hover:text-accent-navy">
                            <svg class="mr-4 h-6 w-6 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            View Marketplace
                        </a>
                        
                        <a href="/logout" class="group flex items-center px-2 py-2 text-base font-medium rounded-md text-gray-600 hover:bg-gray-50 hover:text-accent-navy">
                            <svg class="mr-4 h-6 w-6 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            Logout
                        </a>
                    </div>
                </nav>
            </div>
        </div>
    </div>
</div>

