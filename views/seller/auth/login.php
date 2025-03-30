<?php
use App\core\form\Form;
?>

<div class="flex justify-center">
    <div class="w-full max-w-xl bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="bg-gradient-to-r from-accent-navy via-accent-teal to-accent-ceramicblue p-6">
            <h2 class="text-2xl font-bold text-white text-center">Vendor Login</h2>
            <div class="flex justify-center mt-4">
                <div class="w-16 h-1 bg-white rounded-full"></div>
            </div>
        </div>
        
        <div class="p-6">
            <?php $form = Form::begin('', 'post'); ?>
                <?php echo $form->field($model, 'email'); ?>
                
                <?php echo $form->field($model, 'password')->passwordField(); ?>
                
                <div class="flex items-center justify-between mt-4">
                    <div class="flex items-center">
                        <input id="remember_me" name="rememberMe" type="checkbox" class="h-4 w-4 text-accent-teal focus:ring-accent-teal border-gray-300 rounded">
                        <label for="remember_me" class="ml-2 block text-sm text-gray-700">
                            Remember me
                        </label>
                    </div>
                    
                    <div class="text-sm">
                        <a href="/forgot-password" class="font-medium text-accent-ceramicblue hover:text-accent-teal">
                            Forgot your password?
                        </a>
                    </div>
                </div>
                
                <div class="mt-6">
                    <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-accent-navy hover:bg-accent-ceramicblue focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-accent-ceramicblue transition-colors duration-200">
                        Sign In to Vendor Dashboard
                    </button>
                </div>
            <?php Form::end(); ?>
            
            <div class="mt-6 text-center">
                <p class="text-sm text-gray-600">
                    Don't have a vendor account? 
                    <a href="/vendor/register" class="font-medium text-accent-ceramicblue hover:text-accent-teal">
                        Register as a Vendor
                    </a>
                </p>
                <p class="text-sm text-gray-600 mt-2">
                    Looking for customer login? 
                    <a href="/login" class="font-medium text-accent-ceramicblue hover:text-accent-teal">
                        Customer Login
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>