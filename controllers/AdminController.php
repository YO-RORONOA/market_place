<?php

namespace App\controllers;

use App\core\Application;
use App\core\Controller;
use App\core\middlewares\AdminMiddleware;
use App\core\Request;
use App\models\Role;
use App\repositories\VendorRepository;
use App\repositories\UserRepository;
use App\repositories\OrderRepository;
use App\repositories\ProductRepository;
use App\repositories\CategoryRepository;
use App\services\EmailService;

class AdminController extends Controller
{
    private VendorRepository $vendorRepository;
    private UserRepository $userRepository;
    private OrderRepository $orderRepository;
    private ProductRepository $productRepository;
    private CategoryRepository $categoryRepository;
    private EmailService $emailService;
    
    public function __construct()
    {
        parent::__construct();
        $this->setLayout('admin');
        
        // Initialize repositories
        $this->vendorRepository = new VendorRepository();
        $this->userRepository = new UserRepository();
        $this->orderRepository = new OrderRepository();
        $this->productRepository = new ProductRepository();
        $this->categoryRepository = new CategoryRepository();
        $this->emailService = new EmailService();
        
        // Use admin middleware to protect all controller actions
        // $this->registerMiddleware(new AdminMiddleware());
    }
    
    /**
     * Pending vendors listing and validation page
     * 
     * @param Request $request
     * @return string
     */
    public function vendors(Request $request)
    {
        $status = $request->getQuery('status') ?? 'pending';
        
        $vendors = $this->vendorRepository->findAll(['status' => $status]);
        
        
        foreach ($vendors as &$vendor) {
            $userData = $this->userRepository->findOne($vendor['user_id']);
            if ($userData) {
                $vendor['user'] = $userData;
                $vendor['productCount'] = $this->productRepository->countProducts(['vendor_id' => $vendor['user_id']]);
            } else {
                error_log("User data not found for vendor ID: " . $vendor['id'] . ", user ID: " . $vendor['user_id']);
            }
        }
        
        $statusOptions = [
            'pending' => 'Pending Approval',
            'active' => 'Active Vendors',
            'rejected' => 'Rejected Vendors',
            'suspended' => 'Suspended Vendors'
        ];
        error_log("Current status filter: $status, Options: " . json_encode($statusOptions));
        
        return $this->render('admin/vendors', [
            'title' => 'Vendor Validation',
            'vendors' => $vendors,
            'status' => $status,
            'statusOptions' => $statusOptions
        ]);
    }
    
    /**
     * View vendor details
     * 
     * @param Request $request
     * @return string
     */
    public function viewVendor(Request $request)
    {
        $vendorId = $request->getQuery('id');
        
        if (!$vendorId) {
            Application::$app->session->setFlash('error', 'Vendor ID is required');
            Application::$app->response->redirect('/admin/vendors');
            return '';
        }
        
        $vendor = $this->vendorRepository->findOne($vendorId);
        
        if (!$vendor) {
            Application::$app->session->setFlash('error', 'Vendor not found');
            Application::$app->response->redirect('/admin/vendors');
            return '';
        }
        
        $userData = $this->userRepository->findOne($vendor['user_id']);
        $vendor['user'] = $userData;
        
        $vendor['productCount'] = $this->productRepository->countProducts(['vendor_id' => $vendor['user_id']]);
        
        $products = $this->productRepository->findByVendor($vendor['user_id'], 5);
        
        return $this->render('admin/vendor-detail', [
            'title' => 'Vendor Details',
            'vendor' => $vendor,
            'products' => $products
        ]);
    }
    
    /**
     * Approve a vendor
     * 
     * @param Request $request
     * @return void
     */
    public function approveVendor(Request $request)
    {
        if (!$request->isPost()) {
            Application::$app->response->redirect('/admin/vendors');
            return;
        }
        
        $vendorId = $request->getBody()['vendor_id'] ?? null;
        
        if (!$vendorId) {
            Application::$app->session->setFlash('error', 'Invalid vendor ID');
            Application::$app->response->redirect('/admin/vendors');
            return;
        }
        
        $vendor = $this->vendorRepository->findOne($vendorId);
        
        if (!$vendor) {
            Application::$app->session->setFlash('error', 'Vendor not found');
            Application::$app->response->redirect('/admin/vendors');
            return;
        }
        
        $result = $this->vendorRepository->update($vendorId, ['status' => 'active']);
        
        if ($result) {
            $user = $this->userRepository->findOne($vendor['user_id']);
            
            error_log("Vendor {$vendor['store_name']} (ID: {$vendorId}) approved by admin");
            
            Application::$app->session->setFlash('success', 'Vendor approved successfully');
        } else {
            Application::$app->session->setFlash('error', 'Failed to approve vendor');
        }
        
        Application::$app->response->redirect('/admin/vendors');
    }
    
    /**
     * Reject a vendor
     * 
     * @param Request $request
     * @return void
     */
    public function rejectVendor(Request $request)
    {
        if (!$request->isPost()) {
            Application::$app->response->redirect('/admin/vendors');
            return;
        }
        
        $vendorId = $request->getBody()['vendor_id'] ?? null;
        $reason = $request->getBody()['reason'] ?? 'Your vendor application does not meet our requirements at this time.';
        
        if (!$vendorId) {
            Application::$app->session->setFlash('error', 'Invalid vendor ID');
            Application::$app->response->redirect('/admin/vendors');
            return;
        }
        
        $vendor = $this->vendorRepository->findOne($vendorId);
        
        if (!$vendor) {
            Application::$app->session->setFlash('error', 'Vendor not found');
            Application::$app->response->redirect('/admin/vendors');
            return;
        }
        
        $result = $this->vendorRepository->update($vendorId, ['status' => 'rejected']);
        
        if ($result) {
            $user = $this->userRepository->findOne($vendor['user_id']);
            
            error_log("Vendor {$vendor['store_name']} (ID: {$vendorId}) rejected by admin. Reason: {$reason}");
            
            Application::$app->session->setFlash('success', 'Vendor rejected successfully');
        } else {
            Application::$app->session->setFlash('error', 'Failed to reject vendor');
        }
        
        Application::$app->response->redirect('/admin/vendors');
    }
    
    /**
     * Suspend a vendor
     * 
     * @param Request $request
     * @return void
     */
    public function suspendVendor(Request $request)
    {
        if (!$request->isPost()) {
            Application::$app->response->redirect('/admin/vendors');
            return;
        }
        
        $vendorId = $request->getBody()['vendor_id'] ?? null;
        $reason = $request->getBody()['reason'] ?? 'Your vendor account has been suspended due to policy violations.';
        
        if (!$vendorId) {
            Application::$app->session->setFlash('error', 'Invalid vendor ID');
            Application::$app->response->redirect('/admin/vendors');
            return;
        }
        
        $vendor = $this->vendorRepository->findOne($vendorId);
        
        if (!$vendor) {
            Application::$app->session->setFlash('error', 'Vendor not found');
            Application::$app->response->redirect('/admin/vendors');
            return;
        }
        
        $result = $this->vendorRepository->update($vendorId, ['status' => 'suspended']);
        
        if ($result) {
            $user = $this->userRepository->findOne($vendor['user_id']);
            
            error_log("Vendor {$vendor['store_name']} (ID: {$vendorId}) suspended by admin. Reason: {$reason}");
            
            Application::$app->session->setFlash('success', 'Vendor suspended successfully');
        } else {
            Application::$app->session->setFlash('error', 'Failed to suspend vendor');
        }
        
        Application::$app->response->redirect('/admin/vendors');
    }
    
    /**
     * Statistics overview page
     * 
     * @param Request $request
     * @return string
     */
    public function statistics(Request $request)
    {
        $dateRange = $request->getQuery('range') ?? '30days';
        
        $endDate = date('Y-m-d');
        $startDate = $this->getStartDateFromRange($dateRange);
        
        $stats = $this->getStatistics($startDate, $endDate);
        
        return $this->render('admin/statistics', [
            'title' => 'Platform Statistics',
            'stats' => $stats,
            'dateRange' => $dateRange,
            'rangeOptions' => [
                '7days' => 'Last 7 Days',
                '30days' => 'Last 30 Days',
                '90days' => 'Last 90 Days',
                'year' => 'Last Year',
                'all' => 'All Time'
            ]
        ]);
    }
    
    /**
     * Calculate the start date based on the selected range
     * 
     * @param string $range Date range identifier
     * @return string Start date in Y-m-d format
     */
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
    
    /**
     * Get all statistics data for the dashboard
     * 
     * @param string $startDate Start date in Y-m-d format
     * @param string $endDate End date in Y-m-d format
     * @return array Statistics data
     */
    private function getStatistics(string $startDate, string $endDate): array
{
    $totalUsers = $this->userRepository->count();
    $activeUsers = $this->userRepository->count(['status' => 'active']);
    
    $newUsers = $this->userRepository->countInDateRange($startDate, $endDate);
    
    $totalVendors = $this->vendorRepository->count();
    $pendingVendors = $this->vendorRepository->count(['status' => 'pending']);
    $activeVendors = $this->vendorRepository->count(['status' => 'active']);
    
    $totalOrders = $this->orderRepository->count();
    $completedOrders = $this->orderRepository->countByStatus('completed');
    
    $totalRevenue = $this->orderRepository->totalRevenue();
    $periodRevenue = $this->orderRepository->getRevenueInDateRange($startDate, $endDate);
    
    $totalProducts = $this->productRepository->countProducts();
    
    $categories = $this->categoryRepository->getMainCategories();
    $categoryStats = [];
    
    foreach ($categories as $category) {
        $productCount = $this->productRepository->countProducts(['category_id' => $category['id']]);
        $categoryStats[] = [
            'name' => $category['name'],
            'count' => $productCount
        ];
    }
    
    $chartData = $this->generateChartData($startDate, $endDate);
    
    return [
        'userStats' => [
            'total' => $totalUsers,
            'new' => $newUsers,
            'active' => $activeUsers
        ],
        'vendorStats' => [
            'total' => $totalVendors,
            'pending' => $pendingVendors,
            'active' => $activeVendors
        ],
        'orderStats' => [
            'total' => $totalOrders,
            'completed' => $completedOrders,
            'revenue' => $totalRevenue,
            'periodRevenue' => $periodRevenue
        ],
        'productStats' => [
            'total' => $totalProducts,
            'categories' => $categoryStats
        ],
        'chartData' => $chartData
    ];
}

    


private function generateChartData(string $startDate, string $endDate): array
{
    $months = [];
    $userRegistrationData = [];
    $revenueData = [];
    
    $interval = $this->determineChartInterval($startDate, $endDate);
    
    if ($interval === 'daily') {
        $startDateTime = new \DateTime($startDate);
        $endDateTime = new \DateTime($endDate);
        $periodDays = $endDateTime->diff($startDateTime)->days + 1;
        
        $periodDays = min($periodDays, 30);
        $currentDate = new \DateTime($endDate);
        $currentDate->modify('-' . ($periodDays - 1) . ' days');
        
        for ($i = 0; $i < $periodDays; $i++) {
            $dayDate = $currentDate->format('Y-m-d');
            $months[] = $currentDate->format('d M');
            
            $userRegistrationData[] = $this->userRepository->countInDateRange($dayDate, $dayDate);
            
            $revenueData[] = $this->orderRepository->getRevenueInDateRange($dayDate, $dayDate);
            
            $currentDate->modify('+1 day');
        }
    } else {
        $startDateTime = new \DateTime($startDate);
        $endDateTime = new \DateTime($endDate);
        $startDateTime->modify('first day of this month');
        $endDateTime->modify('first day of next month');
        
        $period = new \DatePeriod(
            $startDateTime,
            new \DateInterval('P1M'),
            $endDateTime
        );
        
        $datePoints = iterator_count($period);
        if ($datePoints > 6) {
            $endDateTime = new \DateTime($endDate);
            $endDateTime->modify('first day of next month');
            $startDateTime = clone $endDateTime;
            $startDateTime->modify('-5 months');
            
            $period = new \DatePeriod(
                $startDateTime,
                new \DateInterval('P1M'),
                $endDateTime
            );
        }
        
        foreach ($period as $date) {
            $monthStart = $date->format('Y-m-d');
            $monthEnd = $date->format('Y-m-t');
            $months[] = $date->format('M Y');
            
            $userRegistrationData[] = $this->userRepository->countInDateRange($monthStart, $monthEnd);
            
            $revenueData[] = $this->orderRepository->getRevenueInDateRange($monthStart, $monthEnd);
        }
    }
    
    return [
        'months' => $months,
        'userRegistrations' => $userRegistrationData,
        'revenue' => $revenueData
    ];
}
private function determineChartInterval(string $startDate, string $endDate): string
{
    $start = new \DateTime($startDate);
    $end = new \DateTime($endDate);
    $diff = $end->diff($start);
    
    // For periods of 31 days or less, use daily interval
    if ($diff->days <= 31) {
        return 'daily';
    }
    
    // Otherwise use monthly
    return 'monthly';
}


    public function exportUsers()
    {
        $users = $this->userRepository->findAll();
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="users_export_' . date('Y-m-d') . '.csv"');
        
        $output = fopen('php://output', 'w');
        
        fputcsv($output, ['ID', 'First Name', 'Last Name', 'Email', 'Status', 'Created At']);
        
        foreach ($users as $user) {
            fputcsv($output, [
                $user['id'],
                $user['firstname'],
                $user['lastname'],
                $user['email'],
                $user['status'],
                $user['created_at']
            ]);
        }
        
        fclose($output);
        exit;
    }
    
    /**
     * Export vendors as CSV
     * 
     * @return void
     */
    public function exportVendors()
    {
        $vendors = $this->vendorRepository->findAll();
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="vendors_export_' . date('Y-m-d') . '.csv"');
        
        $output = fopen('php://output', 'w');
        
        fputcsv($output, ['ID', 'User ID', 'Store Name', 'Status', 'Created At']);
        
        foreach ($vendors as $vendor) {
            fputcsv($output, [
                $vendor['id'],
                $vendor['user_id'],
                $vendor['store_name'],
                $vendor['status'],
                $vendor['created_at']
            ]);
        }
        
        fclose($output);
        exit;
    }
    
    /**
     * Export orders as CSV
     * 
     * @return void
     */
    public function exportOrders()
    {
        $orders = $this->orderRepository->findAll();
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="orders_export_' . date('Y-m-d') . '.csv"');
        
        $output = fopen('php://output', 'w');
        
        fputcsv($output, ['ID', 'User ID', 'Total Amount', 'Status', 'Payment Method', 'Created At']);
        
        foreach ($orders as $order) {
            fputcsv($output, [
                $order['id'],
                $order['user_id'],
                $order['total_amount'],
                $order['status'],
                $order['payment_method'],
                $order['created_at']
            ]);
        }
        
        fclose($output);
        exit;
    }
    
    /**
     * Export products as CSV
     * 
     * @return void
     */
    public function exportProducts()
    {
        $products = $this->productRepository->findAll();
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="products_export_' . date('Y-m-d') . '.csv"');
        
        $output = fopen('php://output', 'w');
        
        fputcsv($output, ['ID', 'Name', 'Price', 'Stock', 'Category', 'Vendor ID', 'Status', 'Created At']);
        
        foreach ($products as $product) {
            $category = $this->categoryRepository->findOne($product['category_id']);
            $categoryName = $category ? $category['name'] : 'Unknown';
            
            fputcsv($output, [
                $product['id'],
                $product['name'],
                $product['price'],
                $product['stock_quantity'],
                $categoryName,
                $product['vendor_id'],
                $product['status'],
                $product['created_at']
            ]);
        }
        
        fclose($output);
        exit;
    }


    public function dashboard()
{
    $totalUsers = $this->userRepository->count();
    $activeUsers = $this->userRepository->count(['status' => 'active']);
    
    $totalVendors = $this->vendorRepository->count();
    $pendingVendors = $this->vendorRepository->count(['status' => 'pending']);
    $activeVendors = $this->vendorRepository->count(['status' => 'active']);
    
    $totalProducts = $this->productRepository->countProducts();
    
    $totalOrders = $this->orderRepository->count();
    $completedOrders = $this->orderRepository->countByStatus('completed');
    
    $totalRevenue = $this->orderRepository->totalRevenue();
    
    $categories = $this->categoryRepository->getMainCategories();
    
    return $this->render('admin/dashboard', [
        'title' => 'Admin Dashboard',
        'stats' => [
            'users' => [
                'total' => $totalUsers,
                'active' => $activeUsers
            ],
            'vendors' => [
                'total' => $totalVendors,
                'pending' => $pendingVendors,
                'active' => $activeVendors
            ],
            'products' => [
                'total' => $totalProducts
            ],
            'orders' => [
                'total' => $totalOrders,
                'completed' => $completedOrders
            ],
            'revenue' => $totalRevenue,
            'categories' => $categories
        ]
    ]);
}

public function createCategory(Request $request)
{
    if (!$request->isPost()) {
        Application::$app->response->redirect('/admin/dashboard');
        return;
    }
    
    $data = $request->getBody();
    $name = $data['name'] ?? '';
    $parentId = !empty($data['parent_id']) ? (int)$data['parent_id'] : null;
    
    if (empty($name)) {
        Application::$app->session->setFlash('error', 'Category name is required');
        Application::$app->response->redirect('/admin/dashboard');
        return;
    }
    
    if ($parentId) {
        $parent = $this->categoryRepository->findOne($parentId);
        if (!$parent) {
            Application::$app->session->setFlash('error', 'Parent category not found');
            Application::$app->response->redirect('/admin/dashboard');
            return;
        }
    }
    
    $categoryNames = array_map('trim', explode(',', $name));
    $createdCount = 0;
    $errorCount = 0;
    $duplicateCount = 0;
    $duplicateNames = [];
    
    foreach ($categoryNames as $categoryName) {
        if (empty($categoryName)) continue;
        
        try {
            if ($this->categoryRepository->categoryExists($categoryName, $parentId)) {
                $duplicateCount++;
                $duplicateNames[] = $categoryName;
                continue;
            }
            
            $categoryData = [
                'name' => $categoryName,
                'parent_id' => $parentId
            ];
            
            $result = $this->categoryRepository->create($categoryData);
            
            if ($result) {
                $createdCount++;
            } else {
                $errorCount++;
            }
        } catch (\Exception $e) {
            error_log("Error creating category: " . $e->getMessage());
            $errorCount++;
        }
    }
    
    if ($createdCount > 0) {
        if ($createdCount === 1) {
            Application::$app->session->setFlash('success', 'Category created successfully');
        } else {
            Application::$app->session->setFlash('success', $createdCount . ' categories created successfully');
        }
    }
    
    if ($duplicateCount > 0) {
        $duplicateMessage = $duplicateCount === 1 
            ? 'Category "' . implode('", "', $duplicateNames) . '" already exists'
            : $duplicateCount . ' categories already exist: "' . implode('", "', $duplicateNames) . '"';
        
        Application::$app->session->setFlash('info', $duplicateMessage);
    }
    
    if ($errorCount > 0) {
        if ($createdCount === 0 && $duplicateCount === 0) {
            Application::$app->session->setFlash('error', 'Failed to create categories');
        } else {
            Application::$app->session->setFlash('error', $errorCount . ' categories failed to create');
        }
    }
    
    Application::$app->response->redirect('/admin/dashboard');
}
}