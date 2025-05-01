<?php

namespace App\controllers;

use App\core\Application;
use App\core\Controller;
use App\core\Request;

class TestController extends Controller
{
    /**
     * Load test products into cart and redirect to checkout
     */
    public function loadTestCart()
    {
        $testProducts = [
            [
                'product_id' => 1,
                'name' => 'Handcrafted Moroccan Ceramic Plate',
                'price' => 39.99,
                'quantity' => 2,
                'image_path' => '/uploads/products/moroccan-plate.jpg'
            ],
            [
                'product_id' => 2,
                'name' => 'Blue Moroccan Ceramic Tagine',
                'price' => 64.99,
                'quantity' => 1,
                'image_path' => '/uploads/products/blue-tagine.jpg'
            ],
            [
                'product_id' => 5,
                'name' => 'Silver Moroccan Filigree Earrings',
                'price' => 75.99,
                'quantity' => 1,
                'image_path' => '/uploads/products/silver-earrings.jpg'
            ]
        ];
        
        // Store test products in session
        Application::$app->session->set('cart_items', array_reduce(
            $testProducts, 
            function($carry, $item) {
                $carry[$item['product_id']] = $item;
                return $carry;
            }, 
            []
        ));
        
        if (!Application::$app->session->get('user')) {
            Application::$app->session->set('user', [
                'id' => 1, 
                'name' => 'Test User',
                'email' => 'test@example.com',
                'role_id' => 1 // Buyer role
            ]);
        }
        
        Application::$app->session->setFlash('success', 'Cart loaded with test products');
        Application::$app->response->redirect('/checkout');
    }
}