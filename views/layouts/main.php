<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>YOU/Market - <?= $title ?? 'Online Marketplace' ?></title>
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
    <header class="relative shadow-md moroccan-border">
        <div class="absolute inset-0 z-0" style="background-image: url('../../assets/images/wmremove-transformed(1).jpeg'); background-size: cover; background-position: center; opacity: 0.15;"></div>
        <div class="container mx-auto px-4 py-3 flex justify-between items-center relative z-10">
            <a href="/" class="text-2xl font-bold text-accent-navy">
                <span class="text-accent-terracotta">YOU</span>/Market
            </a>
            <nav>
                <ul class="flex space-x-6">
                    <li><a href="/" class="text-gray-600 hover:text-accent-teal transition">Home</a></li>
                    <li><a href="/products" class="text-gray-600 hover:text-accent-teal transition">Shop</a></li>
                    <?php if (isset($_SESSION['user'])): ?>
                        <li><a href="/profile" class="text-gray-600 hover:text-accent-teal transition">My Account</a></li>
                        <li><a href="/logout" class="text-gray-600 hover:text-accent-teal transition">Logout</a></li>
                    <?php else: ?>
                        <li><a href="/login" class="text-gray-600 hover:text-accent-teal transition">Login</a></li>
                        <li><a href="/register" class="text-gray-600 hover:text-accent-teal transition">Register</a></li>
                    <?php endif; ?>
                    <li>
                        <a href="/cart" class="relative text-gray-600 hover:text-accent-teal transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            <?php
                            if (class_exists('\App\services\CartService')):
                                $cartService = new \App\services\CartService();
                                $itemCount = $cartService->getItemCount();
                                if ($itemCount > 0): 
                            ?>
                            <span class="absolute -top-2 -right-2 bg-accent-terracotta text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                                <?= $itemCount ?>
                            </span>
                            <?php 
                                endif;
                            endif;
                            ?>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
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

    <footer class="bg-white shadow-inner py-6 moroccan-border mt-auto">
        <div class="container mx-auto px-4">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="mb-4 md:mb-0">
                    <p class="text-gray-600">&copy; <?= date('Y') ?> YOU/Market. All rights reserved.</p>
                </div>
                <div class="flex space-x-4">
                    <a href="#" class="text-gray-500 hover:text-accent-ceramicblue transition">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd"></path>
                        </svg>
                    </a>
                    <a href="#" class="text-gray-500 hover:text-accent-ceramicblue transition">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path fill-rule="evenodd" d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z" clip-rule="evenodd"></path>
                        </svg>
                    </a>
                    <a href="#" class="text-gray-500 hover:text-accent-ceramicblue transition">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84"></path>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </footer>
    <script src="assets/js/app.js"></script>
    <script src="assets/js/cart.js"></script>
    <script src="assets/js/products.js"></script>
    <!-- <script > console.log('test')</script> -->

</body>
</html>