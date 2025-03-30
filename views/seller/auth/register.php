<?php
use App\core\form\Form;
?>

<div class="flex justify-center">
    <div class="w-full max-w-xl bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="bg-gradient-to-r from-accent-navy via-accent-teal to-accent-ceramicblue p-6">
            <h2 class="text-2xl font-bold text-white text-center">Become a Vendor</h2>
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
                            Already have a customer account? You can still register as a vendor with a new vendor account.
                        </p>
                    </div>
                </div>
            </div>
            
            <?php $form = Form::begin('', 'post'); ?>
                <h3 class="text-lg font-medium text-accent-navy mb-4">Account Information</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <?php echo $form->field($userModel, 'firstname'); ?>
                    <?php echo $form->field($userModel, 'lastname'); ?>
                </div>
                
                <?php echo $form->field($userModel, 'email'); ?>
                <?php echo $form->field($userModel, 'password')->passwordField(); ?>
                <p class="mt-1 text-xs text-gray-500">Must be at least 8 characters</p>
                <?php echo $form->field($userModel, 'passwordConfirm')->passwordField(); ?>
                
                <h3 class="text-lg font-medium text-accent-navy mt-8 mb-4">Store Information</h3>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Store Name</label>
                    <input 
                        name="store_name" 
                        type="text" 
                        value="<?= $vendorModel->store_name ?>" 
                        class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-accent-teal focus:border-accent-teal <?= $vendorModel->hasError('store_name') ? 'border-red-500' : '' ?>"
                    >
                    <div class="text-red-500 mt-1 text-sm">
                        <?= $vendorModel->getFirstError('store_name') ?>
                    </div>
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Store Description</label>
                    <textarea 
                        name="description" 
                        rows="3" 
                        class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-accent-teal focus:border-accent-teal <?= $vendorModel->hasError('description') ? 'border-red-500' : '' ?>"
                    ><?= $vendorModel->description ?></textarea>
                    <div class="text-red-500 mt-1 text-sm">
                        <?= $vendorModel->getFirstError('description') ?>
                    </div>
                </div>
                
                <div class="flex items-center mt-6">
                    <input id="terms" name="terms" type="checkbox" class="h-4 w-4 text-accent-teal focus:ring-accent-teal border-gray-300 rounded" required>
                    <label for="terms" class="ml-2 block text-sm text-gray-700">
                        I agree to the <a href="#" class="text-accent-ceramicblue hover:text-accent-teal">Terms of Service</a>, <a href="#" class="text-accent-ceramicblue hover:text-accent-teal">Privacy Policy</a>, and <a href="#" class="text-accent-ceramicblue hover:text-accent-teal">Vendor Agreement</a>
                    </label>
                </div>
                
                <div class="mt-6">
                    <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-accent-navy hover:bg-accent-ceramicblue focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-accent-ceramicblue transition-colors duration-200">
                        Register as a Vendor
                    </button>
                </div>
            <?php Form::end(); ?>
            
            <div class="mt-6 text-center">
                <p class="text-sm text-gray-600">
                    Already have a vendor account? 
                    <a href="/vendor/login" class="font-medium text-accent-ceramicblue hover:text-accent-teal">
                        Sign in
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>