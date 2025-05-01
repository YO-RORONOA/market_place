<?php

namespace App\services;

use App\core\Application;
use App\models\CartItem;
use App\repositories\ProductRepository;
use App\repositories\CartRepository;

class CartService
{
    private $productRepository;
    private $cartRepository;
    private const CART_SESSION_KEY = 'cart_items';

    public function __construct()
    {
        $this->productRepository = new ProductRepository();
        $this->cartRepository = new CartRepository();
    }

    public function getCart(): array
    {
        $userId = $this->getCurrentUserId();
        $cart = Application::$app->session->get(self::CART_SESSION_KEY);

        if ($userId && (empty($cart) || !is_array($cart))) {
            $cart = $this->loadCartFromDatabase($userId);
            Application::$app->session->set(self::CART_SESSION_KEY, $cart);
        }

        return is_array($cart) ? $cart : [];
    }

    public function addItem(int $productId, int $quantity = 1): bool
    {
        $product = $this->productRepository->findOne($productId);

        if (!$product) {
            return false;
        }

        $cart = $this->getCart();

        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] += $quantity;
        } else {
            $cart[$productId] = [
                'product_id' => $product['id'],
                'name' => $product['name'],
                'price' => $product['price'],
                'quantity' => $quantity,
                'image_path' => $product['image_path']
            ];
        }

        Application::$app->session->set(self::CART_SESSION_KEY, $cart);

        $this->persistCartToDatabase($cart);

        return true;
    }

    public function updateItem(int $productId, int $quantity): bool
    {
        $cart = $this->getCart();

        if (!isset($cart[$productId])) {
            return false;
        }

        if ($quantity <= 0) {
            return $this->removeItem($productId);
        }

        $cart[$productId]['quantity'] = $quantity;
        Application::$app->session->set(self::CART_SESSION_KEY, $cart);

        $this->persistCartToDatabase($cart);

        return true;
    }

    public function removeItem(int $productId): bool
    {
        $cart = $this->getCart();

        if (!isset($cart[$productId])) {
            return false;
        }

        unset($cart[$productId]);
        Application::$app->session->set(self::CART_SESSION_KEY, $cart);

        $this->persistCartToDatabase($cart);

        return true;
    }

    public function clearCart(): void
    {
        Application::$app->session->set(self::CART_SESSION_KEY, []);

        $userId = $this->getCurrentUserId();
        if ($userId) {
            $this->cartRepository->clearCart($userId);
        }
    }

    public function clearSessionCart(): void
    {
        Application::$app->session->set(self::CART_SESSION_KEY, []);
    }

    public function getItemCount(): int
    {
        $cart = $this->getCart();
        return array_sum(array_column($cart, 'quantity'));
    }

    public function getCartTotal(): float
    {
        $cart = $this->getCart();
        $total = 0;

        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        return $total;
    }

    public function getCartItems(): array
    {
        $cart = $this->getCart();
        $items = [];

        foreach ($cart as $item) {
            $items[] = new CartItem(
                $item['product_id'],
                $item['name'],
                $item['price'],
                $item['quantity'],
                $item['image_path']
            );
        }

        return $items;
    }

    private function getCurrentUserId(): ?int
    {
        $user = Application::$app->session->get('user');
        return $user ? ($user['id'] ?? null) : null;
    }

    private function persistCartToDatabase(array $cart): bool
    {
        $userId = $this->getCurrentUserId();
        if (!$userId) {
            return false;
        }

        return $this->cartRepository->saveCartItems($userId, $cart);
    }

    private function loadCartFromDatabase(int $userId): array
    {
        $cartItems = $this->cartRepository->getCartItems($userId);

        $cart = [];
        foreach ($cartItems as $item) {
            $cart[$item['product_id']] = [
                'product_id' => $item['product_id'],
                'name' => $item['name'],
                'price' => $item['price'],
                'quantity' => $item['quantity'],
                'image_path' => $item['image_path']
            ];
        }

        return $cart;
    }

    public function initializeUserCart(int $userId): void
    {
        Application::$app->session->set(self::CART_SESSION_KEY, []);

        $cart = $this->loadCartFromDatabase($userId);

        Application::$app->session->set(self::CART_SESSION_KEY, $cart);
    }

    public function persistVisitorCartToUser(int $userId): void
    {
        $visitorCart = $this->getCart(); 

        if (empty($visitorCart)) {
            return; 
        }

        $userCart = $this->loadCartFromDatabase($userId);

        foreach ($visitorCart as $productId => $item) {
            if (isset($userCart[$productId])) {
                $userCart[$productId]['quantity'] = max($userCart[$productId]['quantity'], $item['quantity']);
            } else {
                $userCart[$productId] = $item;
            }
        }

        Application::$app->session->set(self::CART_SESSION_KEY, $userCart);

        $this->cartRepository->clearCart($userId);
        $this->cartRepository->saveCartItems($userId, $userCart);
    }
}
