<?php
use App\core\form\Form;
?>


<div class="flex justify-center">
    <div class="w-full max-w-xl bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="bg-gradient-to-r from-accent-terracotta via-accent-ochre to-accent-teal p-6">
            <h2 class="text-2xl font-bold text-white text-center">Create Your Account</h2>
            <div class="flex justify-center mt-4">
                <div class="w-16 h-1 bg-white rounded-full"></div>
            </div>
        </div>
        
        <div class="p-6">
            <?php $form = Form::begin('', 'post', ['class' => 'space-y-6']); ?>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="firstname" class="block text-sm font-medium text-gray-700">First name</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <?php echo $form->field($model, 'firstname')
                                ->fieldOnly()
                                ->addAttributes([
                                    'class' => 'appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-accent-teal focus:border-accent-teal',
                                    'placeholder' => 'Enter your first name'
                                ]); ?>
                        </div>
                    </div>
                    
                    <div>
                        <label for="lastname" class="block text-sm font-medium text-gray-700">Last name</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <?php echo $form->field($model, 'lastname')
                                ->fieldOnly()
                                ->addAttributes([
                                    'class' => 'appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-accent-teal focus:border-accent-teal',
                                    'placeholder' => 'Enter your last name'
                                ]); ?>
                        </div>
                    </div>
                </div>
                
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email address</label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <?php echo $form->field($model, 'email')
                            ->fieldOnly()
                            ->addAttributes([
                                'class' => 'appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-accent-teal focus:border-accent-teal',
                                'placeholder' => 'you@example.com'
                            ]); ?>
                    </div>
                </div>
                
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <?php echo $form->field($model, 'password')
                            ->fieldOnly()
                            ->passwordField()
                            ->addAttributes([
                                'class' => 'appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-accent-teal focus:border-accent-teal',
                                'placeholder' => 'Create a secure password'
                            ]); ?>
                    </div>
                    <p class="mt-1 text-xs text-gray-500">Must be at least 8 characters</p>
                </div>
                
                <div>
                    <label for="passwordConfirm" class="block text-sm font-medium text-gray-700">Confirm password</label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <?php echo $form->field($model, 'passwordConfirm')
                            ->fieldOnly()
                            ->passwordField()
                            ->addAttributes([
                                'class' => 'appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-accent-teal focus:border-accent-teal',
                                'placeholder' => 'Confirm your password'
                            ]); ?>
                    </div>
                </div>
                
                <div class="flex items-center">
                    <input id="terms" name="terms" type="checkbox" class="h-4 w-4 text-accent-teal focus:ring-accent-teal border-gray-300 rounded">
                    <label for="terms" class="ml-2 block text-sm text-gray-700">
                        I agree to the <a href="#" class="text-accent-ceramicblue hover:text-accent-teal">Terms of Service</a> and <a href="#" class="text-accent-ceramicblue hover:text-accent-teal">Privacy Policy</a>
                    </label>
                </div>
                
                <div>
                    <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-accent-navy hover:bg-accent-ceramicblue focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-accent-ceramicblue transition-colors duration-200">
                        Create Account
                    </button>
                </div>
                
                <div class="relative py-2">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-300"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 bg-white text-gray-500">Or sign up with</span>
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <a href="#" class="w-full inline-flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                            <svg class="w-5 h-5" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M47.532 24.5528C47.532 22.9214 47.3997 21.2811 47.1175 19.6761H24.48V28.9181H37.4434C36.9055 31.8988 35.177 34.5356 32.6461 36.2111V42.2078H40.3801C44.9217 38.0278 47.532 31.8547 47.532 24.5528Z" fill="#4285F4"/>
                                <path d="M24.48 48.0016C30.9529 48.0016 36.4116 45.8764 40.3888 42.2078L32.6549 36.2111C30.5031 37.675 27.7252 38.5039 24.4888 38.5039C18.2275 38.5039 12.9187 34.2798 11.0139 28.6006H3.03296V34.7825C7.10718 42.8868 15.4056 48.0016 24.48 48.0016Z" fill="#34A853"/>
                                <path d="M11.0051 28.6006C9.99973 25.6199 9.99973 22.3922 11.0051 19.4115V13.2296H3.03298C-0.371021 20.0112 -0.371021 28.0009 3.03298 34.7825L11.0051 28.6006Z" fill="#FBBC04"/>
                                <path d="M24.48 9.49932C27.9016 9.44641 31.2086 10.7339 33.6866 13.0973L40.5387 6.24523C36.2 2.17101 30.4414 -0.068932 24.48 0.00161733C15.4055 0.00161733 7.10718 5.11644 3.03296 13.2296L11.005 19.4115C12.901 13.7235 18.2187 9.49932 24.48 9.49932Z" fill="#EA4335"/>
                            </svg>
                        </a>
                    </div>
                    <div>
                        <a href="#" class="w-full inline-flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                            <svg class="w-5 h-5" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M24 5C13.5066 5 5 13.5066 5 24C5 34.4934 13.5066 43 24 43C34.4934 43 43 34.4934 43 24C43 13.5066 34.4934 5 24 5ZM30.0982 17.0982H27.5666C27.0498 17.0982 26.4688 17.7604 26.4688 18.5604V20.5H30.0982L29.5184 24.0294H26.4688V34.422H22.2184V24.0294H19.1516V20.5H22.2184V18.9516C22.2184 15.971 24.421 13.578 27.5666 13.578H30.0982V17.0982Z" fill="#3b5998"/>
                            </svg>
                        </a>
                    </div>
                </div>
            <?php Form::end(); ?>
            
            <div class="mt-6 text-center">
                <p class="text-sm text-gray-600">
                    Already have an account? 
                    <a href="/login" class="font-medium text-accent-ceramicblue hover:text-accent-teal">
                        Sign in
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>