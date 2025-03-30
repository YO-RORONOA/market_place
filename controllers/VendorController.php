<?php

namespace App\controllers;

use App\core\Application;
use App\core\Controller;
use App\Core\Middlewares\VendorMiddleware;
use App\core\Request;
use App\models\Product;
use App\repositories\CategoryRepository;
use App\repositories\ProductRepository;
use App\repositories\OrderRepository;
use App\repositories\VendorRepository;
use App\repositories\OrderItemRepository;
use App\repositories\UserRepository;

class VendorController extends Controller
{
    private ProductRepository $productRepository;
    private CategoryRepository $categoryRepository;
    private OrderRepository $orderRepository;
    private VendorRepository $vendorRepository;
    private OrderItemRepository $orderItemRepository;
    private UserRepository $userRepository;

    public function __construct()
    {
        parent::__construct();
        $this->productRepository = new ProductRepository();
        $this->categoryRepository = new CategoryRepository();
        $this->orderRepository = new OrderRepository();
        $this->vendorRepository = new VendorRepository();
        $this->orderItemRepository = new OrderItemRepository();
        $this->userRepository = new UserRepository();

        $this->registerMiddleware(new VendorMiddleware());
        $this->setLayout('vendor');
    }

    public function dashboard()
    {
        $userId = Application::$app->session->get('user')['id'] ?? 0;
        
        $vendor = $this->vendorRepository->findByUserId($userId);
        
        if (!$vendor) {
            Application::$app->session->setFlash('error', 'Vendor profile not found. Please contact support.');
            Application::$app->response->redirect('/');
            return;
        }
        
        $stats = $this->vendorRepository->getStoreStats($userId);
        
        $recentOrders = $this->vendorRepository->getRecentOrders($userId, 5);
        
        $topProducts = $this->vendorRepository->getTopProducts($userId, 3);
        
        if (empty($stats)) {
            $stats = [
                'productCount' => 0,
                'orderCount' => 0,
                'revenue' => 0
            ];
        }
        
        if (empty($recentOrders)) {
            $recentOrders = [];
        }
        
        if (empty($topProducts)) {
            $topProducts = [];
        }
        
        $pendingOrders = $this->getVendorOrdersByStatus($userId, 'pending');
        $processingOrders = $this->getVendorOrdersByStatus($userId, 'processing');
        $shippedOrders = $this->getVendorOrdersByStatus($userId, 'shipped');
        $completedOrders = $this->getVendorOrdersByStatus($userId, 'completed');
        $cancelledOrders = $this->getVendorOrdersByStatus($userId, 'cancelled');
        
        $monthlySales = $this->getVendorMonthlySales($userId);
        
        return $this->render('seller/dashboard', [
            'title' => 'Vendor Dashboard',
            'vendor' => $vendor,
            'stats' => $stats,
            'recentOrders' => $recentOrders,
            'topProducts' => $topProducts,
            'orderStats' => [
                'pending' => count($pendingOrders),
                'processing' => count($processingOrders),
                'shipped' => count($shippedOrders),
                'completed' => count($completedOrders),
                'cancelled' => count($cancelledOrders)
            ],
            'monthlySales' => $monthlySales
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
    
    public function orders(Request $request)
    {
        $vendorId = Application::$app->session->get('user')['id'] ?? 0;
        $page = (int)$request->getQuery('page', 1);
        $status = $request->getQuery('status', '');
        $limit = 10;
        $offset = ($page - 1) * $limit;
        
        $conditions = [];
        if (!empty($status)) {
            $conditions['status'] = $status;
        }
        
        $orders = $this->getVendorOrders($vendorId, $conditions, $limit, $offset);
        
        $totalOrders = $this->countVendorOrders($vendorId, $conditions);
        $totalPages = ceil($totalOrders / $limit);
        
        return $this->render('vendor/orders/index', [
            'orders' => $orders,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalOrders' => $totalOrders,
            'status' => $status,
            'title' => 'My Orders'
        ]);
    }
    
    public function orderDetails(Request $request)
    {
        $orderId = (int)$request->getQuery('id');
        $vendorId = Application::$app->session->get('user')['id'] ?? 0;
        
        if (!$orderId) {
            Application::$app->session->setFlash('error', 'Order ID is required');
            Application::$app->response->redirect('/vendor/orders');
            return;
        }
        
        $order = $this->orderRepository->findOne($orderId);
        
        if (!$order) {
            Application::$app->session->setFlash('error', 'Order not found');
            Application::$app->response->redirect('/vendor/orders');
            return;
        }
        
        $items = $this->orderItemRepository->findByOrderId($orderId);
        
        $vendorItems = [];
        $orderTotal = 0;
        
        foreach ($items as $item) {
            $product = $this->productRepository->findOne($item['product_id']);
            
            if ($product && $product['vendor_id'] == $vendorId) {
                $item['product'] = $product;
                $vendorItems[] = $item;
                $orderTotal += $item['price'] * $item['quantity'];
            }
        }
        
        if (empty($vendorItems)) {
            Application::$app->session->setFlash('error', 'You do not have any products in this order');
            Application::$app->response->redirect('/vendor/orders');
            return;
        }
        
        $customer = $this->userRepository->findOne($order['user_id']);
        
        return $this->render('vendor/orders/view', [
            'order' => $order,
            'items' => $vendorItems,
            'orderTotal' => $orderTotal,
            'customer' => $customer,
            'title' => 'Order #' . $orderId
        ]);
    }
    


    public function createProduct()
    {
        $product = new Product();
        $categories = $this->categoryRepository->findAll();

        return $this->render('vendor/products/create',
        [
            'model' => $product,
            'categories' => $categories,
            'title' => 'Create New Product'
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
                Application::$app->session->setFlash('success', 'Product created successfully');
                Application::$app->response->redirect('/vendor/products');
                return;
            }

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

    public function editProduct(Request $request)
    {
        $id = $request->getQuery('id');
        $vendorId = Application::$app->session->get('user')['id'] ?? 0;
        
        if (!$id) {
            Application::$app->session->setFlash('error', 'Product ID is required');
            Application::$app->response->redirect('/vendor/products');
            return;
        }
        
        $productData = $this->productRepository->findOne($id);
        
        if (!$productData || $productData['vendor_id'] != $vendorId) {
            Application::$app->session->setFlash('error', 'Product not found or you do not have permission to edit it');
            Application::$app->response->redirect('/vendor/products');
            return;
        }
        
        $product = new Product();
        $product->loadData($productData);
        $categories = $this->categoryRepository->findAll();
        
        return $this->render('vendor/products/edit', [
            'model' => $product,
            'categories' => $categories,
            'title' => 'Edit Product'
        ]);
    }

    public function updateProduct(Request $request)
    {
        $id = (int)$request->getbody()['id'] ?? 0;
        $vendorId = Application::$app->session->get('user')['id'] ?? 0;
        
        if (!$id) {
            Application::$app->session->setFlash('error', 'Product ID is required');
            Application::$app->response->redirect('/vendor/products');
            return;
        }
        
        $productData = $this->productRepository->findOne($id);
        
        if (!$productData || $productData['vendor_id'] != $vendorId) {
            Application::$app->session->setFlash('error', 'Product not found or you do not have permission to edit it');
            Application::$app->response->redirect('/vendor/products');
            return;
        }
        
        if ($request->isPost()) {
            $data = $request->getbody();
            
            $newImagePath = $this->handleImageUpload($request);
            if ($newImagePath) {
                $data['image_path'] = $newImagePath;
            } else {
                $data['image_path'] = $productData['image_path'];
            }
            
            $data['vendor_id'] = $vendorId;
            
            $success = $this->productRepository->update($id, $data);
            
            if ($success) {
                Application::$app->session->setFlash('success', 'Product updated successfully');
                Application::$app->response->redirect('/vendor/products');
                return;
            }
            
            Application::$app->session->setFlash('error', 'Failed to update product');
            
            $product = new Product();
            $product->loadData($data);
            $categories = $this->categoryRepository->findAll();
            
            return $this->render('vendor/products/edit', [
                'model' => $product,
                'categories' => $categories,
                'title' => 'Edit Product'
            ]);
        }
    }

    public function deleteProduct(Request $request)
    {
        $id = $request->getQuery('id');
        $vendorId = Application::$app->session->get('user')['id'] ?? 0;
        
        if (!$id) {
            Application::$app->session->setFlash('error', 'Product ID is required');
            Application::$app->response->redirect('/vendor/products');
            return;
        }
        
        $productData = $this->productRepository->findOne($id);
        
        if (!$productData || $productData['vendor_id'] != $vendorId) {
            Application::$app->session->setFlash('error', 'Product not found or you do not have permission to delete it');
            Application::$app->response->redirect('/vendor/products');
            return;
        }
        
        $success = $this->productRepository->delete($id);
        
        if ($success) {
            Application::$app->session->setFlash('success', 'Product deleted successfully');
        } else {
            Application::$app->session->setFlash('error', 'Failed to delete product');
        }
        
        Application::$app->response->redirect('/vendor/products');
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

  