<?php

use App\core\Controller;
use App\Core\Middlewares\VendorMiddleware;
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

    

}