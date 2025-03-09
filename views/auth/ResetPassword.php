<?php
use App\core\form\Form;
?>

<div class="flex justify-center">
    <div class="w-full max-w-xl bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="bg-gradient-to-r from-accent-terracotta via-accent-ochre to-accent-teal p-6">
            <h2 class="text-2xl font-bold text-white text-center">Create New Password</h2>
            <div class="flex justify-center mt-4">
                <div class="w-16 h-1 bg-white rounded-full"></div>
            </div>
        </div>
        
        <div class="p-6">
            <p class="text-sm text-gray-600 mb-6">
                Your password must be at least 8 characters long and contain a mix of letters and numbers.
            </p>
            
            <?php $form = Form::begin('', 'post'); ?>
                <input type="hidden" name="token" value="<?= $token ?>">
                
                <?php echo $form->field($model, 'password')->passwordField(); ?>
                <p class="mt-1 text-xs text-gray-500">Must be at least 8 characters</p>
                
                <?php echo $form->field($model, 'passwordConfirm')->passwordField(); ?>
                
                <div class="mt-6">
                    <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-accent-navy hover:bg-accent-ceramicblue focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-accent-ceramicblue transition-colors duration-200">
                        Reset Password
                    </button>
                </div>
            <?php Form::end(); ?>
        </div>
    </div>
</div>