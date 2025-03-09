<div class="flex justify-center">
    <div class="w-full max-w-xl bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="bg-gradient-to-r from-accent-terracotta via-accent-ochre to-accent-teal p-6">
            <h2 class="text-2xl font-bold text-white text-center">Invalid or Expired Token</h2>
            <div class="flex justify-center mt-4">
                <div class="w-16 h-1 bg-white rounded-full"></div>
            </div>
        </div>
        
        <div class="p-6 text-center">
            <div class="flex justify-center mb-6">
                <div class="rounded-full bg-red-100 p-3">
                    <svg class="h-12 w-12 text-red-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
            </div>
            
            <h3 class="text-lg font-medium text-gray-900 mb-2">The link you followed is invalid or has expired</h3>
            <p class="text-sm text-gray-600 mb-6">
                Please request a new verification link or password reset link.
            </p>
            
            <div class="grid grid-cols-1 gap-4 mt-6">
                <a href="/forgot-password" class="flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-accent-navy hover:bg-accent-ceramicblue focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-accent-ceramicblue transition-colors duration-200">
                    Request New Password Reset
                </a>
                <a href="/login" class="flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-accent-ceramicblue transition-colors duration-200">
                    Back to Login
                </a>
            </div>
        </div>
    </div>
</div>