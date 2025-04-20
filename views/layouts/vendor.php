<?php

use App\core\Application;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>YOU/Market Vendor - <?= $title ?? 'Dashboard' ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        accent: {
                            'terracotta': '#c95227',
                            'ochre': '#d59c32',
                            'teal': '#1a7f86',
                            'navy': '#1c3b6a',
                            'ceramicblue': '#3d8ac9',
                        },
                    }
                }
            }
        }
    </script>
    <style>
        .moroccan-bg {
            background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" opacity="0.05" viewBox="0 0 100 100"><path d="M50 0L100 50L50 100L0 50z" fill="%23c95227"/><path d="M0 0L50 0L0 50z" fill="%23d59c32"/><path d="M100 0L100 50L50 0z" fill="%231a7f86"/><path d="M0 100L0 50L50 100z" fill="%233d8ac9"/><path d="M100 100L50 100L100 50z" fill="%231c3b6a"/></svg>');
            background-size: 50px 50px;
        }
        
        .moroccan-border {
            position: relative;
            overflow: hidden;
        }
        
        .moroccan-border::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #c95227, #d59c32, #1a7f86, #3d8ac9, #1c3b6a);
        }
    </style>
</head>
<body class="bg-gray-50 flex flex-col min-h-screen">
    <!-- Mobile Header with Hamburger Menu -->
    <header class="bg-white shadow-md moroccan-border sticky top-0 z-30">
    <div class="container mx-auto px-4">
        <div class="flex items-center justify-between h-16">
            <!-- Left: Logo & Toggle -->
            <div class="flex items-center">
                <!-- Mobile menu button -->
                <button id="sidebarToggle" class="lg:hidden p-2 rounded-full text-gray-600 hover:bg-gray-100 hover:text-accent-teal focus:outline-none transition-colors duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
                
                <!-- Logo -->
                <a href="/" class="flex items-center ml-2 lg:ml-0">
                    <div class="flex items-center">
                        <div class="bg-accent-terracotta text-white rounded-md w-8 h-8 flex items-center justify-center mr-2">
                            <span class="font-bold">Y</span>
                        </div>
                        <span class="text-xl font-bold">
                            <span class="text-accent-terracotta">YOU</span><span class="text-accent-navy">/Market</span>
                        </span>
                    </div>
                </a>
            </div>

            <!-- Center: Page Title (visible on larger screens) -->
            <div class="hidden md:block absolute left-1/2 transform -translate-x-1/2">
                <div class="flex items-center px-3 py-1 rounded-full bg-gray-100 text-gray-700">
                    <span class="text-accent-teal mr-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                    </span>
                    <span class="text-sm font-medium">Vendor Portal</span>
                </div>
            </div>
            
            <!-- Right: Action Buttons -->
            <div class="flex items-center space-x-1 md:space-x-3">
                <!-- Create New Button -->
                <div class="relative group">
                    <button id="createNewBtn" class="p-2 rounded-full text-gray-600 hover:bg-gray-100 hover:text-accent-teal focus:outline-none transition-colors duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                    </button>
                    <!-- Dropdown for Create New -->
                    <div id="createNewDropdown" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2 z-10 text-sm">
                        <a href="/vendor/products/create" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-accent-ochre" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                            New Product
                        </a>
                        <a href="#" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-accent-ochre" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            New Promotion
                        </a>
                    </div>
                </div>
                
                <!-- Notifications -->
                <button id="notificationsBtn" class="p-2 rounded-full text-gray-600 hover:bg-gray-100 hover:text-accent-teal focus:outline-none transition-colors duration-200 relative">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                    <!-- Notification badge - only shows when there are notifications -->
                    <span class="absolute -top-1 -right-1 bg-accent-terracotta text-white text-xs font-bold rounded-full h-4 w-4 flex items-center justify-center">3</span>
                </button>
                
                <!-- Desktop navigation -->
                <nav class="hidden md:flex items-center space-x-1">
                    <a href="/" class="px-2 py-1 rounded-md text-sm font-medium text-gray-600 hover:text-accent-teal hover:bg-gray-100 transition-colors duration-200 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        Store
                    </a>
                    
                    <?php if (in_array(\App\models\Role::BUYER, Application::$app->session->get('user')['roles'] ?? [])): ?>
                    <a href="/switch-to-buyer" class="px-2 py-1 rounded-md text-sm font-medium text-gray-600 hover:text-accent-teal hover:bg-gray-100 transition-colors duration-200 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                        </svg>
                        Switch
                    </a>
                    <?php endif; ?>
                </nav>
                
                <!-- User profile dropdown -->
                <div class="relative group">
                    <button id="userProfileBtn" class="flex items-center p-1 rounded-full hover:bg-gray-100 transition-colors duration-200 focus:outline-none">
                        <div class="w-8 h-8 rounded-full bg-accent-teal text-white flex items-center justify-center text-sm font-medium">
                            <?= strtoupper(substr(Application::$app->session->get('user')['email'] ?? 'U', 0, 1)) ?>
                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 ml-1 hidden md:block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    
                    <!-- Profile Dropdown Menu -->
                    <div id="userProfileDropdown" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2 z-10">
                        <div class="px-4 py-2 border-b border-gray-100 text-sm">
                            <p class="text-gray-500">Signed in as</p>
                            <p class="font-medium text-gray-900 truncate"><?= htmlspecialchars(Application::$app->session->get('user')['email'] ?? '') ?></p>
                        </div>
                        <a href="/vendor/settings" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <div class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                Settings
                            </div>
                        </a>
                        <a href="/logout" class="block px-4 py-2 text-sm text-red-600 hover:bg-gray-100 border-t border-gray-100 mt-1">
                            <div class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                                Logout
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Notifications Panel (Hidden by default) -->
    <div id="notificationsPanel" class="hidden absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-lg py-2 z-10 mr-4">
        <div class="px-4 py-2 border-b border-gray-100 flex justify-between items-center">
            <h3 class="text-sm font-medium text-gray-900">Notifications</h3>
            <button class="text-xs text-accent-teal hover:text-accent-navy">Mark all as read</button>
        </div>
        <div class="max-h-96 overflow-y-auto">
            <a href="#" class="block px-4 py-3 hover:bg-gray-50 border-b border-gray-100">
                <div class="flex">
                    <div class="flex-shrink-0 mr-3">
                        <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                        </div>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-900">New order received!</p>
                        <p class="text-xs text-gray-500">Someone purchased "Handcrafted Moroccan Plate"</p>
                        <p class="text-xs text-gray-400 mt-1">10 minutes ago</p>
                    </div>
                </div>
            </a>
            <a href="#" class="block px-4 py-3 hover:bg-gray-50 border-b border-gray-100">
                <div class="flex">
                    <div class="flex-shrink-0 mr-3">
                        <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-900">Payment completed</p>
                        <p class="text-xs text-gray-500">You received a payment of $79.99</p>
                        <p class="text-xs text-gray-400 mt-1">1 hour ago</p>
                    </div>
                </div>
            </a>
            <a href="#" class="block px-4 py-3 hover:bg-gray-50">
                <div class="flex">
                    <div class="flex-shrink-0 mr-3">
                        <div class="w-10 h-10 rounded-full bg-yellow-100 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-900">Low stock alert</p>
                        <p class="text-xs text-gray-500">Blue Moroccan Ceramic Tagine (2 left)</p>
                        <p class="text-xs text-gray-400 mt-1">Yesterday</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="px-4 py-2 border-t border-gray-100 text-center">
            <a href="#" class="text-sm text-accent-teal hover:text-accent-navy">View all notifications</a>
        </div>
    </div>
</header>

    <div class="flex flex-grow">
        <!-- Mobile Sidebar (off-canvas) -->
        <div id="mobileSidebar" class="fixed inset-0 z-40 md:hidden transform -translate-x-full transition-transform duration-300 ease-in-out">
            <!-- Backdrop -->
            <div id="sidebarBackdrop" class="absolute inset-0 bg-black opacity-50"></div>
            
            <!-- Sidebar content -->
            <div class="relative w-64 max-w-xs h-full bg-white shadow-xl flex flex-col">
                <div class="p-4 border-b">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-semibold text-accent-navy">Vendor Dashboard</h2>
                        <button id="closeSidebar" class="p-2 rounded-md text-gray-500 hover:text-accent-navy hover:bg-gray-100 focus:outline-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <p class="text-sm text-gray-600 mt-1">
                        <?= htmlspecialchars(Application::$app->session->get('user')['name'] ?? 'Vendor') ?>
                    </p>
                </div>
                
                <nav class="flex-1 px-2 py-4 space-y-1 overflow-y-auto">
                    <a href="/vendor/dashboard" class="flex items-center px-3 py-2 rounded-md hover:bg-gray-100">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        Dashboard
                    </a>
                    <a href="/vendor/products" class="flex items-center px-3 py-2 rounded-md hover:bg-gray-100">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                        Products
                    </a>
                    <a href="/vendor/orders" class="flex items-center px-3 py-2 rounded-md hover:bg-gray-100">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        Orders
                    </a>
                    <a href="/vendor/analytics" class="flex items-center px-3 py-2 rounded-md hover:bg-gray-100">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        Analytics
                    </a>
                    <a href="/vendor/settings" class="flex items-center px-3 py-2 rounded-md hover:bg-gray-100">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        Settings
                    </a>
                    
                    <div class="border-t border-gray-200 pt-4 mt-4">
                        <a href="/" class="flex items-center px-3 py-2 rounded-md hover:bg-gray-100">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                            Go to Marketplace
                        </a>
                        <a href="/logout" class="flex items-center px-3 py-2 rounded-md hover:bg-gray-100">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            Logout
                        </a>
                    </div>
                </nav>
            </div>
        </div>

        <!-- Desktop Sidebar -->
        <div class="hidden md:block w-64 bg-white shadow-md p-6 flex-shrink-0">
            <div class="mb-8">
                <h2 class="text-lg font-semibold text-accent-navy mb-2">Vendor Dashboard</h2>
                <p class="text-sm text-gray-600">
                    <?= htmlspecialchars(Application::$app->session->get('user')['name'] ?? 'Vendor') ?>
                </p>
            </div>
            
            <nav>
                <ul class="space-y-2">
                    <li>
                        <a href="/vendor/dashboard" class="flex items-center px-3 py-2 rounded-md hover:bg-gray-100">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                            Dashboard
                        </a>
                    </li>
                    <li>
                        <a href="/vendor/products" class="flex items-center px-3 py-2 rounded-md hover:bg-gray-100">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                            Products
                        </a>
                    </li>
                    <li>
                        <a href="/vendor/orders" class="flex items-center px-3 py-2 rounded-md hover:bg-gray-100">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            Orders
                        </a>
                    </li>
                    <li>
                        <a href="/vendor/analytics" class="flex items-center px-3 py-2 rounded-md hover:bg-gray-100">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                            Analytics
                        </a>
                    </li>
                    <li>
                        <a href="/vendor/settings" class="flex items-center px-3 py-2 rounded-md hover:bg-gray-100">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Settings
                        </a>
                    </li>
                </ul>
            </nav>
        </div>

        <!-- Main Content -->
        <main class="flex-grow p-4 md:p-6 overflow-y-auto">
            <?php if (App\core\Application::$app->session->getFlash('success')): ?>
                <div class="mb-4 md:mb-6">
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4" role="alert">
                        <p><?= App\core\Application::$app->session->getFlash('success') ?></p>
                    </div>
                </div>
            <?php endif; ?>
            
            <?php if (App\core\Application::$app->session->getFlash('error')): ?>
                <div class="mb-4 md:mb-6">
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4" role="alert">
                        <p><?= App\core\Application::$app->session->getFlash('error') ?></p>
                    </div>
                </div>
            <?php endif; ?>
            
            <div class="bg-white rounded-lg shadow-sm p-4 md:p-6">
                {{content}}
            </div>
        </main>
    </div>

    <footer class="bg-white shadow-inner py-4 md:py-6 moroccan-border mt-auto">
        <div class="container mx-auto px-4">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="mb-4 md:mb-0">
                    <p class="text-gray-600 text-sm">&copy; <?= date('Y') ?> YOU/Market. All rights reserved.</p>
                </div>
                <div class="flex space-x-4">
                    <a href="#" class="text-gray-500 hover:text-accent-ceramicblue transition">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd"></path>
                        </svg>
                    </a>
                    <a href="#" class="text-gray-500 hover:text-accent-ceramicblue transition">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path fill-rule="evenodd" d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z" clip-rule="evenodd"></path>
                        </svg>
                    </a>
                    <a href="#" class="text-gray-500 hover:text-accent-ceramicblue transition">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84"></path>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </footer>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Mobile sidebar toggle
            const sidebarToggle = document.getElementById('sidebarToggle');
            const mobileSidebar = document.getElementById('mobileSidebar');
            const sidebarBackdrop = document.getElementById('sidebarBackdrop');
            const closeSidebar = document.getElementById('closeSidebar');
            
            if (sidebarToggle && mobileSidebar) {
                sidebarToggle.addEventListener('click', function() {
                    mobileSidebar.classList.remove('-translate-x-full');
                });
                
                if (closeSidebar) {
                    closeSidebar.addEventListener('click', function() {
                        mobileSidebar.classList.add('-translate-x-full');
                    });
                }
                
                if (sidebarBackdrop) {
                    sidebarBackdrop.addEventListener('click', function() {
                        mobileSidebar.classList.add('-translate-x-full');
                    });
                }
            }
            
            // Highlight current page in navigation
            const currentPath = window.location.pathname;
            const navLinks = document.querySelectorAll('nav a');
            
            navLinks.forEach(link => {
                if (currentPath === link.getAttribute('href') || 
                    (currentPath.startsWith(link.getAttribute('href')) && link.getAttribute('href') !== '/')) {
                    link.classList.add('bg-gray-100', 'text-accent-navy');
                    link.classList.remove('hover:bg-gray-100');
                }
            });
        });
    </script>
    
    <script src="/assets/js/vendor.js"></script>
</body>
</html>