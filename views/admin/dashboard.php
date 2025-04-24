<div class="space-y-6">
    <!-- Welcome Section -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h1 class="text-2xl font-bold text-admin-primary mb-2">Welcome to Admin Dashboard</h1>
        <p class="text-gray-600">Manage vendor validations and monitor platform statistics.</p>
    </div>
    
    <!-- Summary Stats Cards -->
    <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
        <!-- Pending Vendors Card -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="p-6 bg-gradient-to-r from-admin-accent to-yellow-300">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-semibold text-admin-primary opacity-75">Pending Vendors</p>
                        <h3 class="text-3xl font-bold text-admin-primary mt-1">0</h3>
                    </div>
                    <div class="p-2 bg-white bg-opacity-25 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-admin-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                </div>
            </div>
            <div class="p-4 bg-white">
                <a href="/admin/vendors" class="text-admin-primary hover:text-admin-accent font-medium flex items-center text-sm transition-colors">
                    View Pending Vendors
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </a>
            </div>
        </div>
        
        <!-- Total Users Card -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="p-6 bg-gradient-to-r from-blue-500 to-blue-400">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-semibold text-white opacity-75">Total Users</p>
                        <h3 class="text-3xl font-bold text-white mt-1">0</h3>
                    </div>
                    <div class="p-2 bg-white bg-opacity-25 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                </div>
            </div>
            <div class="p-4 bg-white">
                <a href="/admin/statistics" class="text-admin-primary hover:text-admin-accent font-medium flex items-center text-sm transition-colors">
                    View User Statistics
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </a>
            </div>
        </div>
        
        <!-- Platform Revenue Card -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="p-6 bg-gradient-to-r from-green-500 to-green-400">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-semibold text-white opacity-75">Total Revenue</p>
                        <h3 class="text-3xl font-bold text-white mt-1">0.00 MAD</h3>
                    </div>
                    <div class="p-2 bg-white bg-opacity-25 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>
            <div class="p-4 bg-white">
                <a href="/admin/statistics" class="text-admin-primary hover:text-admin-accent font-medium flex items-center text-sm transition-colors">
                    View Financial Statistics
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </a>
            </div>
        </div>
    </div>
    
    <!-- Main Sections -->
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        <!-- Vendor Validation Section -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-admin-primary">Recent Vendor Applications</h2>
                <a href="/admin/vendors" class="text-sm font-medium text-admin-accent hover:underline">View All</a>
            </div>
            
            <!-- Empty state -->
            <div class="py-8 text-center border border-dashed border-gray-300 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
                <p class="text-gray-500 mb-2">No pending vendor applications</p>
                <p class="text-gray-400 text-sm">New applications will appear here</p>
            </div>
            
            <!-- Action buttons -->
            <div class="mt-4 flex flex-col md:flex-row gap-3">
                <a href="/admin/vendors" class="flex items-center justify-center px-4 py-2 bg-admin-primary text-white rounded-md hover:bg-admin-secondary transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Manage Vendor Applications
                </a>
            </div>
        </div>
        
        <!-- Platform Statistics Preview -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-admin-primary">Platform Statistics</h2>
                <a href="/admin/statistics" class="text-sm font-medium text-admin-accent hover:underline">View Detailed Stats</a>
            </div>
            
            <!-- Chart Placeholder -->
            <div class="h-64 border border-dashed border-gray-300 rounded-lg flex items-center justify-center bg-gray-50">
                <div class="text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    <p class="text-gray-500 mb-2">Statistics Dashboard</p>
                    <p class="text-gray-400 text-sm">User registration and order data will be displayed here</p>
                </div>
            </div>
            
            <!-- Key metrics -->
            <div class="mt-4 grid grid-cols-2 gap-3">
                <div class="border border-gray-200 rounded-lg p-3">
                    <p class="text-xs text-gray-500">Registered Users</p>
                    <p class="text-lg font-semibold text-admin-primary">0</p>
                </div>
                <div class="border border-gray-200 rounded-lg p-3">
                    <p class="text-xs text-gray-500">Active Vendors</p>
                    <p class="text-lg font-semibold text-admin-primary">0</p>
                </div>
                <div class="border border-gray-200 rounded-lg p-3">
                    <p class="text-xs text-gray-500">Total Orders</p>
                    <p class="text-lg font-semibold text-admin-primary">0</p>
                </div>
                <div class="border border-gray-200 rounded-lg p-3">
                    <p class="text-xs text-gray-500">Products Listed</p>
                    <p class="text-lg font-semibold text-admin-primary">0</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Activity Log Section -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold text-admin-primary">Recent Activity</h2>
        </div>
        
        <!-- Activity timeline -->
        <div class="relative">
            <!-- Timeline line -->
            <div class="absolute top-0 left-5 w-0.5 h-full bg-gray-200 mt-3"></div>
            
            <!-- Empty state -->
            <div class="relative py-8 pl-12 border border-dashed border-gray-300 rounded-lg">
                <!-- Timeline dot -->
                <div class="absolute left-5 top-1/2 -translate-y-1/2 -translate-x-1/2 w-3 h-3 bg-gray-400 rounded-full z-10"></div>
                
                <div class="text-center">
                    <p class="text-gray-500 mb-1">No recent activities</p>
                    <p class="text-gray-400 text-sm">System activities will be displayed here</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Quick Actions & System Status -->
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <!-- Quick Actions -->
        <div class="bg-white rounded-lg shadow-sm p-6 lg:col-span-1">
            <h2 class="text-lg font-semibold text-admin-primary mb-4">Quick Actions</h2>
            
            <div class="space-y-3">
                <a href="/admin/vendors" class="flex items-center p-3 bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors">
                    <div class="p-2 bg-yellow-100 rounded-full mr-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <div>
                        <p class="font-medium text-sm text-gray-700">Validate Vendors</p>
                        <p class="text-xs text-gray-500">Approve pending vendor applications</p>
                    </div>
                </a>
                
                <a href="/admin/statistics" class="flex items-center p-3 bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors">
                    <div class="p-2 bg-blue-100 rounded-full mr-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                    <div>
                        <p class="font-medium text-sm text-gray-700">View Statistics</p>
                        <p class="text-xs text-gray-500">Monitor platform performance</p>
                    </div>
                </a>
                
                <a href="/" class="flex items-center p-3 bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors">
                    <div class="p-2 bg-green-100 rounded-full mr-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                    </div>
                    <div>
                        <p class="font-medium text-sm text-gray-700">Go to Main Site</p>
                        <p class="text-xs text-gray-500">View the customer-facing website</p>
                    </div>
                </a>
            </div>
        </div>
        
        <!-- System Status -->
        <div class="bg-white rounded-lg shadow-sm p-6 lg:col-span-2">
            <h2 class="text-lg font-semibold text-admin-primary mb-4">System Status</h2>
            
            <div class="mb-6">
                <div class="flex items-center mb-2">
                    <div class="w-3 h-3 bg-green-500 rounded-full mr-2"></div>
                    <span class="text-sm font-medium text-gray-700">All systems operational</span>
                </div>
                <p class="text-xs text-gray-500">Last checked: <?= date('Y-m-d H:i:s') ?></p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="border border-gray-200 rounded-lg p-4">
                    <h4 class="text-sm font-medium text-gray-700 mb-2">Server Load</h4>
                    <div class="flex items-center">
                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                            <div class="bg-green-500 h-2.5 rounded-full" style="width: 15%"></div>
                        </div>
                        <span class="ml-2 text-sm text-gray-600">15%</span>
                    </div>
                </div>
                
                <div class="border border-gray-200 rounded-lg p-4">
                    <h4 class="text-sm font-medium text-gray-700 mb-2">Database</h4>
                    <div class="flex items-center">
                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                            <div class="bg-green-500 h-2.5 rounded-full" style="width: 25%"></div>
                        </div>
                        <span class="ml-2 text-sm text-gray-600">25%</span>
                    </div>
                </div>
                
                <div class="border border-gray-200 rounded-lg p-4">
                    <h4 class="text-sm font-medium text-gray-700 mb-2">Storage</h4>
                    <div class="flex items-center">
                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                            <div class="bg-green-500 h-2.5 rounded-full" style="width: 32%"></div>
                        </div>
                        <span class="ml-2 text-sm text-gray-600">32%</span>
                    </div>
                </div>
                
                <div class="border border-gray-200 rounded-lg p-4">
                    <h4 class="text-sm font-medium text-gray-700 mb-2">Memory Usage</h4>
                    <div class="flex items-center">
                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                            <div class="bg-green-500 h-2.5 rounded-full" style="width: 28%"></div>
                        </div>
                        <span class="ml-2 text-sm text-gray-600">28%</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Chart.js for future implementation -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // We'll add charts in future implementations
        // This is a placeholder for when we have actual data to display
        
        // Example initialization code for future use:
        /*
        const ctx = document.getElementById('statisticsChart');
        if (ctx) {
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                    datasets: [{
                        label: 'Users',
                        data: [0, 0, 0, 0, 0, 0],
                        borderColor: '#2a3f5e',
                        backgroundColor: 'rgba(42, 63, 94, 0.1)'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });
        }
        */
    });
</script>