<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>YOU/Market - <?= $title ?? 'Authentication' ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            200: '#bae6fd',
                            300: '#7dd3fc',
                            400: '#38bdf8',
                            500: '#0ea5e9',
                            600: '#0284c7',
                            700: '#0369a1',
                            800: '#075985',
                            900: '#0c4a6e',
                        },
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
<header class="relative shadow-md moroccan-border overflow-hidden" style="height: 64px;">
    <div class="absolute inset-0 z-0" style="background-image: url('../../assets/images/wmremove-transformed(1).jpeg'); background-size: cover; background-position: center; opacity: 0.15;"></div>
    <div class="container mx-auto px-4 py-3 flex justify-between items-center relative z-10">
        <a href="/" class="text-2xl font-bold text-accent-navy">
            <span class="text-accent-terracotta">YOU</span>/Market
        </a>
            <ul class="flex space-x-6">
                <li><a href="/" class="text-gray-600 hover:text-accent-teal transition">Home</a></li>
                <li><a href="/login" class="text-gray-600 hover:text-accent-teal transition">Login</a></li>
                <li><a href="/register" class="text-gray-600 hover:text-accent-teal transition">Register</a></li>
            </ul>
        </nav>
    </div>
</header>

    <main class="flex-grow container mx-auto px-4 py-8">
        <?php if (App\core\Application::$app->session->getFlash('success')): ?>
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                <p><?= App\core\Application::$app->session->getFlash('success') ?></p>
            </div>
        <?php endif; ?>
        
        <?php if (App\core\Application::$app->session->getFlash('error')): ?>
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                <p><?= App\core\Application::$app->session->getFlash('error') ?></p>
            </div>
        <?php endif; ?>
        
        
        {{content}}
    </main>

    <footer class="bg-white shadow-inner py-6 moroccan-border">
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
</body>
</html>