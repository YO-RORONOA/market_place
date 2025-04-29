<div class="space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-4">
        <h1 class="text-2xl font-bold text-accent-navy">Store Statistics</h1>
        
        <!-- Date range filter -->
        <div class="flex items-center space-x-2">
            <label for="dateRange" class="text-sm text-gray-600">Time Period:</label>
            <select id="dateRange" class="border border-gray-300 rounded-md text-sm p-1 focus:outline-none focus:ring-1 focus:ring-accent-teal">
                <option value="7days">Last 7 Days</option>
                <option value="30days" selected>Last 30 Days</option>
                <option value="90days">Last 3 Months</option>
                <option value="year">Last Year</option>
                <option value="all">All Time</option>
            </select>
        </div>
    </div>

    <!-- Stats overview cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Revenue Card -->
        <div class="bg-white rounded-lg shadow-sm p-4 border-l-4 border-accent-teal">
            <div class="flex justify-between">
                <div>
                    <h3 class="text-sm font-medium text-gray-500">Total Revenue</h3>
                    <p class="text-2xl font-bold text-accent-navy"><?= number_format($stats['revenue'] ?? 0, 2) ?> MAD</p>
                </div>
                <div class="p-2 bg-accent-teal bg-opacity-10 rounded-full h-10 w-10 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-accent-teal" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <div class="mt-2 text-sm">
                <span class="text-gray-500">Period: </span>
                <span class="font-medium"><?= number_format($stats['periodRevenue'] ?? 0, 2) ?> MAD</span>
            </div>
        </div>

        <!-- Orders Card -->
        <div class="bg-white rounded-lg shadow-sm p-4 border-l-4 border-accent-ochre">
            <div class="flex justify-between">
                <div>
                    <h3 class="text-sm font-medium text-gray-500">Total Orders</h3>
                    <p class="text-2xl font-bold text-accent-navy"><?= $stats['totalOrders'] ?? 0 ?></p>
                </div>
                <div class="p-2 bg-accent-ochre bg-opacity-10 rounded-full h-10 w-10 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-accent-ochre" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
            </div>
            <div class="mt-2 text-sm">
                <span class="text-gray-500">Completed: </span>
                <span class="font-medium"><?= $stats['completedOrders'] ?? 0 ?></span>
            </div>
        </div>

        <!-- Products Card -->
        <div class="bg-white rounded-lg shadow-sm p-4 border-l-4 border-accent-ceramicblue">
            <div class="flex justify-between">
                <div>
                    <h3 class="text-sm font-medium text-gray-500">Products</h3>
                    <p class="text-2xl font-bold text-accent-navy"><?= $stats['totalProducts'] ?? 0 ?></p>
                </div>
                <div class="p-2 bg-accent-ceramicblue bg-opacity-10 rounded-full h-10 w-10 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-accent-ceramicblue" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                </div>
            </div>
            <div class="mt-2 text-sm">
                <span class="text-gray-500">Active: </span>
                <span class="font-medium"><?= $stats['activeProducts'] ?? 0 ?></span>
            </div>
        </div>

        <!-- Conversion Rate Card -->
        <div class="bg-white rounded-lg shadow-sm p-4 border-l-4 border-accent-terracotta">
            <div class="flex justify-between">
                <div>
                    <h3 class="text-sm font-medium text-gray-500">Avg. Order Value</h3>
                    <p class="text-2xl font-bold text-accent-navy">
                        <?= ($stats['totalOrders'] ?? 0) > 0 
                            ? number_format(($stats['revenue'] ?? 0) / ($stats['totalOrders'] ?? 1), 2) 
                            : '0.00' ?> MAD
                    </p>
                </div>
                <div class="p-2 bg-accent-terracotta bg-opacity-10 rounded-full h-10 w-10 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-accent-terracotta" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                </div>
            </div>
            <div class="mt-2 text-sm">
                <span class="text-gray-500">This period: </span>
                <span class="font-medium">
                    <?= ($stats['periodOrders'] ?? 0) > 0 
                        ? number_format(($stats['periodRevenue'] ?? 0) / ($stats['periodOrders'] ?? 1), 2) 
                        : '0.00' ?> MAD
                </span>
            </div>
        </div>
    </div>

    <!-- Order Status Breakdown -->
    <div class="bg-white rounded-lg shadow-sm p-4 md:p-6">
        <h2 class="text-lg font-semibold text-accent-navy mb-4">Order Status Breakdown</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4 text-center">
            <div class="bg-yellow-50 rounded-lg p-3">
                <div class="text-yellow-600 font-medium">Pending</div>
                <div class="text-xl font-bold mt-1"><?= $stats['orderStatus']['pending'] ?? 0 ?></div>
                <div class="text-xs text-gray-500 mt-1">
                    <?= ($stats['totalOrders'] ?? 0) > 0 
                        ? number_format((($stats['orderStatus']['pending'] ?? 0) / ($stats['totalOrders'] ?? 1)) * 100, 1) 
                        : '0' ?>%
                </div>
            </div>
            
            <div class="bg-blue-50 rounded-lg p-3">
                <div class="text-blue-600 font-medium">Processing</div>
                <div class="text-xl font-bold mt-1"><?= $stats['orderStatus']['processing'] ?? 0 ?></div>
                <div class="text-xs text-gray-500 mt-1">
                    <?= ($stats['totalOrders'] ?? 0) > 0 
                        ? number_format((($stats['orderStatus']['processing'] ?? 0) / ($stats['totalOrders'] ?? 1)) * 100, 1) 
                        : '0' ?>%
                </div>
            </div>
            
            <div class="bg-indigo-50 rounded-lg p-3">
                <div class="text-indigo-600 font-medium">Shipped</div>
                <div class="text-xl font-bold mt-1"><?= $stats['orderStatus']['shipped'] ?? 0 ?></div>
                <div class="text-xs text-gray-500 mt-1">
                    <?= ($stats['totalOrders'] ?? 0) > 0 
                        ? number_format((($stats['orderStatus']['shipped'] ?? 0) / ($stats['totalOrders'] ?? 1)) * 100, 1) 
                        : '0' ?>%
                </div>
            </div>
            
            <div class="bg-green-50 rounded-lg p-3">
                <div class="text-green-600 font-medium">Completed</div>
                <div class="text-xl font-bold mt-1"><?= $stats['orderStatus']['completed'] ?? 0 ?></div>
                <div class="text-xs text-gray-500 mt-1">
                    <?= ($stats['totalOrders'] ?? 0) > 0 
                        ? number_format((($stats['orderStatus']['completed'] ?? 0) / ($stats['totalOrders'] ?? 1)) * 100, 1) 
                        : '0' ?>%
                </div>
            </div>
            
            <div class="bg-red-50 rounded-lg p-3">
                <div class="text-red-600 font-medium">Cancelled</div>
                <div class="text-xl font-bold mt-1"><?= $stats['orderStatus']['cancelled'] ?? 0 ?></div>
                <div class="text-xs text-gray-500 mt-1">
                    <?= ($stats['totalOrders'] ?? 0) > 0 
                        ? number_format((($stats['orderStatus']['cancelled'] ?? 0) / ($stats['totalOrders'] ?? 1)) * 100, 1) 
                        : '0' ?>%
                </div>
            </div>
        </div>
    </div>

    <!-- Top Products -->
    <div class="bg-white rounded-lg shadow-sm p-4 md:p-6">
        <h2 class="text-lg font-semibold text-accent-navy mb-4">Top Selling Products</h2>
        
        <?php if (empty($topProducts)): ?>
            <div class="py-8 text-center text-gray-500">
                No product sales data available yet.
            </div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Units Sold</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Revenue</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($topProducts as $product): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <?php if (!empty($product['image_path'])): ?>
                                                <img class="h-10 w-10 rounded-full object-cover" src="<?= htmlspecialchars($product['image_path']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                                            <?php else: ?>
                                                <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-500">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                    </svg>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900"><?= htmlspecialchars($product['name']) ?></div>
                                            <div class="text-sm text-gray-500">ID: <?= $product['id'] ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?= number_format($product['price'], 2) ?> MAD
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?= $product['units_sold'] ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">
                                    <?= number_format($product['revenue'], 2) ?> MAD
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

    <!-- Monthly Breakdown -->
    <div class="bg-white rounded-lg shadow-sm p-4 md:p-6">
        <h2 class="text-lg font-semibold text-accent-navy mb-4">Monthly Performance</h2>
        
        <?php if (empty($monthlyData)): ?>
            <div class="py-8 text-center text-gray-500">
                No monthly data available yet.
            </div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Month</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Orders</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Revenue</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Avg. Order Value</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($monthlyData as $month): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    <?= htmlspecialchars($month['month']) ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?= $month['orders'] ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?= number_format($month['revenue'], 2) ?> MAD
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?= number_format($month['orders'] > 0 ? $month['revenue'] / $month['orders'] : 0, 2) ?> MAD
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
    // Date range filter functionality
    const dateRangeSelect = document.getElementById('dateRange');
    
    if (dateRangeSelect) {
        // Set initial value from URL parameter if exists
        const urlParams = new URLSearchParams(window.location.search);
        const rangeParam = urlParams.get('range');
        
        if (rangeParam) {
            dateRangeSelect.value = rangeParam;
        }
        
        // Handle change event
        dateRangeSelect.addEventListener('change', function() {
            window.location.href = '/seller/statistics?range=' + this.value;
        });
    }
});
</script>