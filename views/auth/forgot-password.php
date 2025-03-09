<?php
use App\core\form\Form;
?>

<div class="flex justify-center">
    <div class="w-full max-w-xl bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="bg-gradient-to-r from-accent-terracotta via-accent-ochre to-accent-teal p-6">
            <h2 class="text-2xl font-bold text-white text-center">Reset Your Password</h2>
            <div class="flex justify-center mt-4">
                <div class="w-16 h-1 bg-white rounded-full"></div>
            </div>
        </div>
        
        <div class="p-6">
            <p class="text-sm text-gray-600 mb-6">
                Enter your email address and we'll send you a link to reset your password.
            </p>
            
            <?php $form = Form::begin('', 'post'); ?>
                <?php echo $form->field($model, 'email'); ?>
                
                <div class="mt-6">
                    <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-accent-navy hover:bg-accent-ceramicblue focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-accent-ceramicblue transition-colors duration-200">
                        Send Reset Link
                    </button>
                </div>
            <?php Form::end(); ?>
            
            <div class="mt-6 text-center">
                <p class="text-sm text-gray-600">
                    Remember your password? 
                    <a href="/login" class="font-medium text-accent-ceramicblue hover:text-accent-teal">
                        Back to login
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>