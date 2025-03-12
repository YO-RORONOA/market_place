<?php

use App\core\Controller;
use App\core\Request;
use App\repositories\CategoryRepository;
use App\repositories\ProductRepository;

class ProductController extends Controller
{
    private ProductRepository $productRepository;
    private CategoryRepository $categoryRepository;

    public function __construct()
    {
        parent::__construct();
        $this->productRepository = new ProductRepository();
        $this->categoryRepository = new CategoryRepository();
    }

    public function index(Request $request)
    {
        $page = (int)$request->getQuery('page', 1);
        $limit = 12;
        $offset = ($page -1) * $limit;

        $categoryId = $request->getQuery('category');
        $search = $request->getQuery('search');


        $params = [];
        $products = [];
        $totalProducts = 0;

        if($categoryId)
        {
            $products = $this->productRepository->findByCategory($categoryId, $limit, $offset);
        }

    }
}