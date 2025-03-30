<div class="mb-6">
    <a href="/vendor/products" class="inline-flex items-center text-accent-teal hover:text-accent-navy transition">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        Back to Products
    </a>
</div>

<h1 class="text-2xl font-bold text-accent-navy mb-6">Create New Product</h1>

<form action="/vendor/products/store" method="post" enctype="multipart/form-data" id="productForm">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Main Form Fields (Left Column) -->
        <div class="md:col-span-2 space-y-6">
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h2 class="text-lg font-semibold text-accent-navy mb-4">Product Information</h2>
                
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Product Name *</label>
                    <input 
                        type="text" 
                        id="name" 
                        name="name" 
                        required
                        class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-accent-teal focus:border-accent-teal"
                    >
                </div>
                
                <div class="mb-4">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">
                        Description *
                        <button 
                            type="button" 
                            id="generateDescription" 
                            class="ml-2 inline-flex items-center px-2 py-1 text-xs font-medium rounded text-accent-teal bg-accent-teal bg-opacity-10 hover:bg-opacity-20 focus:outline-none transition"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                            Generate with AI
                        </button>
                    </label>
                    <textarea 
                        id="description" 
                        name="description" 
                        rows="6" 
                        required
                        class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-accent-teal focus:border-accent-teal"
                    ></textarea>
                    <div id="aiSpinner" class="hidden mt-2 text-accent-teal text-sm flex items-center">
                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-accent-teal" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Generating description...
                    </div>
                    <div id="aiError" class="hidden mt-2 text-red-500 text-sm"></div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="price" class="block text-sm font-medium text-gray-700 mb-1">Price (MAD) *</label>
                        <input 
                            type="number" 
                            id="price" 
                            name="price" 
                            min="0.01" 
                            step="0.01" 
                            required
                            class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-accent-teal focus:border-accent-teal"
                        >
                    </div>
                    
                    <div>
                        <label for="stock_quantity" class="block text-sm font-medium text-gray-700 mb-1">Stock Quantity *</label>
                        <input 
                            type="number" 
                            id="stock_quantity" 
                            name="stock_quantity" 
                            min="0" 
                            required
                            class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-accent-teal focus:border-accent-teal"
                        >
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h2 class="text-lg font-semibold text-accent-navy mb-4">Product Details</h2>
                
                <div class="mb-4">
                    <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">Category *</label>
                    <select 
                        id="category_id" 
                        name="category_id" 
                        required
                        class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-accent-teal focus:border-accent-teal"
                    >
                        <option value="">Select Category</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= $category['id'] ?>"><?= htmlspecialchars($category['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="mb-4">
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select 
                        id="status" 
                        name="status" 
                        class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-accent-teal focus:border-accent-teal"
                    >
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
                
                <div class="mb-4">
                    <label for="tags" class="block text-sm font-medium text-gray-700 mb-1">
                        Tags (Comma separated)
                        <button 
                            type="button" 
                            id="generateTags" 
                            class="ml-2 inline-flex items-center px-2 py-1 text-xs font-medium rounded text-accent-teal bg-accent-teal bg-opacity-10 hover:bg-opacity-20 focus:outline-none transition"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                            Generate with AI
                        </button>
                    </label>
                    <input 
                        type="text" 
                        id="tags" 
                        name="tags" 
                        placeholder="e.g. handcraft, ceramic, moroccan"
                        class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-accent-teal focus:border-accent-teal"
                    >
                    <div id="tagsSpinner" class="hidden mt-2 text-accent-teal text-sm flex items-center">
                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-accent-teal" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Generating tags...
                    </div>
                    <div id="tagsError" class="hidden mt-2 text-red-500 text-sm"></div>
                </div>
            </div>
        </div>
        
        <!-- Right Column (Image Upload) -->
        <div class="md:col-span-1">
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h2 class="text-lg font-semibold text-accent-navy mb-4">Product Image</h2>
                
                <div class="mb-4">
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                        <div class="space-y-1 text-center">
                            <div id="imagePreviewContainer" class="hidden mb-3">
                                <img id="imagePreview" src="#" alt="Image preview" class="mx-auto h-32 w-auto">
                            </div>
                            
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="flex text-sm text-gray-600">
                                <label for="image" class="relative cursor-pointer bg-white rounded-md font-medium text-accent-teal hover:text-accent-navy focus-within:outline-none">
                                    <span>Upload a file</span>
                                    <input id="image" name="image" type="file" class="sr-only" accept="image/*">
                                </label>
                                <p class="pl-1">or drag and drop</p>
                            </div>
                            <p class="text-xs text-gray-500">
                                PNG, JPG, GIF up to 2MB
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-sm p-6 mt-6">
                <h2 class="text-lg font-semibold text-accent-navy mb-4">SEO Optimization</h2>
                
                <div class="mb-4">
                    <label for="meta_title" class="block text-sm font-medium text-gray-700 mb-1">Meta Title</label>
                    <input 
                        type="text" 
                        id="meta_title" 
                        name="meta_title" 
                        class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-accent-teal focus:border-accent-teal"
                    >
                    <p class="mt-1 text-xs text-gray-500">Recommended: 50-60 characters</p>
                </div>
                
                <div class="mb-4">
                    <label for="meta_description" class="block text-sm font-medium text-gray-700 mb-1">Meta Description</label>
                    <textarea 
                        id="meta_description" 
                        name="meta_description" 
                        rows="3" 
                        class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-accent-teal focus:border-accent-teal"
                    ></textarea>
                    <p class="mt-1 text-xs text-gray-500">Recommended: 150-160 characters</p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="mt-6 flex justify-end space-x-3">
        <a href="/vendor/products" class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition">
            Cancel
        </a>
        <button type="submit" class="px-6 py-2 bg-accent-ochre hover:bg-accent-terracotta text-white font-medium rounded-md transition">
            Create Product
        </button>
    </div>
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Image preview
    const imageInput = document.getElementById('image');
    const imagePreview = document.getElementById('imagePreview');
    const imagePreviewContainer = document.getElementById('imagePreviewContainer');
    
    imageInput.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                imagePreview.src = e.target.result;
                imagePreviewContainer.classList.remove('hidden');
            }
            reader.readAsDataURL(file);
        } else {
            imagePreviewContainer.classList.add('hidden');
        }
    });
    
    // Generate description with AI
    const generateDescBtn = document.getElementById('generateDescription');
    const descriptionField = document.getElementById('description');
    const nameField = document.getElementById('name');
    const categoryField = document.getElementById('category_id');
    const aiSpinner = document.getElementById('aiSpinner');
    const aiError = document.getElementById('aiError');
    
    generateDescBtn.addEventListener('click', function() {
        const productName = nameField.value.trim();
        if (!productName) {
            aiError.textContent = 'Please enter a product name first.';
            aiError.classList.remove('hidden');
            return;
        }
        
        const categoryId = categoryField.value;
        let categoryName = '';
        if (categoryId) {
            categoryName = categoryField.options[categoryField.selectedIndex].text;
        }
        
        // Show spinner, hide error
        aiSpinner.classList.remove('hidden');
        aiError.classList.add('hidden');
        
        // Make request to generate description
        fetch('/vendor/products/generate-description', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                name: productName,
                category: categoryName
            })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Failed to generate description');
            }
            return response.json();
        })
        .then(data => {
            if (data.error) {
                throw new Error(data.error);
            }
            
            descriptionField.value = data.description;
            aiSpinner.classList.add('hidden');
        })
        .catch(error => {
            aiSpinner.classList.add('hidden');
            aiError.textContent = error.message || 'Failed to generate description. Please try again.';
            aiError.classList.remove('hidden');
            console.error('Error:', error);
        });
    });
    
    // Generate tags with AI
    const generateTagsBtn = document.getElementById('generateTags');
    const tagsField = document.getElementById('tags');
    const tagsSpinner = document.getElementById('tagsSpinner');
    const tagsError = document.getElementById('tagsError');
    
    generateTagsBtn.addEventListener('click', function() {
        const productName = nameField.value.trim();
        if (!productName) {
            tagsError.textContent = 'Please enter a product name first.';
            tagsError.classList.remove('hidden');
            return;
        }
        
        const categoryId = categoryField.value;
        let categoryName = '';
        if (categoryId) {
            categoryName = categoryField.options[categoryField.selectedIndex].text;
        }
        
        const description = descriptionField.value.trim();
        
        // Show spinner, hide error
        tagsSpinner.classList.remove('hidden');
        tagsError.classList.add('hidden');
        
        // Make request to generate tags
        fetch('/vendor/products/generate-tags', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                name: productName,
                category: categoryName,
                description: description
            })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Failed to generate tags');
            }
            return response.json();
        })
        .then(data => {
            if (data.error) {
                throw new Error(data.error);
            }
            
            tagsField.value = data.tags.join(', ');
            tagsSpinner.classList.add('hidden');
        })
        .catch(error => {
            tagsSpinner.classList.add('hidden');
            tagsError.textContent = error.message || 'Failed to generate tags. Please try again.';
            tagsError.classList.remove('hidden');
            console.error('Error:', error);
        });
    });
});
</script>