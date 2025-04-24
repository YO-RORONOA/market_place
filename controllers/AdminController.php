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
        // Get status filter from request
        $status = $request->getQuery('status') ?? 'pending';
        
        // Get vendors with the requested status
        $vendors = $this->vendorRepository->findAll(['status' => $status]);
        
        // For debugging, let's log what we found
        error_log("Found " . count($vendors) . " vendors with status '$status'");
        
        // For each vendor, load the associated user data
        foreach ($vendors as &$vendor) {
            $userData = $this->userRepository->findOne($vendor['user_id']);
            if ($userData) {
                $vendor['user'] = $userData;
                // Calculate the number of products for this vendor
                $vendor['productCount'] = $this->productRepository->countProducts(['vendor_id' => $vendor['user_id']]);
            } else {
                // Log if user data wasn't found
                error_log("User data not found for vendor ID: " . $vendor['id'] . ", user ID: " . $vendor['user_id']);
            }
        }
        
        // Log all the status options and the current selection for debugging
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
        
        // Load user data
        $userData = $this->userRepository->findOne($vendor['user_id']);
        $vendor['user'] = $userData;
        
        // Load vendor statistics
        $vendor['productCount'] = $this->productRepository->countProducts(['vendor_id' => $vendor['user_id']]);
        
        // Get vendor's products
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
        
        // Get vendor data
        $vendor = $this->vendorRepository->findOne($vendorId);
        
        if (!$vendor) {
            Application::$app->session->setFlash('error', 'Vendor not found');
            Application::$app->response->redirect('/admin/vendors');
            return;
        }
        
        // Update vendor status to active
        $result = $this->vendorRepository->update($vendorId, ['status' => 'active']);
        
        if ($result) {
            // Get user data to send notification email
            $user = $this->userRepository->findOne($vendor['user_id']);
            
            // Send approval notification email
            // For now, we'll just log the action
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
        
        // Get vendor data
        $vendor = $this->vendorRepository->findOne($vendorId);
        
        if (!$vendor) {
            Application::$app->session->setFlash('error', 'Vendor not found');
            Application::$app->response->redirect('/admin/vendors');
            return;
        }
        
        // Update vendor status to rejected
        $result = $this->vendorRepository->update($vendorId, ['status' => 'rejected']);
        
        if ($result) {
            // Get user data to send notification email
            $user = $this->userRepository->findOne($vendor['user_id']);
            
            // Send rejection notification email
            // For now, we'll just log the action
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
        
        // Get vendor data
        $vendor = $this->vendorRepository->findOne($vendorId);
        
        if (!$vendor) {
            Application::$app->session->setFlash('error', 'Vendor not found');
            Application::$app->response->redirect('/admin/vendors');
            return;
        }
        
        // Update vendor status to suspended
        $result = $this->vendorRepository->update($vendorId, ['status' => 'suspended']);
        
        if ($result) {
            // Get user data to send notification email
            $user = $this->userRepository->findOne($vendor['user_id']);
            
            // Send suspension notification email
            // For now, we'll just log the action
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
        // Get date range filter from request (default to last 30 days)
        $dateRange = $request->getQuery('range') ?? '30days';
        
        // Calculate start date based on range
        $endDate = date('Y-m-d');
        $startDate = $this->getStartDateFromRange($dateRange);
        
        // Fetch statistics data
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
        // User statistics
        $totalUsers = $this->userRepository->count();
        $activeUsers = $this->userRepository->count(['status' => 'active']);
        
        // Since your model doesn't have a direct way to count by date range,
        // we'll create a placeholder for now
        $newUsers = 0;
        
        // Vendor statistics
        $totalVendors = $this->vendorRepository->count();
        $pendingVendors = $this->vendorRepository->count(['status' => 'pending']);
        $activeVendors = $this->vendorRepository->count(['status' => 'active']);
        
        // Order statistics
        $totalOrders = $this->orderRepository->count();
        $completedOrders = $this->orderRepository->countByStatus('completed');
        
        // Calculate total revenue (simulated data for now)
        $totalRevenue = 0; // In a real implementation, this would come from the database
        
        // Get product statistics
        $totalProducts = $this->productRepository->countProducts();
        
        // Get category distribution
        $categories = $this->categoryRepository->getMainCategories();
        $categoryStats = [];
        
        foreach ($categories as $category) {
            $productCount = $this->productRepository->countProducts(['category_id' => $category['id']]);
            $categoryStats[] = [
                'name' => $category['name'],
                'count' => $productCount
            ];
        }
        
        // Simulate monthly data for charts
        $months = [];
        $userRegistrationData = [];
        $revenueData = [];
        
        // Generate data for the last 6 months
        for ($i = 5; $i >= 0; $i--) {
            $month = date('M Y', strtotime("-$i months"));
            $months[] = $month;
            
            // In a real implementation, these would be actual counts from database
            $userRegistrationData[] = rand(0, 10); // Random data for demonstration
            $revenueData[] = rand(0, 5000); // Random data for demonstration
        }
        
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
                'revenue' => $totalRevenue
            ],
            'productStats' => [
                'total' => $totalProducts,
                'categories' => $categoryStats
            ],
            'chartData' => [
                'months' => $months,
                'userRegistrations' => $userRegistrationData,
                'revenue' => $revenueData
            ]
        ];
    }


    public function exportUsers()
    {
        // Get all users
        $users = $this->userRepository->findAll();
        
        // Set headers for CSV download
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="users_export_' . date('Y-m-d') . '.csv"');
        
        // Create a file handle for php://output
        $output = fopen('php://output', 'w');
        
        // Add CSV headers
        fputcsv($output, ['ID', 'First Name', 'Last Name', 'Email', 'Status', 'Created At']);
        
        // Add user data
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
        // Get all vendors
        $vendors = $this->vendorRepository->findAll();
        
        // Set headers for CSV download
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="vendors_export_' . date('Y-m-d') . '.csv"');
        
        // Create a file handle for php://output
        $output = fopen('php://output', 'w');
        
        // Add CSV headers
        fputcsv($output, ['ID', 'User ID', 'Store Name', 'Status', 'Created At']);
        
        // Add vendor data
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
        // Get all orders
        $orders = $this->orderRepository->findAll();
        
        // Set headers for CSV download
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="orders_export_' . date('Y-m-d') . '.csv"');
        
        // Create a file handle for php://output
        $output = fopen('php://output', 'w');
        
        // Add CSV headers
        fputcsv($output, ['ID', 'User ID', 'Total Amount', 'Status', 'Payment Method', 'Created At']);
        
        // Add order data
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
        // Get all products
        $products = $this->productRepository->findAll();
        
        // Set headers for CSV download
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="products_export_' . date('Y-m-d') . '.csv"');
        
        // Create a file handle for php://output
        $output = fopen('php://output', 'w');
        
        // Add CSV headers
        fputcsv($output, ['ID', 'Name', 'Price', 'Stock', 'Category', 'Vendor ID', 'Status', 'Created At']);
        
        // Add product data
        foreach ($products as $product) {
            // Get category name
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
}