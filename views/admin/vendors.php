<div class="space-y-6">
    <!-- Page Header -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-admin-primary">Vendor Validation</h1>
                <p class="text-gray-600 mt-1">Manage vendor applications and approvals</p>
            </div>
            
            <!-- Filter Dropdown (for future implementation) -->
            <div class="mt-4 md:mt-0">
                <select class="bg-white border border-gray-300 rounded-md shadow-sm py-2 px-3 text-gray-700 focus:outline-none focus:ring-admin-accent focus:border-admin-accent">
                    <option value="pending">Pending Approval</option>
                    <option value="approved">Approved</option>
                    <option value="rejected">Rejected</option>
                    <option value="all">All Vendors</option>
                </select>
            </div>
        </div>
    </div>
    
    <!-- Vendors List -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-admin-primary">Pending Vendor Applications</h2>
        </div>
        
        <!-- Empty state for pending vendors -->
        <?php if (empty($vendors)): ?>
<div class="p-6 text-center border-b border-gray-200">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
    </svg>
    <p class="text-gray-500 mb-2">No pending vendor applications</p>
    <p class="text-gray-400 text-sm">New applications will appear here for review</p>
</div>
<?php else: ?>
<div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Vendor
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Store Name
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Applied On
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Status
                </th>
                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Actions
                </th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            <?php foreach ($vendors as $vendor): ?>
            <tr>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 font-bold">
                            <?= strtoupper(substr($vendor['user']['firstname'], 0, 1)) ?>
                        </div>
                        <div class="ml-4">
                            <div class="text-sm font-medium text-gray-900"><?= htmlspecialchars($vendor['user']['firstname'] . ' ' . $vendor['user']['lastname']) ?></div>
                            <div class="text-sm text-gray-500"><?= htmlspecialchars($vendor['user']['email']) ?></div>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-900"><?= htmlspecialchars($vendor['store_name']) ?></div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-500"><?= date('Y-m-d H:i:s', strtotime($vendor['created_at'])) ?></div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                        <?php if ($vendor['status'] === 'pending'): ?>
                            bg-yellow-100 text-yellow-800
                        <?php elseif ($vendor['status'] === 'active'): ?>
                            bg-green-100 text-green-800
                        <?php elseif ($vendor['status'] === 'rejected'): ?>
                            bg-red-100 text-red-800
                        <?php elseif ($vendor['status'] === 'suspended'): ?>
                            bg-gray-100 text-gray-800
                        <?php endif; ?>
                    ">
                        <?= ucfirst(htmlspecialchars($vendor['status'])) ?>
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <div class="flex justify-end space-x-2">
                        <?php if ($vendor['status'] === 'pending'): ?>
                        <form action="/admin/vendors/approve" method="post" class="inline">
                            <input type="hidden" name="vendor_id" value="<?= $vendor['id'] ?>">
                            <button type="submit" class="text-green-600 hover:text-green-900 bg-green-100 hover:bg-green-200 rounded-md px-2 py-1 transition-colors">
                                Approve
                            </button>
                        </form>
                        <form action="/admin/vendors/reject" method="post" class="inline">
                            <input type="hidden" name="vendor_id" value="<?= $vendor['id'] ?>">
                            <button type="submit" class="text-red-600 hover:text-red-900 bg-red-100 hover:bg-red-200 rounded-md px-2 py-1 transition-colors">
                                Reject
                            </button>
                        </form>
                        <?php elseif ($vendor['status'] === 'active'): ?>
                        <form action="/admin/vendors/suspend" method="post" class="inline">
                            <input type="hidden" name="vendor_id" value="<?= $vendor['id'] ?>">
                            <button type="submit" class="text-yellow-600 hover:text-yellow-900 bg-yellow-100 hover:bg-yellow-200 rounded-md px-2 py-1 transition-colors">
                                Suspend
                            </button>
                        </form>
                        <?php elseif ($vendor['status'] === 'rejected' || $vendor['status'] === 'suspended'): ?>
                        <form action="/admin/vendors/approve" method="post" class="inline">
                            <input type="hidden" name="vendor_id" value="<?= $vendor['id'] ?>">
                            <button type="submit" class="text-green-600 hover:text-green-900 bg-green-100 hover:bg-green-200 rounded-md px-2 py-1 transition-colors">
                                Activate
                            </button>
                        </form>
                        <?php endif; ?>
                        <a href="/admin/vendors/view?id=<?= $vendor['id'] ?>" class="text-blue-600 hover:text-blue-900 bg-blue-100 hover:bg-blue-200 rounded-md px-2 py-1 transition-colors">
                            Details
                        </a>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php endif; ?>
    </div>
</div>