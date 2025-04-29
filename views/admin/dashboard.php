<div class="space-y-6">
    <!-- Welcome Section -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h1 class="text-2xl font-bold text-admin-primary mb-2">Welcome to Admin Dashboard</h1>
        <p class="text-gray-600">Manage vendor validations and monitor platform statistics.</p>
    </div>
    
    <!-- Summary Stats Cards -->
    <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
        <!-- Pending Vendors Card -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="p-6 bg-gradient-to-r from-admin-accent to-yellow-300">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-semibold text-admin-primary opacity-75">Pending Vendors</p>
                        <h3 class="text-3xl font-bold text-admin-primary mt-1"><?= $stats['vendors']['pending'] ?? 0 ?></h3>
                    </div>
                    <div class="p-2 bg-white bg-opacity-25 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-admin-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                </div>
            </div>
            <div class="p-4 bg-white">
                <a href="/admin/vendors" class="text-admin-primary hover:text-admin-accent font-medium flex items-center text-sm transition-colors">
                    View Pending Vendors
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </a>
            </div>
        </div>
        
        <!-- Total Users Card -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="p-6 bg-gradient-to-r from-blue-500 to-blue-400">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-semibold text-white opacity-75">Total Users</p>
                        <h3 class="text-3xl font-bold text-white mt-1"><?= $stats['users']['total'] ?? 0 ?></h3>
                    </div>
                    <div class="p-2 bg-white bg-opacity-25 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                </div>
            </div>
            <div class="p-4 bg-white">
                <a href="/admin/statistics" class="text-admin-primary hover:text-admin-accent font-medium flex items-center text-sm transition-colors">
                    View User Statistics
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </a>
            </div>
        </div>
        
        <!-- Platform Revenue Card -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="p-6 bg-gradient-to-r from-green-500 to-green-400">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-semibold text-white opacity-75">Total Revenue</p>
                        <h3 class="text-3xl font-bold text-white mt-1"><?= number_format($stats['revenue'] ?? 0, 2) ?> MAD</h3>
                    </div>
                    <div class="p-2 bg-white bg-opacity-25 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>
            <div class="p-4 bg-white">
                <a href="/admin/statistics" class="text-admin-primary hover:text-admin-accent font-medium flex items-center text-sm transition-colors">
                    View Financial Statistics
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </a>
            </div>
        </div>
    </div>
    
    <!-- Main Sections -->
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        <!-- Vendor Validation Section -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-admin-primary">Recent Vendor Applications</h2>
                <a href="/admin/vendors" class="text-sm font-medium text-admin-accent hover:underline">View All</a>
            </div>
            
            <!-- Empty state -->
            <?php if (empty($stats['vendors']['pending'])): ?>
            <div class="py-8 text-center border border-dashed border-gray-300 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
                <p class="text-gray-500 mb-2">No pending vendor applications</p>
                <p class="text-gray-400 text-sm">New applications will appear here</p>
            </div>
            <?php else: ?>
            <div class="flex justify-center">
                <p class="text-xl font-bold text-admin-primary"><?= $stats['vendors']['pending'] ?> pending applications</p>
            </div>
            <?php endif; ?>
            
            <!-- Action buttons -->
            <div class="mt-4 flex flex-col md:flex-row gap-3">
                <a href="/admin/vendors" class="flex items-center justify-center px-4 py-2 bg-admin-primary text-white rounded-md hover:bg-admin-secondary transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Manage Vendor Applications
                </a>
            </div>
        </div>
        
        <!-- Categories and Products Section -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-admin-primary">Categories & Products</h2>
                <button id="openCategoryModal" class="text-sm font-medium text-admin-accent hover:underline flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Add Category
                </button>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="flex justify-between items-center">
                        <h3 class="text-sm font-medium text-gray-500">Total Categories</h3>
                        <span class="text-xl font-bold text-admin-primary"><?= count($stats['categories']) ?></span>
                    </div>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="flex justify-between items-center">
                        <h3 class="text-sm font-medium text-gray-500">Total Products</h3>
                        <span class="text-xl font-bold text-admin-primary"><?= $stats['products']['total'] ?? 0 ?></span>
                    </div>
                </div>
            </div>
            
            <!-- Category list -->
            <div class="border border-gray-200 rounded-lg overflow-hidden">
                <div class="bg-gray-50 px-4 py-2 border-b border-gray-200">
                    <h3 class="text-sm font-medium text-gray-700">Top Categories</h3>
                </div>
                <?php if (empty($stats['categories'])): ?>
                <div class="p-4 text-center">
                    <p class="text-gray-500">No categories found</p>
                </div>
                <?php else: ?>
                <ul class="divide-y divide-gray-200 max-h-52 overflow-y-auto">
                    <?php foreach(array_slice($stats['categories'], 0, 5) as $category): ?>
                    <li class="px-4 py-2 flex justify-between items-center">
                        <span class="text-sm text-gray-700"><?= htmlspecialchars($category['name']) ?></span>
                    </li>
                    <?php endforeach; ?>
                </ul>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <!-- Activity Log Section -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold text-admin-primary">System Overview</h2>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="border border-gray-200 rounded-lg p-4">
                <h4 class="text-sm font-medium text-gray-700 mb-2">Orders</h4>
                <div class="flex items-center">
                    <span class="text-xl font-bold text-admin-primary mr-2"><?= $stats['orders']['total'] ?? 0 ?></span>
                    <span class="text-xs text-gray-500">Total</span>
                </div>
                <div class="flex items-center mt-1">
                    <span class="text-sm font-medium text-gray-700 mr-2"><?= $stats['orders']['completed'] ?? 0 ?></span>
                    <span class="text-xs text-gray-500">Completed</span>
                </div>
            </div>
            
            <div class="border border-gray-200 rounded-lg p-4">
                <h4 class="text-sm font-medium text-gray-700 mb-2">Vendors</h4>
                <div class="flex items-center">
                    <span class="text-xl font-bold text-admin-primary mr-2"><?= $stats['vendors']['total'] ?? 0 ?></span>
                    <span class="text-xs text-gray-500">Total</span>
                </div>
                <div class="flex items-center mt-1">
                    <span class="text-sm font-medium text-gray-700 mr-2"><?= $stats['vendors']['active'] ?? 0 ?></span>
                    <span class="text-xs text-gray-500">Active</span>
                </div>
            </div>
            
            <div class="border border-gray-200 rounded-lg p-4">
                <h4 class="text-sm font-medium text-gray-700 mb-2">Users</h4>
                <div class="flex items-center">
                    <span class="text-xl font-bold text-admin-primary mr-2"><?= $stats['users']['total'] ?? 0 ?></span>
                    <span class="text-xs text-gray-500">Total</span>
                </div>
                <div class="flex items-center mt-1">
                    <span class="text-sm font-medium text-gray-700 mr-2"><?= $stats['users']['active'] ?? 0 ?></span>
                    <span class="text-xs text-gray-500">Active</span>
                </div>
            </div>
            
            <div class="border border-gray-200 rounded-lg p-4">
                <h4 class="text-sm font-medium text-gray-700 mb-2">System Status</h4>
                <div class="flex items-center">
                    <div class="w-3 h-3 bg-green-500 rounded-full mr-2"></div>
                    <span class="text-sm text-gray-700">All systems operational</span>
                </div>
                <p class="text-xs text-gray-500 mt-1">Last updated: <?= date('Y-m-d H:i:s') ?></p>
            </div>
        </div>
    </div>
</div>

<!-- Category Creation Modal -->
<div id="categoryModal" class="fixed inset-0 flex items-center justify-center z-50 hidden">
    <div class="absolute inset-0 bg-black opacity-50" id="modalBackdrop"></div>
    <div class="relative bg-white rounded-lg shadow-lg max-w-md w-full p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Create Category</h3>
        <form action="/admin/categories/create" method="post" id="categoryForm">
            <div class="mb-4">
                <label for="categoryName" class="block text-sm font-medium text-gray-700 mb-1">Category Name(s)</label>
                <input 
                    type="text" 
                    id="categoryName" 
                    name="name" 
                    class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-admin-accent focus:border-admin-accent"
                    placeholder="Enter category name or multiple names separated by commas"
                    required
                >
                <p class="mt-1 text-xs text-gray-500">For multiple categories, separate names with commas (e.g., "Electronics, Books, Clothing")</p>
            </div>
            
            <div class="mb-4">
                <label for="parentCategory" class="block text-sm font-medium text-gray-700 mb-1">Parent Category (Optional)</label>
                <select 
                    id="parentCategory" 
                    name="parent_id" 
                    class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-admin-accent focus:border-admin-accent"
                >
                    <option value="">None (Top Level Category)</option>
                    <?php foreach($stats['categories'] as $category): ?>
                    <option value="<?= $category['id'] ?>"><?= htmlspecialchars($category['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="flex justify-end space-x-3 mt-5">
                <button 
                    type="button" 
                    id="closeCategoryModal"
                    class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-admin-accent"
                >
                    Cancel
                </button>
                <button 
                    type="submit" 
                    class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-admin-primary hover:bg-admin-secondary focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-admin-primary"
                >
                    Create Category
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Category modal functionality
        const openCategoryModal = document.getElementById('openCategoryModal');
        const categoryModal = document.getElementById('categoryModal');
        const closeCategoryModal = document.getElementById('closeCategoryModal');
        const modalBackdrop = document.getElementById('modalBackdrop');
        
        function showModal() {
            categoryModal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }
        
        function hideModal() {
            categoryModal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }
        
        if (openCategoryModal && categoryModal) {
            openCategoryModal.addEventListener('click', showModal);
        }
        
        if (closeCategoryModal) {
            closeCategoryModal.addEventListener('click', hideModal);
        }
        
        if (modalBackdrop) {
            modalBackdrop.addEventListener('click', hideModal);
        }
        
        // Form submission handling
        const categoryForm = document.getElementById('categoryForm');
        if (categoryForm) {
            categoryForm.addEventListener('submit', function(e) {
                const nameInput = document.getElementById('categoryName');
                if (!nameInput || !nameInput.value.trim()) {
                    e.preventDefault();
                    alert('Please enter at least one category name');
                }
            });
        }
    });
</script>