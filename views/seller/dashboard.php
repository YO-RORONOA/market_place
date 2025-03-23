<!-- Mobile-specific header with hamburger menu -->
<div class="md:hidden mb-6">
    <div class="flex justify-between items-center">
        <h1 class="text-xl font-bold text-accent-navy">Vendor Dashboard</h1>
        <!-- <button id="mobileMenuToggle" class="p-2 rounded-md text-gray-500 hover:text-accent-navy hover:bg-gray-100 focus:outline-none">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button> -->
    </div>
    
    <!-- Mobile Navigation Menu (hidden by default) -->
    <div id="mobileMenu" class="hidden mt-4 py-3 px-2 bg-white rounded-lg shadow">
        <nav>
            <ul class="space-y-2">
                <li>
                    <a href="/vendor/dashboard" class="flex items-center p-2 rounded-md hover:bg-gray-100">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        Dashboard
                    </a>
                </li>
                <li>
                    <a href="/vendor/products" class="flex items-center p-2 rounded-md hover:bg-gray-100">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                        Products
                    </a>
                </li>
                <li>
                    <a href="/vendor/orders" class="flex items-center p-2 rounded-md hover:bg-gray-100">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        Orders
                    </a>
                </li>
                <li>
                    <a href="/vendor/analytics" class="flex items-center p-2 rounded-md hover:bg-gray-100">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        Analytics
                    </a>
                </li>
                <li>
                    <a href="/vendor/settings" class="flex items-center p-2 rounded-md hover:bg-gray-100">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        Settings
                    </a>
                </li>
                <li>
                    <a href="/marketplace" class="flex items-center p-2 rounded-md hover:bg-gray-100">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        Marketplace
                    </a>
                </li>
                <li>
                    <a href="/logout" class="flex items-center p-2 rounded-md hover:bg-gray-100">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        Logout
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</div>

<!-- Desktop heading - hidden on mobile -->
<h1 class="text-2xl font-bold text-accent-navy mb-6 hidden md:block">Vendor Dashboard</h1>

<!-- Stats Cards -->
<div class="grid grid-cols-1 gap-4 mb-6 md:grid-cols-3 md:gap-6 md:mb-8">
    <!-- Products Card -->
    <div class="bg-gradient-to-br from-accent-ochre to-accent-terracotta rounded-lg p-4 text-white">
        <div class="flex justify-between items-start">
            <div>
                <h3 class="text-base font-semibold opacity-90 mb-1">Products</h3>
                <p class="text-2xl font-bold">10</p>
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
                <p class="text-2xl font-bold">5</p>
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
                <p class="text-2xl font-bold">2,500 MAD</p>
            </div>
            <div class="p-1 bg-white bg-opacity-20 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>
        <div class="mt-3">
            <a href="/vendor/analytics" class="text-xs text-white hover:text-white hover:underline font-medium flex items-center">
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
            <h3 class="font-medium text-accent-navy text-sm">Orders</h3>
        </a>
        
        <a href="/vendor/settings" class="bg-white border border-gray-200 rounded-lg p-3 flex flex-col items-center hover:bg-gray-50 transition text-center">
            <div class="p-2 bg-accent-navy bg-opacity-10 rounded-full mb-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-accent-navy" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </div>
            <h3 class="font-medium text-accent-navy text-sm">Settings</h3>
        </a>
    </div>
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
            <div class="p-4">
                <div class="flex justify-between">
                    <span class="text-sm font-medium text-accent-navy">#ORD-001</span>
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                        Completed
                    </span>
                </div>
                <div class="mt-2 flex justify-between text-sm text-gray-500">
                    <span>2023-05-15</span>
                    <span class="font-medium">500 MAD</span>
                </div>
            </div>
            
            <div class="p-4">
                <div class="flex justify-between">
                    <span class="text-sm font-medium text-accent-navy">#ORD-002</span>
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                        Processing
                    </span>
                </div>
                <div class="mt-2 flex justify-between text-sm text-gray-500">
                    <span>2023-05-18</span>
                    <span class="font-medium">750 MAD</span>
                </div>
            </div>
            
            <div class="p-4">
                <div class="flex justify-between">
                    <span class="text-sm font-medium text-accent-navy">#ORD-003</span>
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                        Shipped
                    </span>
                </div>
                <div class="mt-2 flex justify-between text-sm text-gray-500">
                    <span>2023-05-20</span>
                    <span class="font-medium">1,250 MAD</span>
                </div>
            </div>
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
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-accent-navy">#ORD-001</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">2023-05-15</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                            Completed
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">500 MAD</td>
                </tr>
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-accent-navy">#ORD-002</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">2023-05-18</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                            Processing
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">750 MAD</td>
                </tr>
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-accent-navy">#ORD-003</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">2023-05-20</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                            Shipped
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">1,250 MAD</td>
                </tr>
            </tbody>
        </table>
    </div>
    