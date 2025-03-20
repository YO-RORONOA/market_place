<?php

use App\core\Application;
use App\core\Controller;
use App\Core\Middlewares\VendorMiddleware;
use App\core\Request;
use App\repositories\CategoryRepository;
use App\repositories\ProductRepository;

class VendorController extends Controller
{
    private ProductRepository $productRepository;
    private CategoryRepository $categoryRepository;

    public function __construct()
    {
        parent::__construct();
        $this->productRepository = new ProductRepository();
        $this->categoryRepository = new CategoryRepository();

        $this->registerMiddleware(new VendorMiddleware);
        $this->setLayout('vendor');
    }


    public function dashboard()
    {
        return $this->render('vendor/dashboard', [
            'title' => 'vendor Dashboard'
        ]);
    }

    public function products(Request $request)
    {
        $vendorId = Application::$app->session->get('user')['id'] ?? 0;
        $page = (int)$request->getQuery('page', 1);
        $limit = 10;
        $offset = ($page - 1) * $limit;

        $products = $this->productRepository->findByVendor($vendorId, $limit, $offset);
        $totalProducts = $this->productRepository->countProducts(['vendor_id' => $vendorId]);
        $totalPages = ceil($totalProducts / $limit);

        return $this->render('vendor/products/index', [
            'products' => $products,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalProducts' => $totalProducts,
            'title' => 'My Products'
        ]);


    }

    

}