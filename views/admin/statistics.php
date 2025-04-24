<div class="space-y-6">
    <!-- Page Header -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-admin-primary">Platform Statistics</h1>
                <p class="text-gray-600 mt-1">View overall platform performance and metrics</p>
            </div>
            
            <!-- Date Range Selector -->
            <div class="mt-4 md:mt-0">
                <form method="get" class="flex">
                    <select name="range" onchange="this.form.submit()" class="bg-white border border-gray-300 rounded-md shadow-sm py-2 px-3 text-gray-700 focus:outline-none focus:ring-admin-accent focus:border-admin-accent">
                        <?php foreach ($rangeOptions as $value => $label): ?>
                            <option value="<?= $value ?>" <?= $dateRange === $value ? 'selected' : '' ?>>
                                <?= $label ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Summary Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Users Card -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="p-4 bg-blue-500 text-white">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-semibold">Users</h3>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
            </div>
            <div class="p-4">
                <div class="flex justify-between items-center">
                    <span class="text-gray-500 text-sm">Total Users</span>
                    <span class="text-2xl font-bold text-admin-primary"><?= $stats['userStats']['total'] ?? 0 ?></span>
                </div>
                <div class="flex justify-between items-center mt-2">
                    <span class="text-gray-500 text-sm">New This Month</span>
                    <span class="text-lg font-semibold text-gray-700"><?= $stats['userStats']['new'] ?? 0 ?></span>
                </div>
                <div class="flex justify-between items-center mt-2">
                    <span class="text-gray-500 text-sm">Active Users</span>
                    <span class="text-lg font-semibold text-gray-700"><?= $stats['userStats']['active'] ?? 0 ?></span>
                </div>
            </div>
        </div>
        
        <!-- Vendors Card -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="p-4 bg-yellow-500 text-white">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-semibold">Vendors</h3>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
            </div>
            <div class="p-4">
                <div class="flex justify-between items-center">
                    <span class="text-gray-500 text-sm">Total Vendors</span>
                    <span class="text-2xl font-bold text-admin-primary"><?= $stats['vendorStats']['total'] ?? 0 ?></span>
                </div>
                <div class="flex justify-between items-center mt-2">
                    <span class="text-gray-500 text-sm">Pending Approval</span>
                    <span class="text-lg font-semibold text-yellow-600"><?= $stats['vendorStats']['pending'] ?? 0 ?></span>
                </div>
                <div class="flex justify-between items-center mt-2">
                    <span class="text-gray-500 text-sm">Active Vendors</span>
                    <span class="text-lg font-semibold text-gray-700"><?= $stats['vendorStats']['active'] ?? 0 ?></span>
                </div>
            </div>
        </div>
        
        <!-- Orders Card -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="p-4 bg-green-500 text-white">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-semibold">Orders</h3>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                </div>
            </div>
            <div class="p-4">
                <div class="flex justify-between items-center">
                    <span class="text-gray-500 text-sm">Total Orders</span>
                    <span class="text-2xl font-bold text-admin-primary"><?= $stats['orderStats']['total'] ?? 0 ?></span>
                </div>
                <div class="flex justify-between items-center mt-2">
                    <span class="text-gray-500 text-sm">Completed</span>
                    <span class="text-lg font-semibold text-gray-700"><?= $stats['orderStats']['completed'] ?? 0 ?></span>
                </div>
                <div class="flex justify-between items-center mt-2">
                    <span class="text-gray-500 text-sm">Conversion Rate</span>
                    <span class="text-lg font-semibold text-gray-700">
                        <?php
                        if (isset($stats['orderStats']['total']) && isset($stats['userStats']['total']) && $stats['userStats']['total'] > 0) {
                            echo round(($stats['orderStats']['total'] / $stats['userStats']['total']) * 100, 1) . '%';
                        } else {
                            echo '0%';
                        }
                        ?>
                    </span>
                </div>
            </div>
        </div>
        
        <!-- Revenue Card -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="p-4 bg-purple-500 text-white">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-semibold">Revenue</h3>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <div class="p-4">
                <div class="flex justify-between items-center">
                    <span class="text-gray-500 text-sm">Total Revenue</span>
                    <span class="text-2xl font-bold text-admin-primary"><?= number_format($stats['orderStats']['revenue'] ?? 0, 2) ?> MAD</span>
                </div>
                <div class="flex justify-between items-center mt-2">
                    <span class="text-gray-500 text-sm">Avg. Order Value</span>
                    <span class="text-lg font-semibold text-gray-700">
                        <?php
                        if (isset($stats['orderStats']['revenue']) && isset($stats['orderStats']['total']) && $stats['orderStats']['total'] > 0) {
                            echo number_format($stats['orderStats']['revenue'] / $stats['orderStats']['total'], 2) . ' MAD';
                        } else {
                            echo '0.00 MAD';
                        }
                        ?>
                    </span>
                </div>
                <div class="flex justify-between items-center mt-2">
                    <span class="text-gray-500 text-sm">Products</span>
                    <span class="text-lg font-semibold text-gray-700"><?= $stats['productStats']['total'] ?? 0 ?></span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Users Registration Chart -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-admin-primary">User Registrations</h2>
            </div>
            <div class="p-6">
                <canvas id="userRegistrationChart" height="300"></canvas>
            </div>
        </div>
        
        <!-- Revenue Chart -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-admin-primary">Monthly Revenue</h2>
            </div>
            <div class="p-6">
                <canvas id="revenueChart" height="300"></canvas>
            </div>
        </div>
    </div>
    
    <!-- Additional Statistics -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Product Categories Distribution -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-admin-primary">Product Categories Distribution</h2>
            </div>
            <div class="p-6">
                <?php if (empty($stats['productStats']['categories'])): ?>
                <div class="bg-gray-50 border border-dashed border-gray-300 rounded-lg h-64 flex items-center justify-center">
                    <div class="text-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" />
                        </svg>
                        <p class="text-gray-500 mb-2">No product categories data available</p>
                        <p class="text-gray-400 text-sm">Add products to see category distribution</p>
                    </div>
                </div>
                <?php else: ?>
                <canvas id="categoryChart" height="300"></canvas>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- User/Vendor Growth Comparison -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-admin-primary">User & Vendor Growth</h2>
            </div>
            <div class="p-6">
                <canvas id="growthChart" height="300"></canvas>
            </div>
        </div>
    </div>
    
    <!-- Data Export Section -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h2 class="text-lg font-semibold text-admin-primary mb-4">Export Data</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <a href="/admin/export/users" class="flex items-center justify-center px-4 py-2 bg-admin-primary text-white rounded-md hover:bg-admin-secondary transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Export Users CSV
            </a>
            <a href="/admin/export/vendors" class="flex items-center justify-center px-4 py-2 bg-admin-primary text-white rounded-md hover:bg-admin-secondary transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Export Vendors CSV
            </a>
            <a href="/admin/export/orders" class="flex items-center justify-center px-4 py-2 bg-admin-primary text-white rounded-md hover:bg-admin-secondary transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Export Orders CSV
            </a>
            <a href="/admin/export/products" class="flex items-center justify-center px-4 py-2 bg-admin-primary text-white rounded-md hover:bg-admin-secondary transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Export Products CSV
            </a>
        </div>
    </div>
</div>

<!-- Add Chart.js for the charts -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Setup chart colors
        const chartColors = {
            blue: '#3b82f6',
            blueLight: 'rgba(59, 130, 246, 0.1)',
            green: '#10b981',
            greenLight: 'rgba(16, 185, 129, 0.1)',
            yellow: '#f59e0b',
            yellowLight: 'rgba(245, 158, 11, 0.1)',
            purple: '#8b5cf6',
            purpleLight: 'rgba(139, 92, 246, 0.1)',
            adminPrimary: '#2a3f5e',
            adminAccent: '#f0b429'
        };
        
        // Initialize user registration chart
        const userCtx = document.getElementById('userRegistrationChart');
        if (userCtx) {
            new Chart(userCtx, {
                type: 'line',
                data: {
                    labels: <?= json_encode($stats['chartData']['months']) ?>,
                    datasets: [{
                        label: 'User Registrations',
                        data: <?= json_encode($stats['chartData']['userRegistrations']) ?>,
                        borderColor: chartColors.blue,
                        backgroundColor: chartColors.blueLight,
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        title: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0
                            }
                        }
                    }
                }
            });
        }
        
        // Initialize revenue chart
        const revenueCtx = document.getElementById('revenueChart');
        if (revenueCtx) {
            new Chart(revenueCtx, {
                type: 'bar',
                data: {
                    labels: <?= json_encode($stats['chartData']['months']) ?>,
                    datasets: [{
                        label: 'Revenue (MAD)',
                        data: <?= json_encode($stats['chartData']['revenue']) ?>,
                        backgroundColor: chartColors.green
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        title: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }
        
        // Initialize category distribution chart if we have categories
        <?php if (!empty($stats['productStats']['categories'])): ?>
        const categoryCtx = document.getElementById('categoryChart');
        if (categoryCtx) {
            new Chart(categoryCtx, {
                type: 'pie',
                data: {
                    labels: <?= json_encode(array_column($stats['productStats']['categories'], 'name')) ?>,
                    datasets: [{
                        data: <?= json_encode(array_column($stats['productStats']['categories'], 'count')) ?>,
                        backgroundColor: [
                            chartColors.blue,
                            chartColors.green,
                            chartColors.yellow,
                            chartColors.purple,
                            '#e11d48',
                            '#7e22ce',
                            '#0e7490',
                            '#7f1d1d',
                            '#374151'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'right'
                        }
                    }
                }
            });
        }
        <?php endif; ?>
        
        // Initialize user & vendor growth comparison chart
        const growthCtx = document.getElementById('growthChart');
        if (growthCtx) {
            // For demonstration purposes, we'll create simulated data
            // In a real implementation, this would come from the backend
            const months = <?= json_encode($stats['chartData']['months']) ?>;
            
            // Create dummy data for vendors (half of users)
            const userRegistrations = <?= json_encode($stats['chartData']['userRegistrations']) ?>;
            const vendorRegistrations = userRegistrations.map(val => Math.floor(val / 2));
            
            new Chart(growthCtx, {
                type: 'line',
                data: {
                    labels: months,
                    datasets: [
                        {
                            label: 'Users',
                            data: userRegistrations,
                            borderColor: chartColors.blue,
                            backgroundColor: chartColors.blueLight,
                            tension: 0.4,
                            fill: false
                        },
                        {
                            label: 'Vendors',
                            data: vendorRegistrations,
                            borderColor: chartColors.adminPrimary,
                            backgroundColor: 'rgba(42, 63, 94, 0.1)',
                            tension: 0.4,
                            fill: false
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        title: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0
                            }
                        }
                    }
                }
            });
        }
    });
</script>