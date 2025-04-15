<?php
// Calculate cart count at the top of the layout file
$cartCount = 0;
$cartService = new \App\services\CartService();
if (isset(App\core\Application::$app) && App\core\Application::$app->session->get('user')) {
    $cartCount = $cartService->getItemCount();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>YOU/Market - Modern E-commerce</title>
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
       header {
    overflow: visible !important;
}

/* Ensure dropdown has correct positioning and visibility */
.dropdown {
    position: relative;
}

.dropdown-content {
    opacity: 0;
    transform: translateY(-10px);
    visibility: hidden;
    transition: all 0.3s ease;
    position: absolute;
    top: 100%; /* Position right below the trigger element */
    right: 0;
    min-width: 12rem; /* Ensure enough width */
    z-index: 50;
}

.dropdown:hover .dropdown-content {
    opacity: 1;
    transform: translateY(0);
    visibility: visible;
}
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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

        /* Dropdown animation */
        .dropdown-content {
            opacity: 0;
            transform: translateY(-10px);
            visibility: hidden;
            transition: all 0.3s ease;
        }
        
        .dropdown:hover .dropdown-content {
            opacity: 1;
            transform: translateY(0);
            visibility: visible;
        }

        /* Mobile menu animation */
        .mobile-menu {
            transition: transform 0.3s ease-in-out;
        }
        
        .search-input:focus {
            box-shadow: 0 0 0 2px rgba(26, 127, 134, 0.3);
        }

        /* Custom scrollbar for dropdowns */
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }
        
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #d59c32;
            border-radius: 10px;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Header - sticky and responsive -->
    <header class="sticky top-0 z-50 bg-white shadow-md moroccan-border w-full">
        <div class="relative">
            <div class="absolute inset-0 z-0" style="background-image: url('../../assets/images/wmremove-transformed(1).jpeg'); background-size: cover; background-position: center; opacity: 0.15;"></div>
            
            <!-- Top header with logo, search, and right menu -->
            <div class="container mx-auto px-4 py-3 relative z-10">
                <div class="flex items-center justify-between">
                    <!-- Left section with logo and hamburger -->
                    <div class="flex items-center">
                        <!-- Mobile menu button -->
                        <button id="mobile-menu-button" class="mr-2 p-2 rounded-md lg:hidden text-gray-600 hover:text-accent-teal focus:outline-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                        
                        <!-- Logo -->
                        <a href="/" class="flex items-center text-2xl font-bold text-accent-navy">
                            <span class="text-accent-terracotta">YOU</span>/Market
                        </a>
                    </div>
                    
                    <!-- Search bar - Hidden on mobile, visible on larger screens -->
                    <div class="hidden md:block flex-grow max-w-xl mx-4">
                        <div class="relative">
                            <input type="text" placeholder="Search for artisan products..." class="search-input w-full py-2 pl-10 pr-4 rounded-full border border-gray-300 focus:outline-none focus:border-accent-teal text-sm">
                            <div class="absolute left-3 top-2.5 text-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Right section with nav icons -->
                    <div class="flex items-center space-x-4">
                        <!-- Search icon on mobile -->
                        <button class="md:hidden p-2 rounded-md text-gray-600 hover:text-accent-teal focus:outline-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </button>
                        
                        <!-- Account dropdown -->
                        <div class="relative dropdown">
                            <button class="p-2 rounded-md text-gray-600 hover:text-accent-teal focus:outline-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </button>
                            <div class="dropdown-content absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-10">
                                <!-- If the user is logged in -->
                                <?php if (App\core\Application::$app->session->get('user')): ?>
                                <div class="px-4 py-2 text-sm text-gray-700 border-b">
                                    <div class="font-medium">Logged In As</div>
                                    <div class="truncate"><?= htmlspecialchars(App\core\Application::$app->session->get('user')['email'] ?? 'User') ?></div>
                                </div>
                                <a href="/profile" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">My Profile</a>
                                <a href="/orders" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">My Orders</a>
                                <a href="/wishlist" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Wishlist</a>
                                <?php if (in_array(\App\models\Role::VENDOR, App\core\Application::$app->session->get('user')['roles'] ?? [])): ?>
                                <a href="/vendor/dashboard" class="block px-4 py-2 text-sm text-accent-teal hover:bg-gray-100">Vendor Dashboard</a>
                                <?php endif; ?>
                                <a href="/logout" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Logout</a>
                                
                                <!-- If the user is not logged in -->
                                <?php else: ?>
                                <a href="/login" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Login</a>
                                <a href="/register" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Register</a>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <!-- Wishlist icon -->
                        <a href="/wishlist" class="p-2 rounded-md text-gray-600 hover:text-accent-teal hidden sm:block">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                        </a>
                        
                        <!-- Cart -->
                        <a href="/cart" class="relative p-2 rounded-md text-gray-600 hover:text-accent-teal">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            <?php if ($cartCount > 0): ?>
                            <span class="absolute -top-1 -right-1 bg-accent-terracotta text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                                <?= $cartCount ?>
                            </span>
                            <?php endif; ?>
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Desktop Navigation - Hidden on mobile -->
            <nav class="hidden lg:block bg-white border-t border-gray-200 relative z-10">
                <div class="container mx-auto px-4">
                    <ul class="flex">
                        <!-- Home -->
                        <li class="relative group">
                            <a href="/" class="block px-4 py-3 font-medium text-gray-700 hover:text-accent-teal">
                                Home
                            </a>
                        </li>
                        
                        <!-- Shop - with dropdown -->
                        <li class="relative dropdown">
                            <a href="/products" class="flex items-center px-4 py-3 font-medium text-gray-700 hover:text-accent-teal">
                                Shop
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </a>
                            <div class="dropdown-content absolute left-0 mt-0 w-72 bg-white rounded-b-md shadow-lg py-2 z-10 custom-scrollbar max-h-96 overflow-y-auto">
                                <div class="grid grid-cols-1">
                                    <a href="/products" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 font-medium">All Products</a>
                                    <a href="/products?category=1" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Electronics</a>
                                    <a href="/products?category=2" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Clothing</a>
                                    <a href="/products?category=3" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Home & Garden</a>
                                    <a href="/products?category=4" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Books</a>
                                    <a href="/products?category=5" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Beauty & Health</a>
                                    <!-- Add more categories as needed -->
                                </div>
                            </div>
                        </li>
                        
                        <!-- New Arrivals -->
                        <li class="relative group">
                            <a href="/products?sort=new" class="block px-4 py-3 font-medium text-gray-700 hover:text-accent-teal">
                                New Arrivals
                            </a>
                        </li>
                        
                        <!-- Featured -->
                        <li class="relative group">
                            <a href="/products?featured=1" class="block px-4 py-3 font-medium text-gray-700 hover:text-accent-teal">
                                Featured
                            </a>
                        </li>
                        
                        <!-- Artisans - with dropdown -->
                        <li class="relative dropdown">
                            <a href="/vendors" class="flex items-center px-4 py-3 font-medium text-gray-700 hover:text-accent-teal">
                                Artisans
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </a>
                            <div class="dropdown-content absolute left-0 mt-0 w-72 bg-white rounded-b-md shadow-lg py-2 z-10">
                                <a href="/vendors" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 font-medium">All Artisans</a>
                                <a href="/vendors?featured=1" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Featured Artisans</a>
                                <a href="/vendor/register" class="block px-4 py-2 text-sm text-accent-teal hover:bg-gray-100">Become an Artisan</a>
                            </div>
                        </li>
                        
                        <!-- About -->
                        <li class="relative group">
                            <a href="/about" class="block px-4 py-3 font-medium text-gray-700 hover:text-accent-teal">
                                About Us
                            </a>
                        </li>
                        
                        <!-- Contact -->
                        <li class="relative group">
                            <a href="/contact" class="block px-4 py-3 font-medium text-gray-700 hover:text-accent-teal">
                                Contact
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
        
        <!-- Mobile search bar - Hidden by default, toggle with search button -->
        <div id="mobile-search" class="hidden p-4 border-t border-gray-200 bg-white">
            <div class="relative">
                <input type="text" placeholder="Search for artisan products..." class="search-input w-full py-2 pl-10 pr-4 rounded-full border border-gray-300 focus:outline-none focus:border-accent-teal text-sm">
                <div class="absolute left-3 top-2.5 text-gray-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
            </div>
        </div>
        
        <!-- Mobile menu - Hidden by default -->
        <div id="mobile-menu" class="mobile-menu transform -translate-x-full fixed top-0 left-0 w-4/5 max-w-xs h-screen bg-white shadow-lg z-50 overflow-y-auto">
            <div class="p-4 border-b border-gray-200 flex justify-between items-center">
                <a href="/" class="text-xl font-bold text-accent-navy">
                    <span class="text-accent-terracotta">YOU</span>/Market
                </a>
                <button id="close-mobile-menu" class="p-2 rounded-md text-gray-600 hover:text-accent-teal focus:outline-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <!-- User actions -->
            <div class="p-4 border-b border-gray-200">
                <!-- If not logged in -->
                <?php if (!App\core\Application::$app->session->get('user')): ?>
                <div class="flex space-x-2">
                    <a href="/login" class="flex-1 py-2 px-3 bg-accent-navy text-white rounded-md text-center text-sm font-medium hover:bg-accent-ceramicblue transition-colors">
                        Login
                    </a>
                    <a href="/register" class="flex-1 py-2 px-3 border border-accent-navy text-accent-navy rounded-md text-center text-sm font-medium hover:bg-gray-50 transition-colors">
                        Register
                    </a>
                </div>
                
                <!-- If logged in -->
                <?php else: ?>
                <div class="mb-3 text-sm">
                    <p>Logged in as:</p>
                    <p class="font-medium"><?= htmlspecialchars(App\core\Application::$app->session->get('user')['email'] ?? 'User') ?></p>
                </div>
                <div class="flex space-x-2">
                    <a href="/profile" class="flex-1 py-2 px-3 bg-accent-navy text-white rounded-md text-center text-sm font-medium hover:bg-accent-ceramicblue transition-colors">
                        My Profile
                    </a>
                    <a href="/logout" class="flex-1 py-2 px-3 border border-accent-navy text-accent-navy rounded-md text-center text-sm font-medium hover:bg-gray-50 transition-colors">
                        Logout
                    </a>
                </div>
                <?php endif; ?>
            </div>
            
            <!-- Navigation links -->
            <nav class="py-2">
                <ul>
                    <li>
                        <a href="/" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                            Home
                        </a>
                    </li>
                    <li class="relative">
                        <div class="flex items-center justify-between px-4 py-2 text-gray-700 hover:bg-gray-100 cursor-pointer mobile-submenu-toggle">
                            <span>Shop</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transform transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                        <ul class="bg-gray-50 hidden">
                            <li>
                                <a href="/products" class="block px-6 py-2 text-gray-700 hover:bg-gray-100">
                                    All Products
                                </a>
                            </li>
                            <li>
                                <a href="/products?category=1" class="block px-6 py-2 text-gray-700 hover:bg-gray-100">
                                    Electronics
                                </a>
                            </li>
                            <li>
                                <a href="/products?category=2" class="block px-6 py-2 text-gray-700 hover:bg-gray-100">
                                    Clothing
                                </a>
                            </li>
                            <li>
                                <a href="/products?category=3" class="block px-6 py-2 text-gray-700 hover:bg-gray-100">
                                    Home & Garden
                                </a>
                            </li>
                            <li>
                                <a href="/products?category=4" class="block px-6 py-2 text-gray-700 hover:bg-gray-100">
                                    Books
                                </a>
                            </li>
                            <li>
                                <a href="/products?category=5" class="block px-6 py-2 text-gray-700 hover:bg-gray-100">
                                    Beauty & Health
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="/products?sort=new" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                            New Arrivals
                        </a>
                    </li>
                    <li>
                        <a href="/products?featured=1" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                            Featured
                        </a>
                    </li>
                    <li class="relative">
                        <div class="flex items-center justify-between px-4 py-2 text-gray-700 hover:bg-gray-100 cursor-pointer mobile-submenu-toggle">
                            <span>Artisans</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transform transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                        <ul class="bg-gray-50 hidden">
                            <li>
                                <a href="/vendors" class="block px-6 py-2 text-gray-700 hover:bg-gray-100">
                                    All Artisans
                                </a>
                            </li>
                            <li>
                                <a href="/vendors?featured=1" class="block px-6 py-2 text-gray-700 hover:bg-gray-100">
                                    Featured Artisans
                                </a>
                            </li>
                            <li>
                                <a href="/vendor/register" class="block px-6 py-2 text-accent-teal hover:bg-gray-100">
                                    Become an Artisan
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="/about" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                            About Us
                        </a>
                    </li>
                    <li>
                        <a href="/contact" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                            Contact
                        </a>
                    </li>
                    <li>
                        <a href="/wishlist" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                            Wishlist
                        </a>
                    </li>
                    <li>
                        <a href="/cart" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                            Cart
                            <?php if ($cartCount > 0): ?>
                            <span class="ml-2 px-2 py-0.5 bg-accent-terracotta text-white text-xs rounded-full">
                                <?= $cartCount ?>
                            </span>
                            <?php endif; ?>
                        </a>
                    </li>
                </ul>
            </nav>
            
            <!-- Vendor switch (if the user has a vendor account) -->
            <?php if (isset(App\core\Application::$app) && 
                      App\core\Application::$app->session->get('user') &&
                      in_array(\App\models\Role::VENDOR, App\core\Application::$app->session->get('user')['roles'] ?? [])): ?>
            <div class="p-4 border-t border-gray-200">
                <a href="/vendor/dashboard" class="block py-2 px-3 bg-gradient-to-r from-accent-terracotta to-accent-ochre text-white rounded-md text-center text-sm font-medium">
                    Vendor Dashboard
                </a>
            </div>
            <?php endif; ?>
        </div>
        
        <!-- Mobile menu backdrop -->
        <div id="mobile-menu-backdrop" class="fixed inset-0 bg-black opacity-50 z-40 hidden"></div>
    </header>

    <main class="flex-grow py-8">
        <?php if (App\core\Application::$app->session->getFlash('success')): ?>
            <div class="container mx-auto px-4 mb-6">
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4" role="alert">
                    <p><?= App\core\Application::$app->session->getFlash('success') ?></p>
                </div>
            </div>
        <?php endif; ?>
        
        <?php if (App\core\Application::$app->session->getFlash('error')): ?>
            <div class="container mx-auto px-4 mb-6">
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4" role="alert">
                    <p><?= App\core\Application::$app->session->getFlash('error') ?></p>
                </div>
            </div>
        <?php endif; ?>
        
        <div class="container mx-auto px-4">
            {{content}}
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Mobile menu toggle
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            const closeMobileMenu = document.getElementById('close-mobile-menu');
            const mobileMenu = document.getElementById('mobile-menu');
            const mobileMenuBackdrop = document.getElementById('mobile-menu-backdrop');
            const searchButton = document.querySelector('button.md\\:hidden');
            const mobileSearch = document.getElementById('mobile-search');
            
            // Mobile menu open/close
            if (mobileMenuButton && mobileMenu && mobileMenuBackdrop) {
                mobileMenuButton.addEventListener('click', function() {
                    mobileMenu.classList.remove('transform', '-translate-x-full');
                    mobileMenuBackdrop.classList.remove('hidden');
                    document.body.classList.add('overflow-hidden');
                });
                
                function closeMobileMenuFn() {
                    mobileMenu.classList.add('transform', '-translate-x-full');
                    mobileMenuBackdrop.classList.add('hidden');
                    document.body.classList.remove('overflow-hidden');
                }
                
                closeMobileMenu.addEventListener('click', closeMobileMenuFn);
                mobileMenuBackdrop.addEventListener('click', closeMobileMenuFn);
            }
            
            // Mobile search toggle
            if (searchButton && mobileSearch) {
                searchButton.addEventListener('click', function() {
                    mobileSearch.classList.toggle('hidden');
                });
            }
            
            // Mobile submenu toggles
            const mobileSubmenuToggles = document.querySelectorAll('.mobile-submenu-toggle');
            
            mobileSubmenuToggles.forEach(toggle => {
                toggle.addEventListener('click', function() {
                    const submenu = this.nextElementSibling;
                    const icon = this.querySelector('svg');
                    
                    if (submenu.classList.contains('hidden')) {
                        submenu.classList.remove('hidden');
                        icon.classList.add('rotate-180');
                    } else {
                        submenu.classList.add('hidden');
                        icon.classList.remove('rotate-180');
                    }
                });
            });
        });
    </script>
    <script src="/assets/js/app.js"></script>
    <script src="/assets/js/cart.js"></script>
    <script src="/assets/js/products.js"></script>
</body>
</html>