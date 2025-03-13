<?php
/**
 * User: TheCodeholic
 * Date: 7/7/2020
 * Time: 9:57 AM
 */

 use App\controllers\SiteController;
 use App\core\Application;
 use App\controllers\AuthController;

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




$app->router->get('/cart', [App\controllers\CartController::class, 'index']);
$app->router->post('/cart/add', [App\controllers\CartController::class, 'add']);
$app->router->post('/cart/update', [App\controllers\CartController::class, 'update']);
$app->router->get('/cart/remove', [App\controllers\CartController::class, 'remove']);
$app->router->get('/cart/clear', [App\controllers\CartController::class, 'clear']);



$app->run();