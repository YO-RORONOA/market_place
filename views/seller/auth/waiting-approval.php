
<div class="flex justify-center">
    <div class="w-full max-w-2xl bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="bg-gradient-to-r from-accent-navy via-accent-teal to-accent-ceramicblue p-6">
            <h2 class="text-2xl font-bold text-white text-center">Account Pending Approval</h2>
            <div class="flex justify-center mt-4">
                <div class="w-16 h-1 bg-white rounded-full"></div>
            </div>
        </div>
        
        <div class="p-6">
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-yellow-700">
                            Your vendor account is pending administrator approval.
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-accent-navy mb-3">Application Status</h3>
                
                <div class="mb-6">
                    <div class="relative">
                        <div class="absolute inset-0 flex items-center" aria-hidden="true">
                            <div class="w-full border-t border-gray-300"></div>
                        </div>
                        <div class="relative flex justify-between">
                            <div>
                                <span class="bg-white px-2 text-sm text-accent-teal font-medium">
                                    Application Submitted
                                </span>
                                <div class="mt-1 w-5 h-5 bg-accent-teal rounded-full"></div>
                            </div>
                            <div>
                                <span class="bg-white px-2 text-sm text-yellow-500 font-medium">
                                    Under Review
                                </span>
                                <div class="mt-1 w-5 h-5 bg-yellow-400 rounded-full animate-pulse"></div>
                            </div>
                            <div>
                                <span class="bg-white px-2 text-sm text-gray-500 font-medium">
                                    Approved
                                </span>
                                <div class="mt-1 w-5 h-5 bg-gray-300 rounded-full"></div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <p class="text-gray-600 mb-4">
                    Thank you for applying to become a vendor on our marketplace. Your application for 
                    <strong class="text-accent-navy"><?= htmlspecialchars($vendor->store_name) ?></strong> 
                    has been received and is currently being reviewed by our team.
                </p>
                
                <p class="text-gray-600 mb-4">
                    This process typically takes 1-2 business days. Once your application is approved, you'll 
                    receive an email notification and will be able to access your vendor dashboard to start 
                    adding products and managing your store.
                </p>
                
                <div class="bg-gray-50 p-4 rounded-lg mb-6">
                    <h4 class="text-sm font-semibold text-accent-navy mb-2">Application Details</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="text-gray-500">Store Name:</span>
                            <span class="ml-2 font-medium text-gray-900"><?= htmlspecialchars($vendor->store_name) ?></span>
                        </div>
                        <div>
                            <span class="text-gray-500">Submitted On:</span>
                            <span class="ml-2 font-medium text-gray-900"><?= date('F j, Y', strtotime($vendor->created_at)) ?></span>
                        </div>
                        <div>
                            <span class="text-gray-500">Status:</span>
                            <span class="ml-2 font-medium text-yellow-600">Pending Approval</span>
                        </div>
                    </div>
                </div>
                
                <div class="bg-accent-teal bg-opacity-10 p-4 rounded-lg">
                    <h4 class="text-sm font-semibold text-accent-teal mb-2">What's Next?</h4>
                    <p class="text-sm text-gray-600 mb-2">
                        While you're waiting for approval, you can:
                    </p>
                    <ul class="list-disc list-inside text-sm text-gray-600 space-y-1">
                        <li>Prepare product descriptions and images</li>
                        <li>Review our <a href="#" class="text-accent-teal hover:underline">Vendor Guidelines</a></li>
                        <li>Check out our <a href="#" class="text-accent-teal hover:underline">Seller FAQ</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="flex flex-col md:flex-row space-y-4 md:space-y-0 md:space-x-4">
                <a href="/" class="flex-1 py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none transition-colors text-center">
                    Return to Homepage
                </a>
                <a href="/buyer/switch" class="flex-1 py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-accent-navy hover:bg-accent-ceramicblue focus:outline-none transition-colors text-center">
                    Switch to Buyer Account
                </a>
            </div>
            
            <div class="mt-6 text-center">
                <p class="text-sm text-gray-500">
                    Need help? Contact our <a href="mailto:support@youmarket.com" class="text-accent-teal hover:underline">support team</a>
                </p>
            </div>
        </div>
    </div>
</div>