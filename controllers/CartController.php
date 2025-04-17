<?php

namespace App\controllers;

use App\core\Application;
use App\core\Controller;
use App\core\Request;
use App\services\CartService;

class CartController extends Controller
{
    private CartService $cartService;
    private Request $request;

    
    public function __construct()
    {
        parent::__construct();
        $this->cartService = new CartService();
        $this->request = new Request();
    }
    
    public function index()
    {
        $cartItems = $this->cartService->getCartItems();
        $cartTotal = $this->cartService->getCartTotal();
        
        return $this->render('cart/index', [
            'cartItems' => $cartItems,
            'cartTotal' => $cartTotal
        ]);
    }
    
    public function add(Request $request)
    {
        if (!$request->isPost()) { //check other than POST not valid
            if ($request->isXhr()) {
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => false,
                    'message' => 'Invalid request method'
                ]);
                exit;
            }
            
            Application::$app->response->redirect('/cart');
            return;
        }
        
        $productId = (int)$request->getbody()['product_id'] ?? 0;
        $quantity = (int)$request->getbody()['quantity'] ?? 1;
        
        if ($productId <= 0 || $quantity <= 0) {
            if ($request->isXhr()) {
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => false,
                    'message' => 'Invalid product or quantity'
                ]);
                exit;
            }
            
            Application::$app->session->setFlash('error', 'Invalid product or quantity');
            Application::$app->response->redirect('/products');
            return;
        }
        
        $success = $this->cartService->addItem($productId, $quantity);
        
        if ($request->isXhr()) {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => $success,
                'message' => $success ? 'Product added to cart' : 'Failed to add product to cart',
                'cartCount' => $this->cartService->getItemCount(),
                'cartTotal' => $this->cartService->getCartTotal()
            ]);
            exit;
        }
        
        if ($success) {
            Application::$app->session->setFlash('success', 'Product added to cart');
        } else {
            Application::$app->session->setFlash('error', 'Failed to add product to cart');
        }
        
        $referer = $_SERVER['HTTP_REFERER'] ?? '/products';
        Application::$app->response->redirect($referer);
    }
    
    public function update(Request $request)
    {
        if (!$request->isPost()) {
            if ($request->isXhr()) {
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => false,
                    'message' => 'Invalid request method'
                ]);
                exit;
            }
            
            Application::$app->response->redirect('/cart');
            return;
        }
        
        $productId = (int)$request->getbody()['product_id'] ?? 0;
        $quantity = (int)$request->getbody()['quantity'] ?? 0;
        
        if ($productId <= 0) {
            if ($request->isXhr()) {
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => false,
                    'message' => 'Invalid product'
                ]);
                exit;
            }
            
            Application::$app->session->setFlash('error', 'Invalid product');
            Application::$app->response->redirect('/cart');
            return;
        }
        
        $success = $this->cartService->updateItem($productId, $quantity);
        
        if ($request->isXhr()) {
            header('Content-Type: application/json');
            
            $cartItems = $this->cartService->getCartItems();
            $cartTotal = $this->cartService->getCartTotal();
            
            // Calculate item total
            $itemTotal = 0;
            foreach ($cartItems as $item) {
                if ($item->product_id == $productId) {
                    $itemTotal = $item->getTotal();
                    break;
                }
            }
            
            echo json_encode([
                'success' => $success,
                'message' => $success ? 'Cart updated' : 'Failed to update cart',
                'itemTotal' => $itemTotal,
                'cartTotal' => $cartTotal,
                'cartCount' => $this->cartService->getItemCount(),
                'quantity' => $quantity
            ]);
            exit;
        }
        
        if ($success) {
            Application::$app->session->setFlash('success', 'Cart updated');
        } else {
            Application::$app->session->setFlash('error', 'Failed to update cart');
        }
        
        Application::$app->response->redirect('/cart');
    }
    
    public function remove(Request $request)
    {
        $productId = (int)$request->getQuery('id') ?? 0;
        
        if ($productId <= 0) {
            if ($request->isXhr()) {
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => false,
                    'message' => 'Invalid product'
                ]);
                exit;
            }
            
            Application::$app->session->setFlash('error', 'Invalid product');
            Application::$app->response->redirect('/cart');
            return;
        }
        
        $success = $this->cartService->removeItem($productId);
        
        if ($request->isXhr()) {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => $success,
                'message' => $success ? 'Product removed from cart' : 'Failed to remove product from cart',
                'cartCount' => $this->cartService->getItemCount(),
                'cartTotal' => $this->cartService->getCartTotal()
            ]);
            exit;
        }
        
        if ($success) {
            Application::$app->session->setFlash('success', 'Product removed from cart');
        } else {
            Application::$app->session->setFlash('error', 'Failed to remove product from cart');
        }
        
        Application::$app->response->redirect('/cart');
    }
    
    public function clear()
    {
        $this->cartService->clearCart();
        
        if ($this->request->isXhr()) {
            echo json_encode([
                'success' => true,
                'message' => 'Cart cleared'
            ]);
            return;
        }
        
        Application::$app->session->setFlash('success', 'Cart cleared');
        Application::$app->response->redirect('/cart');
    }
}