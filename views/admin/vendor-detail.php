<div class="space-y-6">
    <!-- Back button and page header -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div class="flex items-center">
                <a href="/admin/vendors" class="mr-4 p-2 bg-gray-100 rounded-full hover:bg-gray-200 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-admin-primary">Vendor Details</h1>
                    <p class="text-gray-600 mt-1">Review vendor information</p>
                </div>
            </div>
            
            <div class="mt-4 md:mt-0 flex flex-wrap gap-2">
                <?php if ($vendor['status'] === 'pending'): ?>
                <form action="/admin/vendors/approve" method="post" class="inline">
                    <input type="hidden" name="vendor_id" value="<?= $vendor['id'] ?>">
                    <button type="submit" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-md transition-colors flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Approve Vendor
                    </button>
                </form>
                <button type="button" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-md transition-colors flex items-center" onclick="showRejectModal()">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    Reject Vendor
                </button>
                <?php elseif ($vendor['status'] === 'active'): ?>
                <button type="button" class="px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white rounded-md transition-colors flex items-center" onclick="showSuspendModal()">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    Suspend Vendor
                </button>
                <?php elseif ($vendor['status'] === 'suspended' || $vendor['status'] === 'rejected'): ?>
                <form action="/admin/vendors/approve" method="post" class="inline">
                    <input type="hidden" name="vendor_id" value="<?= $vendor['id'] ?>">
                    <button type="submit" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-md transition-colors flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Reactivate Vendor
                    </button>
                </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <!-- Vendor Information -->
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <!-- Vendor Profile Card -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="p-6 bg-gradient-to-r from-admin-primary to-admin-secondary">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="text-lg font-semibold text-white">Vendor Profile</h3>
                        <p class="text-white opacity-80 text-sm mt-1"><?= htmlspecialchars($vendor['user']['email']) ?></p>
                    </div>
                    <div class="bg-white p-2 rounded-lg">
                        <div class="h-10 w-10 rounded-full bg-admin-accent text-admin-primary flex items-center justify-center font-bold text-lg">
                            <?= strtoupper(substr($vendor['store_name'], 0, 1)) ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <div>
                        <p class="text-sm text-gray-500">Store Name</p>
                        <p class="text-lg font-semibold text-gray-700"><?= htmlspecialchars($vendor['store_name']) ?></p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Status</p>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                            <?php if ($vendor['status'] === 'active'): ?>
                                bg-green-100 text-green-800
                            <?php elseif ($vendor['status'] === 'pending'): ?>
                                bg-yellow-100 text-yellow-800
                            <?php elseif ($vendor['status'] === 'rejected'): ?>
                                bg-red-100 text-red-800
                            <?php elseif ($vendor['status'] === 'suspended'): ?>
                                bg-gray-100 text-gray-800
                            <?php endif; ?>
                        ">
                            <?= ucfirst(htmlspecialchars($vendor['status'])) ?>
                        </span>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">User Name</p>
                        <p class="font-semibold text-gray-700"><?= htmlspecialchars($vendor['user']['firstname'] . ' ' . $vendor['user']['lastname']) ?></p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Email Address</p>
                        <p class="font-semibold text-gray-700"><?= htmlspecialchars($vendor['user']['email']) ?></p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Registered On</p>
                        <p class="font-semibold text-gray-700"><?= date('F j, Y', strtotime($vendor['created_at'])) ?></p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Vendor Details Card -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden lg:col-span-2">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-admin-primary">Store Information</h3>
            </div>
            <div class="p-6">
                <div class="space-y-6">
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 mb-2">Store Description</h4>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-gray-700"><?= nl2br(htmlspecialchars($vendor['description'])) ?></p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="text-sm font-medium text-gray-500 mb-1">Products</h4>
                            <p class="text-xl font-bold text-admin-primary"><?= $vendor['productCount'] ?? 0 ?></p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="text-sm font-medium text-gray-500 mb-1">Sales</h4>
                            <p class="text-xl font-bold text-admin-primary">0</p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="text-sm font-medium text-gray-500 mb-1">Revenue</h4>
                            <p class="text-xl font-bold text-admin-primary">0.00 MAD</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Vendor Products -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-admin-primary">Products</h3>
        </div>
        <div class="p-6">
            <?php if (empty($products)): ?>
            <div class="py-8 text-center border border-dashed border-gray-300 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                </svg>
                <p class="text-gray-500 mb-2">No products found</p>
                <p class="text-gray-400 text-sm">This vendor hasn't added any products yet</p>
            </div>
            <?php else: ?>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Product
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Category
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Price
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($products as $product): ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 bg-gray-200 rounded-md overflow-hidden">
                                        <?php if ($product['image_path']): ?>
                                        <img src="<?= htmlspecialchars($product['image_path']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="h-10 w-10 object-cover">
                                        <?php else: ?>
                                        <div class="h-10 w-10 flex items-center justify-center text-gray-500">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900"><?= htmlspecialchars($product['name']) ?></div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-500">
                                    <?php 
                                    // In a real implementation, you would fetch the category name
                                    echo htmlspecialchars($product['category_id']);
                                    ?>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900"><?= number_format($product['price'], 2) ?> MAD</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    <?php if ($product['status'] === 'active'): ?>
                                        bg-green-100 text-green-800
                                    <?php else: ?>
                                        bg-gray-100 text-gray-800
                                    <?php endif; ?>
                                ">
                                    <?= ucfirst(htmlspecialchars($product['status'])) ?>
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                
                
                <?php if ($vendor['productCount'] > count($products)): ?>
                <div class="mt-4 text-center">
                    <a href="/admin/products?vendor_id=<?= $vendor['user_id'] ?>" class="text-admin-accent hover:underline">
                        View all <?= $vendor['productCount'] ?> products
                    </a>
                </div>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="fixed inset-0 flex items-center justify-center z-50 hidden">
    <div class="absolute inset-0 bg-black opacity-50" onclick="hideRejectModal()"></div>
    <div class="relative bg-white rounded-lg shadow-lg max-w-md w-full p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Reject Vendor</h3>
        <form action="/admin/vendors/reject" method="post">
            <input type="hidden" name="vendor_id" value="<?= $vendor['id'] ?>">
            
            <div class="mb-4">
                <label for="reason" class="block text-sm font-medium text-gray-700 mb-1">Rejection Reason</label>
                <textarea 
                    id="reason" 
                    name="reason" 
                    rows="3" 
                    class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-admin-accent focus:border-admin-accent"
                    placeholder="Please provide a reason for rejection (will be sent to the vendor)"
                ></textarea>
            </div>
            
            <div class="flex justify-end space-x-3 mt-5">
                <button 
                    type="button" 
                    class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-admin-accent"
                    onclick="hideRejectModal()"
                >
                    Cancel
                </button>
                <button 
                    type="submit" 
                    class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                >
                    Reject Vendor
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Suspend Modal -->
<div id="suspendModal" class="fixed inset-0 flex items-center justify-center z-50 hidden">
    <div class="absolute inset-0 bg-black opacity-50" onclick="hideSuspendModal()"></div>
    <div class="relative bg-white rounded-lg shadow-lg max-w-md w-full p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Suspend Vendor</h3>
        <form action="/admin/vendors/suspend" method="post">
            <input type="hidden" name="vendor_id" value="<?= $vendor['id'] ?>">
            
            <div class="mb-4">
                <label for="suspend-reason" class="block text-sm font-medium text-gray-700 mb-1">Suspension Reason</label>
                <textarea 
                    id="suspend-reason" 
                    name="reason" 
                    rows="3" 
                    class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-admin-accent focus:border-admin-accent"
                    placeholder="Please provide a reason for suspension (will be sent to the vendor)"
                ></textarea>
            </div>
            
            <div class="flex justify-end space-x-3 mt-5">
                <button 
                    type="button" 
                    class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-admin-accent"
                    onclick="hideSuspendModal()"
                >
                    Cancel
                </button>
                <button 
                    type="submit" 
                    class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500"
                >
                    Suspend Vendor
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Modal functions
    function showRejectModal() {
        document.getElementById('rejectModal').classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }
    
    function hideRejectModal() {
        document.getElementById('rejectModal').classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }
    
    function showSuspendModal() {
        document.getElementById('suspendModal').classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }
    
    function hideSuspendModal() {
        document.getElementById('suspendModal').classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }
</script>