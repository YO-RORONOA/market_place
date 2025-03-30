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
    
    public function analytics()
    {
        $vendorId = Application::$app->session->get('user')['id'] ?? 0;
        
        $stats = $this->vendorRepository->getStoreStats($vendorId);
        
        $monthlySales = $this->getVendorMonthlySales($vendorId);
        
        $categoryDistribution = $this->getProductCategoryDistribution($vendorId);
        
        $topProducts = $this->vendorRepository->getTopProducts($vendorId, 5);
        
        $ordersPerDay = $this->getOrdersPerDay($vendorId, 14); 
        
        return $this->render('vendor/analytics/index', [
            'stats' => $stats,
            'monthlySales' => $monthlySales,
            'categoryDistribution' => $categoryDistribution,
            'topProducts' => $topProducts,
            'ordersPerDay' => $ordersPerDay,
            'title' => 'Analytics'
        ]);
    }

    private function getVendorMonthlySales($vendorId, $limit = 6)
    {
        $sql = "SELECT 
                    DATE_TRUNC('month', o.created_at) as month,
                    SUM(oi.price * oi.quantity) as total_sales,
                    COUNT(DISTINCT o.id) as order_count
                FROM 
                    orders o
                JOIN 
                    order_items oi ON o.id = oi.order_id
                JOIN 
                    products p ON oi.product_id = p.id
                WHERE 
                    p.vendor_id = :vendor_id
                    AND o.status = 'paid'
                GROUP BY 
                    DATE_TRUNC('month', o.created_at)
                ORDER BY 
                    month DESC
                LIMIT :limit";
        
        $statement = Application::$app->db->pdo->prepare($sql);
        $statement->bindValue(':vendor_id', $vendorId);
        $statement->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $statement->execute();
        
        $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
        
        foreach ($result as &$row) {
            $row['month'] = date('M Y', strtotime($row['month']));
        }
        
        return array_reverse($result);
    }
    
    private function getProductCategoryDistribution($vendorId)
    {
        $sql = "SELECT 
                    c.name as category_name,
                    COUNT(p.id) as product_count
                FROM 
                    products p
                JOIN 
                    categories c ON p.category_id = c.id
                WHERE 
                    p.vendor_id = :vendor_id
                    AND p.deleted_at IS NULL
                GROUP BY 
                    c.name
                ORDER BY 
                    product_count DESC";
        
        $statement = Application::$app->db->pdo->prepare($sql);
        $statement->bindValue(':vendor_id', $vendorId);
        $statement->execute();
        
        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    private function getOrdersPerDay($vendorId, $days = 14)
    {
        $sql = "SELECT 
                    DATE(o.created_at) as order_date,
                    COUNT(DISTINCT o.id) as order_count,
                    SUM(oi.price * oi.quantity) as daily_total
                FROM 
                    orders o
                JOIN 
                    order_items oi ON o.id = oi.order_id
                JOIN 
                    products p ON oi.product_id = p.id
                WHERE 
                    p.vendor_id = :vendor_id
                    AND o.created_at >= CURRENT_DATE - :days::INTERVAL
                GROUP BY 
                    DATE(o.created_at)
                ORDER BY 
                    order_date";
        
        $statement = Application::$app->db->pdo->prepare($sql);
        $statement->bindValue(':vendor_id', $vendorId);
        $statement->bindValue(':days', $days . ' days');
        $statement->execute();
        
        $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
        
        foreach ($result as &$row) {
            $row['order_date'] = date('M d', strtotime($row['order_date']));
        }
        
        return $result;
    }
    
    private function getVendorOrders($vendorId, $conditions = [], $limit = 10, $offset = 0)
    {
        $sql = "SELECT DISTINCT o.id
                FROM orders o
                JOIN order_items oi ON o.id = oi.order_id
                JOIN products p ON oi.product_id = p.id
                WHERE p.vendor_id = :vendor_id";
        
        if (!empty($conditions)) {
            foreach ($conditions as $key => $value) {
                $sql .= " AND o.$key = :$key";
            }
        }
        
        $sql .= " ORDER BY o.created_at DESC
                  LIMIT :limit OFFSET :offset";
        
        $statement = Application::$app->db->pdo->prepare($sql);
        $statement->bindValue(':vendor_id', $vendorId);
        
        if (!empty($conditions)) {
            foreach ($conditions as $key => $value) {
                $statement->bindValue(":$key", $value);
            }
        }
        
        $statement->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $statement->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $statement->execute();
        
        $orderIds = $statement->fetchAll(\PDO::FETCH_COLUMN);
        
        if (empty($orderIds)) {
            return [];
        }
        
        $orders = [];
        foreach ($orderIds as $orderId) {
            $order = $this->orderRepository->findOne($orderId);
            if ($order) {
                $items = $this->orderItemRepository->findByOrderId($orderId);
                
                $vendorItems = [];
                $vendorTotal = 0;
                
                foreach ($items as $item) {
                    $product = $this->productRepository->findOne($item['product_id']);
                    
                    if ($product && $product['vendor_id'] == $vendorId) {
                        $item['product'] = $product;
                        $vendorItems[] = $item;
                        $vendorTotal += $item['price'] * $item['quantity'];
                    }
                }
                
                $order['vendor_items'] = $vendorItems;
                $order['vendor_total'] = $vendorTotal;
                
                $order['customer'] = $this->userRepository->findOne($order['user_id']);
                
                $orders[] = $order;
            }
        }
        
        return $orders;
    }
    
    private function countVendorOrders($vendorId, $conditions = [])
    {
        $sql = "SELECT COUNT(DISTINCT o.id)
                FROM orders o
                JOIN order_items oi ON o.id = oi.order_id
                JOIN products p ON oi.product_id = p.id
                WHERE p.vendor_id = :vendor_id";
        
        if (!empty($conditions)) {
            foreach ($conditions as $key => $value) {
                $sql .= " AND o.$key = :$key";
            }
        }
        
        $statement = Application::$app->db->pdo->prepare($sql);
        $statement->bindValue(':vendor_id', $vendorId);
        
        if (!empty($conditions)) {
            foreach ($conditions as $key => $value) {
                $statement->bindValue(":$key", $value);
            }
        }
        
        $statement->execute();
        
        return (int)$statement->fetchColumn();
    }
    
    private function getVendorOrdersByStatus($vendorId, $status)
    {
        $sql = "SELECT DISTINCT o.id
                FROM orders o
                JOIN order_items oi ON o.id = oi.order_id
                JOIN products p ON oi.product_id = p.id
                WHERE p.vendor_id = :vendor_id
                AND o.status = :status";
        
        $statement = Application::$app->db->pdo->prepare($sql);
        $statement->bindValue(':vendor_id', $vendorId);
        $statement->bindValue(':status', $status);
        $statement->execute();
        
        return $statement->fetchAll(\PDO::FETCH_COLUMN);
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

    public function generateDescription(Request $request)
    {
        if (!$request->isPost()) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid request method']);
            exit;
        }
        
        $data = $request->getBody();
        $productName = $data['name'] ?? '';
        $category = $data['category'] ?? '';
        
        if (empty($productName)) {
            http_response_code(400);
            echo json_encode(['error' => 'Product name is required']);
            exit;
        }
        
        try {
            $aiService = new \App\services\AIService();
            $description = $aiService->generateProductDescription($productName, $category);
            
            echo json_encode(['description' => $description]);
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
        
        exit;
    }
    
    public function generateTags(Request $request)
    {
        if (!$request->isPost()) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid request method']);
            exit;
        }
        
        $data = json_decode(file_get_contents('php://input'), true);
        $productName = $data['name'] ?? '';
        $category = $data['category'] ?? '';
        $description = $data['description'] ?? '';
        
        if (empty($productName)) {
            http_response_code(400);
            echo json_encode(['error' => 'Product name is required']);
            exit;
        }
        
        try {
            $aiService = new \App\services\AIService();
            $tags = $aiService->generateProductTags($productName, $category, $description);
            
            echo json_encode(['tags' => $tags]);
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
        
        exit;
    }
    
    public function settings(Request $request)
    {
        $userId = Application::$app->session->get('user')['id'] ?? 0;
        $vendor = $this->vendorRepository->findByUserId($userId);
        
        if (!$vendor) {
            Application::$app->session->setFlash('error', 'Vendor profile not found');
            Application::$app->response->redirect('/vendor/dashboard');
            return;
        }
        
        if ($request->isPost()) {
            $data = $request->getBody();
            
            $success = $this->vendorRepository->update($vendor->id, [
                'store_name' => $data['store_name'] ?? $vendor->store_name,
                'description' => $data['description'] ?? $vendor->description
            ]);
            
            if ($success) {
                Application::$app->session->setFlash('success', 'Store settings updated successfully');
                Application::$app->response->redirect('/vendor/settings');
                return;
            } else {
                Application::$app->session->setFlash('error', 'Failed to update store settings');
            }
        }
        
        return $this->render('vendor/settings/index', [
            'vendor' => $vendor,
            'title' => 'Store Settings'
        ]);
    }
}