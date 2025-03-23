<?php


 use App\controllers\SiteController;
 use App\core\Application;
 use App\controllers\AuthController;
use App\controllers\VendorController;

require_once __DIR__.'/../vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();



$config = [
    'db' => [
        'dsn' => $_ENV['DB_DSN'],
        'user' => $_ENV['DB_USER'],
        'password' => $_ENV['DB_PASSWORD'],
    ]
    ];
$app = new Application(dirname(__DIR__), $config);

$app->router->get('/', [SiteController::class, 'home']);
$app->router->get('/contact', [SiteController::class, 'contact']);

$app->router->get('/login', [AuthController::class, 'login']);
$app->router->post('/login', [AuthController::class, 'login']);

$app->router->get('/register', [AuthController::class, 'register']);
$app->router->post('/register', [AuthController::class, 'register']);


$app->router->get('/register', [AuthController::class, 'register']);
$app->router->post('/register', [AuthController::class, 'register']);
$app->router->get('/verify-email', [AuthController::class, 'verifyEmail']);

$app->router->get('/login', [AuthController::class, 'login']);
$app->router->post('/login', [AuthController::class, 'login']);
$app->router->get('/logout', [AuthController::class, 'logout']);
$app->router->get('/verify-email', [AuthController::class, 'verifyEmail']);

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



$app->router->get('/products', [App\controllers\ProductController::class, 'index']);
$app->router->get('/products/view', [App\controllers\ProductController::class, 'view']);


$app->router->get('/cart', [App\controllers\CartController::class, 'index']);
$app->router->post('/cart/add', [App\controllers\CartController::class, 'add']);
$app->router->post('/cart/update', [App\controllers\CartController::class, 'update']);
$app->router->get('/cart/remove', [App\controllers\CartController::class, 'remove']);
$app->router->get('/cart/clear', [App\controllers\CartController::class, 'clear']);


$app->router->get('/checkout', [App\controllers\CheckoutController::class, 'index']);
$app->router->post('/checkout/process', [App\controllers\CheckoutController::class, 'process']);
$app->router->get('/checkout/success', [App\controllers\CheckoutController::class, 'success']);
$app->router->get('/checkout/cancel', [App\controllers\CheckoutController::class, 'cancel']);


$app->router->post('/webhook/stripe', [App\controllers\CheckoutController::class, 'webhook']);


$app->router->get('/test/load-cart', [App\controllers\TestController::class, 'loadTestCart']);











$app->router->get('/vendor/dashboard', [VendorController::class, 'dashboard']);
$app->router->get('/vendor/products', [VendorController::class, 'products']);
$app->router->get('/vendor/products/create', [VendorController::class, 'createProduct']);
$app->router->post('/vendor/products/store', [VendorController::class, 'storeProduct']);
$app->router->get('/vendor/products/edit', [VendorController::class, 'editProduct']);
$app->router->post('/vendor/products/update', [VendorController::class, 'updateProduct']);
$app->router->get('/vendor/products/delete', [VendorController::class, 'deleteProduct']);
$app->router->post('/vendor/products/generate-description', [VendorController::class, 'generateDescription']);
$app->router->post('/vendor/products/generate-tags', [VendorController::class, 'generateTags']);



$app->run();
