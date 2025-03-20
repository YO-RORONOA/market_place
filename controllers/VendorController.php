<?php

use App\core\Application;
use App\core\Controller;
use App\Core\Middlewares\VendorMiddleware;
use App\core\Request;
use App\models\Product;
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

    public function createProduct()
    {
        $product = new Product();
        $categories = $this->categoryRepository->findAll();

        return $this->render('vendor/products/create',
        [
            'model' =>$product,
            'categories' => $categories,
            'title' => 'create New Product'
        ]);
    }

    public function storeProduct(Request $request)
    {
        $vendorId = Application::$app->session->get('user')['id'] ?? 0;
        $product = new Product();

        if($request->isPost())
        {
            $data = $request->getbody();
            $data['vendor_id'] = $vendorId;

            $imagePath = $this->handleImageUpload($request);
            if($imagePath)
            {
                $data['image_path'] = $imagePath;
            }

            $productId = $this->productRepository->create($data);

            if($productId)
            {
                Application::$app->session->setFlash('success', 'product created successfully');
                Application::$app->response->redirect('/vendor/products');
                return;
            }

            //in case of error

            $product->loadData($data);
            Application::$app->session->setFlash('error', 'Failed to create product');

            $categories = $this->categoryRepository->findAll();
        
        return $this->render('vendor/products/create', [
            'model' => $product,
            'categories' => $categories,
            'title' => 'Create New Product'
        ]);

        }
    }


    private function handleImageUpload(Request $request)
    {
        if(!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK)
        {
            return null;
        }

        $uploadDir = Application::$ROOT_DIR . '/public/uploads/products/';

        if(!is_dir($uploadDir))
        {
            mkdir($uploadDir, 0755, true);
        }

        $filename = uniqid() . '_' . $_FILES['image']['name'];
        $uploadPath = $uploadDir . $filename;

        if(move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath))
        {
            return '/uploads/products/' . $filename;
        }

        return null;
    }


    

}