<?php
use App\core\form\Form;
?>

<div class="flex justify-center">
    <div class="w-full max-w-xl bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="bg-gradient-to-r from-gray-800 via-gray-700 to-gray-600 p-6">
            <h2 class="text-2xl font-bold text-white text-center">Admin Portal</h2>
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
                            This area is restricted to authorized administrators only.
                        </p>
                    </div>
                </div>
            </div>
            
            <?php $form = Form::begin('', 'post'); ?>
                <?php echo $form->field($model, 'email'); ?>
                
                <?php echo $form->field($model, 'password')->passwordField(); ?>
                
                <div class="flex items-center justify-between mt-4">
                    <div class="flex items-center">
                        <input id="remember_me" name="rememberMe" type="checkbox" class="h-4 w-4 text-gray-800 focus:ring-gray-700 border-gray-300 rounded">
                        <label for="remember_me" class="ml-2 block text-sm text-gray-700">
                            Remember me
                        </label>
                    </div>
                </div>
                
                <div class="mt-6">
                    <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-gray-800 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200">
                        Sign In to Admin Portal
                    </button>
                </div>
            <?php Form::end(); ?>
            
            <div class="mt-6 text-center">
                <p class="text-sm text-gray-600">
                    Return to 
                    <a href="/" class="font-medium text-accent-navy hover:text-accent-teal">
                        main site
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>