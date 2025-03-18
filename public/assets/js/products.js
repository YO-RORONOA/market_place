// public/js/products.js

/**
 * YOU/Market Products Functionality
 * Handles dynamic product loading, filtering, and pagination
 */

document.addEventListener('DOMContentLoaded', function() {
    // Initialize product search
    initProductSearch();
    
    // Initialize pagination
    initPagination();
    
    // Initialize category filtering
    initCategoryFiltering();
  });
  
  // Initialize product search with AJAX
  function initProductSearch() {
    const searchForm = document.querySelector('.product-search-form');
    if (!searchForm) return;
    
    searchForm.addEventListener('submit', async function(e) {
      e.preventDefault();
      
      const searchInput = this.querySelector('[name="search"]');
      const searchTerm = searchInput.value.trim();
      
      // Update URL without refreshing the page
      const url = new URL(window.location);
      
      if (searchTerm) {
        url.searchParams.set('search', searchTerm);
        url.searchParams.delete('category');
      } else {
        url.searchParams.delete('search');
      }
      
      url.searchParams.set('page', '1');
      window.history.pushState({}, '', url);
      
      // Show loading state
      const productsContainer = document.querySelector('.products-container');
      productsContainer.innerHTML = getLoadingHTML();
      
      // Fetch results
      const response = await fetchFromServer(url.pathname + url.search, {
        headers: {
          'X-Requested-With': 'XMLHttpRequest'
        }
      });
      
      if (response && response.success) {
        // Update products
        renderProducts(response.products, productsContainer);
        
        // Update pagination
        renderPagination(response.currentPage, response.totalPages, response.categoryId, response.search);
        
        // Re-initialize add to cart forms
        initAddToCartForms();
        
        // Update page title and heading
        const heading = document.querySelector('h1');
        if (heading) {
          const newTitle = searchTerm ? `Search: ${searchTerm}` : 'All Products';
          heading.textContent = newTitle;
          document.title = `YOU/Market - ${newTitle}`;
        }
      } else {
        // Show error message
        productsContainer.innerHTML = `
          <div class="col-span-3 bg-white rounded-lg shadow-sm p-8 text-center">
            <p class="text-gray-600 mb-4">Error loading products. Please try again.</p>
            <a href="/products" class="text-accent-teal hover:underline">View all products</a>
          </div>
        `;
      }
    });
  }
  
  // Initialize pagination with AJAX
  function initPagination() {
    const paginationLinks = document.querySelectorAll('.pagination-link');
    if (!paginationLinks.length) return;
    
    paginationLinks.forEach(link => {
      link.addEventListener('click', async function(e) {
        e.preventDefault();
        
        const page = this.dataset.page;
        const url = new URL(window.location);
        url.searchParams.set('page', page);
        window.history.pushState({}, '', url);
        
        // Scroll to top of products
        const productsContainer = document.querySelector('.products-container');
        productsContainer.scrollIntoView({ behavior: 'smooth' });
        
        // Show loading state
        productsContainer.innerHTML = getLoadingHTML();
        
        // Fetch results
        const response = await fetchFromServer(url.pathname + url.search, {
          headers: {
            'X-Requested-With': 'XMLHttpRequest'
          }
        });
        
        if (response && response.success) {
          // Update products
          renderProducts(response.products, productsContainer);
          
          // Update pagination
          renderPagination(response.currentPage, response.totalPages, response.categoryId, response.search);
          
          // Re-initialize add to cart forms
          initAddToCartForms();
        } else {
          // Show error message
          productsContainer.innerHTML = `
            <div class="col-span-3 bg-white rounded-lg shadow-sm p-8 text-center">
              <p class="text-gray-600 mb-4">Error loading products. Please try again.</p>
              <a href="/products" class="text-accent-teal hover:underline">View all products</a>
            </div>
          `;
        }
      });
    });
  }
  
  // Initialize category filtering with AJAX
  function initCategoryFiltering() {
    const categoryLinks = document.querySelectorAll('.category-link');
    if (!categoryLinks.length) return;
    
    categoryLinks.forEach(link => {
      link.addEventListener('click', async function(e) {
        e.preventDefault();
        
        const categoryId = this.dataset.categoryId;
        const categoryName = this.textContent.trim();
        const url = new URL(window.location);
        
        if (categoryId) {
          url.searchParams.set('category', categoryId);
          url.searchParams.delete('search');
        } else {
          url.searchParams.delete('category');
        }
        
        url.searchParams.set('page', '1');
        window.history.pushState({}, '', url);
        
        // Update active category styling
        document.querySelectorAll('.category-link').forEach(el => {
          el.classList.remove('bg-accent-teal', 'text-white');
          el.classList.add('hover:bg-gray-100');
        });
        
        this.classList.add('bg-accent-teal', 'text-white');
        this.classList.remove('hover:bg-gray-100');
        
        // Scroll to top of products
        const productsContainer = document.querySelector('.products-container');
        productsContainer.scrollIntoView({ behavior: 'smooth' });
        
        // Show loading state
        productsContainer.innerHTML = getLoadingHTML();
        
        // Fetch results
        const response = await fetchFromServer(url.pathname + url.search, {
          headers: {
            'X-Requested-With': 'XMLHttpRequest'
          }
        });
        
        if (response && response.success) {
          // Update products
          renderProducts(response.products, productsContainer);
          
          // Update pagination
          renderPagination(response.currentPage, response.totalPages, response.categoryId, response.search);
          
          // Update page title and heading
          const heading = document.querySelector('h1');
          if (heading) {
            heading.textContent = categoryId ? categoryName : 'All Products';
            document.title = `YOU/Market - ${categoryId ? categoryName : 'All Products'}`;
          }
          
          // Re-initialize add to cart forms
          initAddToCartForms();
        } else {
          // Show error message
          productsContainer.innerHTML = `
            <div class="col-span-3 bg-white rounded-lg shadow-sm p-8 text-center">
              <p class="text-gray-600 mb-4">Error loading products. Please try again.</p>
              <a href="/products" class="text-accent-teal hover:underline">View all products</a>
            </div>
          `;
        }
      });
    });
  }
  
  // Render product cards using the template
  function renderProducts(products, container) {
    // Clear container
    container.innerHTML = '';
    
    if (!products || products.length === 0) {
      container.innerHTML = `
        <div class="col-span-3 bg-white rounded-lg shadow-sm p-8 text-center">
          <p class="text-gray-600 mb-4">No products found.</p>
          <a href="/products" class="text-accent-teal hover:underline">View all products</a>
        </div>
      `;
      return;
    }
    
    const template = document.getElementById('product-card-template');
    const adTemplate = document.getElementById('ad-interstitial-template');
    
    products.forEach((product, index) => {
      // Clone the template
      const productCard = template.content.cloneNode(true);
      
      // Update product link
      const productLink = productCard.querySelector('.product-link');
      productLink.href = `/products/view?id=${product.id}`;
      
      // Update product image
      const productImage = productCard.querySelector('.product-image');
      const noImagePlaceholder = productCard.querySelector('.no-image-placeholder');
      
      if (product.image_path) {
        productImage.src = product.image_path;
        productImage.alt = product.name;
        productImage.style.display = 'block';
        noImagePlaceholder.style.display = 'none';
      } else {
        productImage.style.display = 'none';
        noImagePlaceholder.style.display = 'flex';
      }
      
      // Update product details
      productCard.querySelector('.product-name').textContent = product.name;
      productCard.querySelector('.product-price').textContent = `${parseFloat(product.price).toFixed(2)} MAD`;
      productCard.querySelector('.product-description').textContent = product.description;
      
      // Update form
      const productIdInput = productCard.querySelector('.product-id-input');
      productIdInput.value = product.id;
      
      // Add to container
      container.appendChild(productCard);
      
      // Add interstitial ad after every 6 products
      if ((index + 1) % 6 === 0 && (index + 1) < products.length) {
        const adInterstitial = adTemplate.content.cloneNode(true);
        container.appendChild(adInterstitial);
      }
    });
  }
  
  // Render pagination based on current state
  function renderPagination(currentPage, totalPages, categoryId, search) {
    const paginationWrapper = document.querySelector('.pagination-wrapper');
    if (!paginationWrapper) return;
    
    if (totalPages <= 1) {
      paginationWrapper.innerHTML = '';
      return;
    }
    
    // Build query parameters
    let queryParams = '';
    if (categoryId) {
      queryParams += `&category=${categoryId}`;
    }
    if (search) {
      queryParams += `&search=${encodeURIComponent(search)}`;
    }
    
    let html = '<div class="flex justify-center"><div class="flex space-x-1 pagination-container">';
    
    // Previous page
    if (currentPage > 1) {
      html += `
        <a href="?page=${currentPage - 1}${queryParams}" 
           class="pagination-link px-4 py-2 bg-white border border-gray-300 rounded-md hover:bg-gray-50"
           data-page="${currentPage - 1}">
          Previous
        </a>
      `;
    }
    
    // Page numbers
    const start = Math.max(1, currentPage - 2);
    const end = Math.min(totalPages, start + 4);
    const adjustedStart = Math.max(1, end - 4);
    
    for (let i = adjustedStart; i <= end; i++) {
      const isActive = i === currentPage;
      html += `
        <a href="?page=${i}${queryParams}" 
           class="pagination-link px-4 py-2 ${isActive ? 'bg-accent-teal text-white' : 'bg-white text-gray-700 hover:bg-gray-50'} border border-gray-300 rounded-md"
           data-page="${i}">
          ${i}
        </a>
      `;
    }
    
    // Next page
    if (currentPage < totalPages) {
      html += `
        <a href="?page=${currentPage + 1}${queryParams}" 
           class="pagination-link px-4 py-2 bg-white border border-gray-300 rounded-md hover:bg-gray-50"
           data-page="${currentPage + 1}">
          Next
        </a>
      `;
    }
    
    html += '</div></div>';
    paginationWrapper.innerHTML = html;
    
    // Re-initialize pagination links
    initPagination();
  }
  
  // Helper function to generate loading HTML
  function getLoadingHTML() {
    return `
      <div class="col-span-3 p-12">
        <div class="flex items-center justify-center">
          <svg class="animate-spin -ml-1 mr-3 h-10 w-10 text-accent-teal" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
          </svg>
          <span class="text-lg text-accent-navy">Loading products...</span>
        </div>
      </div>
    `;
  }