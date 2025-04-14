<!-- File: views/_error.php -->
<div class="flex justify-center items-center min-h-[70vh]">
    <div class="w-full max-w-lg bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="bg-gradient-to-r from-accent-terracotta via-accent-ochre to-accent-teal p-6">
            <h2 class="text-2xl font-bold text-white text-center">
                <?= htmlspecialchars($title) ?> (<?= $code ?>)
            </h2>
            <div class="flex justify-center mt-4">
                <div class="w-16 h-1 bg-white rounded-full"></div>
            </div>
        </div>
        
        <div class="p-6 text-center">
            <div class="flex justify-center mb-6">
                <?php if ($code === 404): ?>
                    <!-- 404 Icon -->
                    <div class="rounded-full bg-blue-100 p-3">
                        <svg class="h-16 w-16 text-accent-ceramicblue" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                <?php elseif ($code === 403): ?>
                    <!-- 403 Icon -->
                    <div class="rounded-full bg-yellow-100 p-3">
                        <svg class="h-16 w-16 text-yellow-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                <?php else: ?>
                    <!-- Generic Error Icon -->
                    <div class="rounded-full bg-red-100 p-3">
                        <svg class="h-16 w-16 text-red-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                <?php endif; ?>
            </div>
            
            <h3 class="text-lg font-medium text-gray-900 mb-2">
                <?php if ($code === 404): ?>
                    Page Not Found
                <?php elseif ($code === 403): ?>
                    Access Denied
                <?php elseif ($code === 400): ?>
                    Bad Request
                <?php elseif ($code === 500): ?>
                    Server Error
                <?php elseif ($code === 422): ?>
                    Validation Error
                <?php else: ?>
                    An Error Occurred
                <?php endif; ?>
            </h3>
            
            <p class="text-sm text-gray-600 mb-6">
                <?php if ($isDebug): ?>
                    <?= htmlspecialchars($message) ?>
                <?php else: ?>
                    <?php if ($code === 404): ?>
                        The page you're looking for doesn't exist or has been moved.
                    <?php elseif ($code === 403): ?>
                        You don't have permission to access this resource.
                    <?php elseif ($code === 400): ?>
                        The request could not be understood by the server.
                    <?php elseif ($code === 500): ?>
                        Something went wrong on our end. Our team has been notified.
                    <?php elseif ($code === 422): ?>
                        The submitted data was not valid.
                    <?php else: ?>
                        Something went wrong. Please try again later.
                    <?php endif; ?>
                <?php endif; ?>
            </p>
            
            <div class="grid grid-cols-1 gap-4 mt-6">
                <?php if ($code === 404): ?>
                    <a href="/" class="flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-accent-navy hover:bg-accent-ceramicblue focus:outline-none transition-colors duration-200">
                        Go to Homepage
                    </a>
                <?php elseif ($code === 403): ?>
                    <a href="/login" class="flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-accent-navy hover:bg-accent-ceramicblue focus:outline-none transition-colors duration-200">
                        Log In
                    </a>
                    <a href="/" class="flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none transition-colors duration-200">
                        Go to Homepage
                    </a>
                <?php else: ?>
                    <a href="javascript:history.back()" class="flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-accent-navy hover:bg-accent-ceramicblue focus:outline-none transition-colors duration-200">
                        Go Back
                    </a>
                    <a href="/" class="flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none transition-colors duration-200">
                        Go to Homepage
                    </a>
                <?php endif; ?>
            </div>
            
            <?php if ($isDebug && isset($exception) && $exception instanceof \App\core\exception\ValidationException): ?>
                <div class="mt-6 text-left bg-gray-50 p-4 rounded-md">
                    <h4 class="font-medium text-gray-800 mb-2">Validation Errors:</h4>
                    <ul class="list-disc pl-5 text-sm text-gray-600">
                        <?php foreach ($exception->getErrors() as $field => $errors): ?>
                            <?php foreach ((array)$errors as $error): ?>
                                <li><strong><?= htmlspecialchars($field) ?>:</strong> <?= htmlspecialchars($error) ?></li>
                            <?php endforeach; ?>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>


