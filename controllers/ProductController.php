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
            $totalProducts = $this->productRepository->countProducts(['category_id' => $categoryId, 'status' => 'active']);
            $category = $this->categoryRepository->findOne($categoryId);
            $params['category'] = $category;
        }
        elseif($search)
        {
            $products = $this->productRepository->search($search, $limit, $offset);
            $totalProducts = count($this->productRepository->search($search, 100, 0));
            $params['search'] = $search;
        }
        else{
            $products = $this->productRepository->findAll(['status' => 'active'], false, 'created_at DESC', $limit, $offset);
            $totalProducts = $this->productRepository->countProducts(['status' => 'active']);

        }

        $totalPages = ceil($totalProducts/ $limit);

        $categories = $this->categoryRepository->getMainCategories();

        return $this->render('products/index', 
        [
            'products' => $products,
            'categories' => $categories,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalProducts' => $totalProducts,
            // ...$params
        ])

    }
}