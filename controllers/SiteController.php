<?php


namespace App\controllers;

use App\core\Application;
use App\core\Controller;
use App\core\Request;
use App\repositories\CategoryRepository;
use App\repositories\ProductRepository;

class SiteController extends Controller
{
    private CategoryRepository $categoryRepository;
    private ProductRepository $productRepository;

    public function __construct()
    {
        parent::__construct();
        $this->categoryRepository = new CategoryRepository();
        $this->productRepository = new ProductRepository();
    }
    public function home()
    {
        $imgs = [
            '/assets/images/buy_physical_books.webp',
            '/assets/images/clothingWallpaper.jpg',
            '/assets/images/healthWallpaper.jpg',
            '/assets/images/electronics.jpg'
        ];

        $categories = $this->categoryRepository->getMainCategories();
        
        $newArrivals = $this->productRepository->findAll(
            ['status' => 'active'], 
            false,
            'created_at DESC',
            8 
        );
        
        $popularProducts = $this->productRepository->findAll(
            ['status' => 'active'], 
            false,
            'created_at DESC',
            8
        );
        
        $categoryProducts = [];
        foreach ($categories as $category) {
            $products = $this->productRepository->findByCategory(
                $category['id'],
                4, 
                0
            );
            
            if (!empty($products)) {
                $categoryProducts[$category['id']] = [
                    'name' => $category['name'],
                    'products' => $products
                ];
            }
        }
        
        return $this->render('home/index', [
            'imgs' => $imgs,
            'categories' => $categories,
            'newArrivals' => $newArrivals,
            'popularProducts' => $popularProducts,
            'categoryProducts' => $categoryProducts,
            'title' => 'Welcome to YOU/Market'
        ]);
    }

    public function login()
    {
        $this->setLayout('auth');
        return $this->render('login');
    }

    public function contact()
    {
        return $this->render('contact');
    }

    public function handleContact(Request $request)
    {

        $body = $request->getbody();
        var_dump($body);
    }
}