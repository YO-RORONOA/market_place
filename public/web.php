<?php
// File: web.php (Updated with error handling)

use App\controllers\SiteController;
use App\core\Application;
use App\controllers\AuthController;
use App\controllers\VendorAuthController;
use App\controllers\VendorController;
use App\core\exception\ExceptionHandler;

require_once __DIR__.'/../vendor/autoload.php';

// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

// Define application configuration
$config = [
    'db' => [
        'dsn' => $_ENV['DB_DSN'],
        'user' => $_ENV['DB_USER'],
        'password' => $_ENV['DB_PASSWORD'],
    ],
    // You can enable maintenance mode if needed
    // 'maintenance_mode' => true,
];

// Create the application instance
try {
    $app = new Application(dirname(__DIR__), $config);
    
    // Register routes
    $app->router->post('/vendor/orders/update-status', [App\controllers\VendorController::class, 'updateOrderStatus']);
$app->router->post('/vendor/orders/bulk-update-status', [App\controllers\VendorController::class, 'bulkUpdateOrderStatus']);
$app->router->post('/vendor/orders/add-note', [App\controllers\VendorController::class, 'addOrderNote']);
$app->router->get('/vendor/orders/view', [App\controllers\VendorController::class, 'orderDetails']);

    // Site routes
    $app->router->get('/', [SiteController::class, 'home']);
    $app->router->get('/contact', [SiteController::class, 'contact']);
    
    // Authentication routes
    $app->router->get('/login', [AuthController::class, 'login']);
    $app->router->post('/login', [AuthController::class, 'login']);
    
    $app->router->get('/register', [AuthController::class, 'register']);
    $app->router->post('/register', [AuthController::class, 'register']);
    $app->router->get('/verify-email', [AuthController::class, 'verifyEmail']);
    
    $app->router->get('/logout', [AuthController::class, 'logout']);
    
    $app->router->get('/forgot-password', [AuthController::class, 'forgotPassword']);
    $app->router->post('/forgot-password', [AuthController::class, 'forgotPassword']);
    $app->router->get('/password-reset-sent', [AuthController::class, 'passwordResetSent']);
    $app->router->get('/ResetPassword', [AuthController::class, 'resetPassword']);
    $app->router->post('/ResetPassword', [AuthController::class, 'resetPassword']);
    
    $app->router->get('/email-verification', [AuthController::class, 'emailVerificationPage']);
    $app->router->get('/verify-email', [AuthController::class, 'verifyEmail']);
    $app->router->post('/resend-verification', [AuthController::class, 'resendVerification']);
    $app->router->get('/resend-verification', [AuthController::class, 'emailVerificationPage']);
    
    $app->router->get('/logout', [AuthController::class, 'logout']);
    $app->router->get('/InvalidToken', [AuthController::class, 'invalidToken']);
    $app->router->get('/passwordResetSent', [AuthController::class, 'passwordResetSent']);
    
    // Product routes
    $app->router->get('/products', [App\controllers\ProductController::class, 'index']);
    $app->router->get('/products/view', [App\controllers\ProductController::class, 'view']);
    
    // Cart routes
    $app->router->get('/cart', [App\controllers\CartController::class, 'index']);
    $app->router->post('/cart/add', [App\controllers\CartController::class, 'add']);
    $app->router->post('/cart/update', [App\controllers\CartController::class, 'update']);
    $app->router->get('/cart/remove', [App\controllers\CartController::class, 'remove']);
    $app->router->get('/cart/clear', [App\controllers\CartController::class, 'clear']);
    
    // Checkout routes
    $app->router->get('/checkout', [App\controllers\CheckoutController::class, 'index']);
    $app->router->post('/checkout/process', [App\controllers\CheckoutController::class, 'process']);
    $app->router->get('/checkout/success', [App\controllers\CheckoutController::class, 'success']);
    $app->router->get('/checkout/cancel', [App\controllers\CheckoutController::class, 'cancel']);
    
    // Webhook routes
    $app->router->post('/webhook/stripe', [App\controllers\CheckoutController::class, 'webhook']);
    
    // Test routes
    $app->router->get('/test/load-cart', [App\controllers\TestController::class, 'loadTestCart']);
    
    // Vendor routes
    $app->router->get('/vendor/login', [VendorAuthController::class, 'login']);
    $app->router->post('/vendor/login', [VendorAuthController::class, 'login']);
    $app->router->get('/vendor/register', [VendorAuthController::class, 'register']);
    $app->router->post('/vendor/register', [VendorAuthController::class, 'register']);
    
    $app->router->get('/vendor/dashboard', [VendorController::class, 'dashboard']);
    $app->router->get('/vendor/products', [VendorController::class, 'products']);
    $app->router->get('/vendor/products/create', [VendorController::class, 'createProduct']);
    $app->router->post('/vendor/products/store', [VendorController::class, 'storeProduct']);
    $app->router->get('/vendor/products/edit', [VendorController::class, 'editProduct']);
    $app->router->post('/vendor/products/update', [VendorController::class, 'updateProduct']);
    $app->router->get('/vendor/products/delete', [VendorController::class, 'deleteProduct']);
    $app->router->post('/vendor/products/generate-description', [VendorController::class, 'generateDescription']);
    $app->router->post('/vendor/products/generate-tags', [VendorController::class, 'generateTags']);
    $app->router->get('/vendor/orders', [VendorController::class, 'orders']);
    
    $app->router->get('/vendor/switch', [VendorAuthController::class, 'switchToVendor']);
    $app->router->get('/buyer/switch', [VendorAuthController::class, 'switchToBuyer']);
    
    // Order routes
    $app->router->get('/orders', [App\controllers\OrderController::class, 'index']);
    $app->router->get('/orders/view', [App\controllers\OrderController::class, 'view']);
    $app->router->get('/orders/ajax', [App\controllers\OrderController::class, 'ajax']);
    $app->router->get('/orders/cancel', [App\controllers\OrderController::class, 'cancel']);
    
    // Error routes (for testing)
    $app->router->get('/test/error', function() {
        throw new \Exception('This is a test error');
    });
    
    $app->router->get('/test/not-found', function() {
        throw new \App\core\exception\NotFoundException('This page does not exist');
    });
    
    $app->router->get('/test/forbidden', function() {
        throw new \App\core\exception\ForbiddenException('You are not allowed to access this page');
    });
    
    $app->router->get('/test/database-error', function() {
        throw new \App\core\exception\DatabaseException('Test database connection error');
    });
    
    $app->router->get('/test/validation-error', function() {
        throw new \App\core\exception\ValidationException('Validation failed', [
            'email' => ['Email is not valid'],
            'password' => ['Password must be at least 8 characters']
        ]);
    });
    
    // Run the application
    $app->run();
} catch (Throwable $e) {
    // Catch any errors during application bootstrap
    if (isset($app)) {
        // If the app was created, let the exception handler handle it
        ExceptionHandler::handle($e);
    } else {
        // The app wasn't even created, show a basic error message
        http_response_code(500);
        echo "The application could not be started. Please check the server logs.";
        error_log($e->getMessage() . "\n" . $e->getTraceAsString());
    }
}