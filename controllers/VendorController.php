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
        $page =  max(1, (int)$request->getQuery('page', 1));
        $limit = 10;
        $offset = ($page - 1) * $limit;

        $products = $this->productRepository->findByVendor($vendorId, $limit, $offset);
        $totalProducts = $this->productRepository->countProducts(['vendor_id' => $vendorId]);
        $totalPages = ceil($totalProducts / $limit);

        return $this->render('seller/products/index', [
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
        $page = max(1, (int)$request->getQuery('page', 1));
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
        
        return $this->render('seller/orders/index', [
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
        
        return $this->render('seller/orders/view', [
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
    $sql = "SELECT DISTINCT o.id, o.created_at
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
    
    $results = $statement->fetchAll(\PDO::FETCH_ASSOC);
    $orderIds = array_column($results, 'id');
    
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

        return $this->render('seller/products/create',
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
        
            return $this->render('seller/products/create', [
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
        
        return $this->render('seller/products/edit', [
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



























    public function updateOrderStatus(Request $request)
{
    if (!$request->isXhr()) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Invalid request method']);
        exit;
    }
    
    $vendorId = Application::$app->session->get('user')['id'] ?? 0;
    
    if (!$vendorId) {
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'You must be logged in to update order status']);
        exit;
    }
    
    $data = $request->isPost() ? 
            json_decode(file_get_contents('php://input'), true) : 
            $request->getBody();
    
    if (empty($data['order_id']) || empty($data['status'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Order ID and status are required']);
        exit;
    }
    
    $orderId = (int)$data['order_id'];
    $status = $data['status'];
    
    $orderItems = $this->orderItemRepository->findByOrderId($orderId);
    $hasVendorProducts = false;
    
    foreach ($orderItems as $item) {
        $product = $this->productRepository->findOne($item['product_id']);
        if ($product && $product['vendor_id'] == $vendorId) {
            $hasVendorProducts = true;
            break;
        }
    }
    
    if (!$hasVendorProducts) {
        http_response_code(403);
        echo json_encode(['success' => false, 'message' => 'You do not have permission to update this order']);
        exit;
    }
    
    $updateData = ['status' => $status];
    
    if ($status === 'shipped' && !empty($data['tracking_number'])) {
        $updateData['tracking_number'] = $data['tracking_number'];
        
        if (!empty($data['carrier'])) {
            $updateData['carrier'] = $data['carrier'];
        }
    }
    
    $success = $this->orderRepository->update($orderId, $updateData);
    
    if ($success && !empty($data['note'])) {
    
    }
    
    header('Content-Type: application/json');
    echo json_encode([
        'success' => $success,
        'message' => $success ? 'Order status updated successfully' : 'Failed to update order status',
        'status' => $status
    ]);
    exit;
}

/**
 * Bulk update order statuses
 * 
 * @param Request $request
 * @return void
 */
public function bulkUpdateOrderStatus(Request $request)
{
    if (!$request->isXhr()) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Invalid request method']);
        exit;
    }
    
    $vendorId = Application::$app->session->get('user')['id'] ?? 0;
    
    if (!$vendorId) {
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'You must be logged in to update order status']);
        exit;
    }
    
    $data = $request->isPost() ? 
            json_decode(file_get_contents('php://input'), true) : 
            $request->getBody();
    
    if (empty($data['order_ids']) || empty($data['status'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Order IDs and status are required']);
        exit;
    }
    
    $orderIds = $data['order_ids'];
    $status = $data['status'];
    
    $updatedCount = 0;
    
    foreach ($orderIds as $orderId) {
        $orderItems = $this->orderItemRepository->findByOrderId($orderId);
        $hasVendorProducts = false;
        
        foreach ($orderItems as $item) {
            $product = $this->productRepository->findOne($item['product_id']);
            if ($product && $product['vendor_id'] == $vendorId) {
                $hasVendorProducts = true;
                break;
            }
        }
        
        if ($hasVendorProducts) {
            $success = $this->orderRepository->update($orderId, ['status' => $status]);
            if ($success) {
                $updatedCount++;
            }
        }
    }
    
    header('Content-Type: application/json');
    echo json_encode([
        'success' => $updatedCount > 0,
        'message' => $updatedCount > 0 ? "$updatedCount orders updated successfully" : 'No orders were updated',
        'updated' => $updatedCount
    ]);
    exit;
}

/**
 * Add a note to an order
 * 
 * @param Request $request
 * @return void
 */
public function addOrderNote(Request $request)
{
    if (!$request->isXhr()) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Invalid request method']);
        exit;
    }
    
    $vendorId = Application::$app->session->get('user')['id'] ?? 0;
    
    if (!$vendorId) {
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'You must be logged in to add notes']);
        exit;
    }
    
    $data = $request->isPost() ? 
            json_decode(file_get_contents('php://input'), true) : 
            $request->getBody();
    
    if (empty($data['order_id']) || empty($data['note'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Order ID and note are required']);
        exit;
    }
    
    $orderId = (int)$data['order_id'];
    $note = $data['note'];
    
    $orderItems = $this->orderItemRepository->findByOrderId($orderId);
    $hasVendorProducts = false;
    
    foreach ($orderItems as $item) {
        $product = $this->productRepository->findOne($item['product_id']);
        if ($product && $product['vendor_id'] == $vendorId) {
            $hasVendorProducts = true;
            break;
        }
    }
    
    if (!$hasVendorProducts) {
        http_response_code(403);
        echo json_encode(['success' => false, 'message' => 'You do not have permission to add notes to this order']);
        exit;
    }
    
    
    // Return JSON response
    header('Content-Type: application/json');
    echo json_encode([
        'success' => true,
        'message' => 'Note added successfully',
        'note' => [
            'id' => time(), 
            'order_id' => $orderId,
            'vendor_id' => $vendorId,
            'note' => $note,
            'created_at' => date('Y-m-d H:i:s')
        ]
    ]);
    exit;
}


public function statistics(Request $request)
{
    $vendorId = Application::$app->session->get('user')['id'] ?? 0;
    $dateRange = $request->getQuery('range') ?? '30days';
    
    // Determine date range
    $endDate = date('Y-m-d');
    $startDate = $this->getStartDateFromRange($dateRange);
    
    // Get basic statistics
    $stats = $this->getVendorStatistics($vendorId, $startDate, $endDate);
    
    // Get top selling products
    $topProducts = $this->getTopSellingProducts($vendorId, 5);
    
    // Get monthly performance data
    $monthlyData = $this->getMonthlyPerformance($vendorId, 6);
    
    return $this->render('seller/statistics', [
        'title' => 'Store Statistics',
        'stats' => $stats,
        'topProducts' => $topProducts,
        'monthlyData' => $monthlyData,
        'dateRange' => $dateRange
    ]);
}


private function getStartDateFromRange(string $range): string
{
    switch ($range) {
        case '7days':
            return date('Y-m-d', strtotime('-7 days'));
        case '30days':
            return date('Y-m-d', strtotime('-30 days'));
        case '90days':
            return date('Y-m-d', strtotime('-90 days'));
        case 'year':
            return date('Y-m-d', strtotime('-1 year'));
        case 'all':
        default:
            return '2000-01-01'; // Effectively "all time"
    }
}


private function getVendorStatistics(int $vendorId, string $startDate, string $endDate): array
{
    // Get total product count
    $totalProducts = $this->productRepository->countProducts(['vendor_id' => $vendorId]);
    $activeProducts = $this->productRepository->countProducts(['vendor_id' => $vendorId, 'status' => 'active']);
    
    // Get order status counts
    $pendingOrders = count($this->getVendorOrdersByStatus($vendorId, 'pending'));
    $processingOrders = count($this->getVendorOrdersByStatus($vendorId, 'processing'));
    $shippedOrders = count($this->getVendorOrdersByStatus($vendorId, 'shipped'));
    $completedOrders = count($this->getVendorOrdersByStatus($vendorId, 'completed'));
    $cancelledOrders = count($this->getVendorOrdersByStatus($vendorId, 'cancelled'));
    
    $totalOrders = $pendingOrders + $processingOrders + $shippedOrders + $completedOrders + $cancelledOrders;
    
    // Get revenue data
    $sql = "SELECT SUM(oi.price * oi.quantity) as total_revenue
            FROM order_items oi
            JOIN orders o ON oi.order_id = o.id
            JOIN products p ON oi.product_id = p.id
            WHERE p.vendor_id = :vendor_id
            AND o.deleted_at IS NULL
            AND p.deleted_at IS NULL
            AND o.status IN ('completed', 'shipped', 'processing')";
    
    $statement = Application::$app->db->pdo->prepare($sql);
    $statement->bindValue(':vendor_id', $vendorId);
    $statement->execute();
    
    $revenue = (float)$statement->fetchColumn() ?: 0;
    
    $sqlPeriod = $sql . " AND o.created_at BETWEEN :start_date AND :end_date";
    $statementPeriod = Application::$app->db->pdo->prepare($sqlPeriod);
    $statementPeriod->bindValue(':vendor_id', $vendorId);
    $statementPeriod->bindValue(':start_date', $startDate . ' 00:00:00');
    $statementPeriod->bindValue(':end_date', $endDate . ' 23:59:59');
    $statementPeriod->execute();
    
    $periodRevenue = (float)$statementPeriod->fetchColumn() ?: 0;
    
    $sqlPeriodOrders = "SELECT COUNT(DISTINCT o.id)
                        FROM orders o
                        JOIN order_items oi ON o.id = oi.order_id
                        JOIN products p ON oi.product_id = p.id
                        WHERE p.vendor_id = :vendor_id
                        AND o.deleted_at IS NULL
                        AND p.deleted_at IS NULL
                        AND o.created_at BETWEEN :start_date AND :end_date";
    
    $statementPeriodOrders = Application::$app->db->pdo->prepare($sqlPeriodOrders);
    $statementPeriodOrders->bindValue(':vendor_id', $vendorId);
    $statementPeriodOrders->bindValue(':start_date', $startDate . ' 00:00:00');
    $statementPeriodOrders->bindValue(':end_date', $endDate . ' 23:59:59');
    $statementPeriodOrders->execute();
    
    $periodOrders = (int)$statementPeriodOrders->fetchColumn() ?: 0;
    
    return [
        'totalProducts' => $totalProducts,
        'activeProducts' => $activeProducts,
        'totalOrders' => $totalOrders,
        'completedOrders' => $completedOrders,
        'revenue' => $revenue,
        'periodRevenue' => $periodRevenue,
        'periodOrders' => $periodOrders,
        'orderStatus' => [
            'pending' => $pendingOrders,
            'processing' => $processingOrders,
            'shipped' => $shippedOrders,
            'completed' => $completedOrders,
            'cancelled' => $cancelledOrders
        ]
    ];
}


private function getTopSellingProducts(int $vendorId, int $limit = 5): array
{
    $sql = "SELECT p.id, p.name, p.price, p.image_path,
                   SUM(oi.quantity) AS units_sold,
                   SUM(oi.price * oi.quantity) AS revenue
            FROM products p
            JOIN order_items oi ON p.id = oi.product_id
            JOIN orders o ON oi.order_id = o.id
            WHERE p.vendor_id = :vendor_id
            AND p.deleted_at IS NULL
            AND o.deleted_at IS NULL
            AND o.status IN ('completed', 'shipped', 'processing')
            GROUP BY p.id, p.name, p.price, p.image_path
            ORDER BY units_sold DESC
            LIMIT :limit";
    
    $statement = Application::$app->db->pdo->prepare($sql);
    $statement->bindValue(':vendor_id', $vendorId);
    $statement->bindValue(':limit', $limit, \PDO::PARAM_INT);
    $statement->execute();
    
    return $statement->fetchAll(\PDO::FETCH_ASSOC);
}


private function getMonthlyPerformance(int $vendorId, int $months = 6): array
{
    $sql = "SELECT 
                DATE_FORMAT(o.created_at, '%b %Y') AS month,
                COUNT(DISTINCT o.id) AS orders,
                SUM(oi.price * oi.quantity) AS revenue
            FROM orders o
            JOIN order_items oi ON o.id = oi.order_id
            JOIN products p ON oi.product_id = p.id
            WHERE p.vendor_id = :vendor_id
            AND o.deleted_at IS NULL
            AND p.deleted_at IS NULL
            AND o.status IN ('completed', 'shipped', 'processing')
            GROUP BY month
            ORDER BY MIN(o.created_at) DESC
            LIMIT :limit";
    
    if (strpos($_ENV['DB_DSN'] ?? '', 'pgsql') !== false) {
        $sql = "SELECT 
                    TO_CHAR(o.created_at, 'Mon YYYY') AS month,
                    COUNT(DISTINCT o.id) AS orders,
                    SUM(oi.price * oi.quantity) AS revenue
                FROM orders o
                JOIN order_items oi ON o.id = oi.order_id
                JOIN products p ON oi.product_id = p.id
                WHERE p.vendor_id = :vendor_id
                AND o.deleted_at IS NULL
                AND p.deleted_at IS NULL
                AND o.status IN ('completed', 'shipped', 'processing')
                GROUP BY month
                ORDER BY MIN(o.created_at) DESC
                LIMIT :limit";
    }
    
    $statement = Application::$app->db->pdo->prepare($sql);
    $statement->bindValue(':vendor_id', $vendorId);
    $statement->bindValue(':limit', $months, \PDO::PARAM_INT);
    $statement->execute();
    
    return $statement->fetchAll(\PDO::FETCH_ASSOC);
}
}