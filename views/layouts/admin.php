<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - <?= $title ?? 'Dashboard' ?></title>
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
                        admin: {
                            'primary': '#2a3f5e',
                            'secondary': '#476282',
                            'accent': '#f0b429',
                            'light': '#edf2f7',
                            'dark': '#1a2e4c'
                        }
                    }
                }
            }
        }
    </script>
    <style>
        .admin-bg {
            background-color: #f8fafc;
        }
        
        .sidebar-item.active {
            background-color: rgba(240, 180, 41, 0.15);
            color: #f0b429;
            border-left: 3px solid #f0b429;
        }
        
        @media (max-width: 768px) {
            .sidebar-open {
                transform: translateX(0);
            }
        }
    </style>
</head>
<body class="admin-bg min-h-screen flex flex-col">
    <!-- Header -->
    <header class="bg-admin-primary text-white shadow-md sticky top-0 z-40">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between h-16">
                <!-- Left: Logo & Toggle -->
                <div class="flex items-center">
                    <!-- Mobile menu button -->
                    <button id="sidebarToggle" class="md:hidden p-2 rounded-full text-white hover:bg-admin-secondary focus:outline-none transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                    
                    <!-- Logo -->
                    <a href="/admin/dashboard" class="flex items-center ml-2 md:ml-0">
                        <div class="flex items-center">
                            <div class="bg-admin-accent text-admin-primary rounded-md w-8 h-8 flex items-center justify-center mr-2 font-bold">
                                <span>Y</span>
                            </div>
                            <span class="text-xl font-bold">
                                <span class="text-white">Admin</span>
                                <span class="text-admin-accent">Portal</span>
                            </span>
                        </div>
                    </a>
                </div>

                <!-- Center: Title (on larger screens) -->
                <div class="hidden md:block">
                    <h1 class="text-lg font-semibold"><?= $title ?? 'Admin Dashboard' ?></h1>
                </div>
                
                <!-- Right: User dropdown -->
                <div class="flex items-center">
                    <div class="relative">
                        <button type="button" class="flex items-center space-x-3 focus:outline-none" id="user-menu-button">
                            <div class="hidden md:block text-right">
                                <span class="block text-sm"><?= htmlspecialchars(App\core\Application::$app->session->get('user')['name'] ?? 'Admin') ?></span>
                                <span class="block text-xs text-gray-300"><?= htmlspecialchars(App\core\Application::$app->session->get('user')['email'] ?? '') ?></span>
                            </div>
                            <div class="h-9 w-9 rounded-full bg-admin-accent text-admin-primary flex items-center justify-center font-bold text-sm">
                                <?= strtoupper(substr(App\core\Application::$app->session->get('user')['email'] ?? 'A', 0, 1)) ?>
                            </div>
                            <svg class="hidden md:block h-5 w-5 text-gray-300" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                        
                        <!-- User dropdown menu -->
                        <div class="hidden origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 z-50" id="user-dropdown">
                            <div class="px-4 py-2 border-b border-gray-100">
                                <p class="text-xs text-gray-500">Signed in as</p>
                                <p class="text-sm font-medium text-gray-900 truncate"><?= htmlspecialchars(App\core\Application::$app->session->get('user')['email'] ?? 'Admin') ?></p>
                            </div>
                            <a href="/" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <div class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                    </svg>
                                    View Main Site
                                </div>
                            </a>
                            <a href="/admin/logout" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 border-t border-gray-100">
                                <div class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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
    </header>

    <div class="flex flex-1 overflow-hidden">
        <!-- Sidebar - fixed on larger screens, off-canvas on mobile -->
        <aside id="sidebar" class="bg-admin-primary text-white w-64 md:block fixed md:relative h-full z-30 transform -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out overflow-y-auto">
            <div class="p-4">
                <div class="border-b border-admin-secondary pb-4 mb-4 md:hidden">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-semibold text-white">Admin Menu</h2>
                        <button id="closeSidebar" class="text-white hover:text-gray-300 focus:outline-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
                
                <nav>
                    <ul class="space-y-1">
                        <li>
                            <a href="/admin/dashboard" class="sidebar-item flex items-center px-4 py-3 rounded-md hover:bg-admin-secondary transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                </svg>
                                Dashboard
                            </a>
                        </li>
                        <li>
                            <a href="/admin/vendors" class="sidebar-item flex items-center px-4 py-3 rounded-md hover:bg-admin-secondary transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                                Vendors
                            </a>
                        </li>
                        <li>
                            <a href="/admin/statistics" class="sidebar-item flex items-center px-4 py-3 rounded-md hover:bg-admin-secondary transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                                Statistics
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </aside>
        
        <!-- Backdrop for mobile sidebar -->
        <div id="sidebarBackdrop" class="fixed inset-0 bg-black opacity-50 z-20 hidden md:hidden"></div>

        <!-- Main content -->
        <main class="flex-1 overflow-y-auto p-4 md:p-6">
            <?php if (App\core\Application::$app->session->getFlash('success')): ?>
                <div class="mb-4">
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4" role="alert">
                        <p><?= App\core\Application::$app->session->getFlash('success') ?></p>
                    </div>
                </div>
            <?php endif; ?>
            
            <?php if (App\core\Application::$app->session->getFlash('error')): ?>
                <div class="mb-4">
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4" role="alert">
                        <p><?= App\core\Application::$app->session->getFlash('error') ?></p>
                    </div>
                </div>
            <?php endif; ?>
            
            {{content}}
        </main>
    </div>

    <!-- Footer -->
    <footer class="bg-admin-primary text-white text-center py-3 md:py-4">
        <p class="text-sm">© <?= date('Y') ?> YOU/Market Admin. All rights reserved.</p>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // User dropdown toggle
            const userMenuButton = document.getElementById('user-menu-button');
            const userDropdown = document.getElementById('user-dropdown');
            
            if (userMenuButton && userDropdown) {
                userMenuButton.addEventListener('click', function() {
                    userDropdown.classList.toggle('hidden');
                });
                
                // Close dropdown when clicking outside
                document.addEventListener('click', function(event) {
                    if (!userMenuButton.contains(event.target) && !userDropdown.contains(event.target)) {
                        userDropdown.classList.add('hidden');
                    }
                });
            }
            
            // Mobile sidebar toggle
            const sidebar = document.getElementById('sidebar');
            const sidebarToggle = document.getElementById('sidebarToggle');
            const closeSidebar = document.getElementById('closeSidebar');
            const sidebarBackdrop = document.getElementById('sidebarBackdrop');
            
            if (sidebar && sidebarToggle) {
                sidebarToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('-translate-x-full');
                    sidebarBackdrop.classList.toggle('hidden');
                    document.body.classList.toggle('overflow-hidden');
                });
                
                if (closeSidebar) {
                    closeSidebar.addEventListener('click', function() {
                        sidebar.classList.add('-translate-x-full');
                        sidebarBackdrop.classList.add('hidden');
                        document.body.classList.remove('overflow-hidden');
                    });
                }
                
                if (sidebarBackdrop) {
                    sidebarBackdrop.addEventListener('click', function() {
                        sidebar.classList.add('-translate-x-full');
                        sidebarBackdrop.classList.add('hidden');
                        document.body.classList.remove('overflow-hidden');
                    });
                }
            }
            
            // Highlight current page in navigation
            const currentPath = window.location.pathname;
            const sidebarItems = document.querySelectorAll('.sidebar-item');
            
            sidebarItems.forEach(item => {
                const href = item.getAttribute('href');
                if (href === currentPath || (href !== '/admin/dashboard' && currentPath.startsWith(href))) {
                    item.classList.add('active');
                }
            });
        });
    </script>
</body>
</html>