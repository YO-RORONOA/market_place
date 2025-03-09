<div class="flex justify-center">
    <div class="w-full max-w-xl bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="bg-gradient-to-r from-accent-terracotta via-accent-ochre to-accent-teal p-6">
            <h2 class="text-2xl font-bold text-white text-center">Verify Your Email</h2>
            <div class="flex justify-center mt-4">
                <div class="w-16 h-1 bg-white rounded-full"></div>
            </div>
        </div>
        
        <div class="p-6">
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-yellow-700">
                            Please check your email and click the verification link to activate your account.
                        </p>
                    </div>
                </div>
            </div>
            
            <p class="text-sm text-gray-600 mb-6">
                We've sent a verification link to <strong><?= $email ?></strong>. 
                If you don't see it in your inbox, please check your spam folder.
            </p>
            
            <div class="mt-6">
                <a href="/resend-verification" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-accent-navy hover:bg-accent-ceramicblue focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-accent-ceramicblue transition-colors duration-200">
                    Resend Verification Email
                </a>
            </div>
            
            <div class="mt-6 text-center">
                <p class="text-sm text-gray-600">
                    Need help? 
                    <a href="/contact" class="font-medium text-accent-ceramicblue hover:text-accent-teal">
                        Contact Support
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>