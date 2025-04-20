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
    try {
      const response = await fetchProducts(url.pathname + url.search);
      
      if (response && response.success) {
        // Update products and UI components
        updateProductsUI(response);
      } else {
        // Show error message
        productsContainer.innerHTML = getErrorHTML();
      }
    } catch (error) {
      console.error('Error fetching products:', error);
      productsContainer.innerHTML = getErrorHTML();
    }
  });
}

// Initialize pagination with AJAX
function initPagination() {
  // Need to use document here because pagination elements may be recreated dynamically
  document.addEventListener('click', async function(e) {
    const link = e.target.closest('.pagination-link');
    if (!link) return;
    
    e.preventDefault();
    
    const page = link.dataset.page;
    const url = new URL(window.location);
    url.searchParams.set('page', page);
    window.history.pushState({}, '', url);
    
    // Scroll to top of products
    const productsContainer = document.querySelector('.products-container');
    productsContainer.scrollIntoView({ behavior: 'smooth' });
    
    // Show loading state
    productsContainer.innerHTML = getLoadingHTML();
    
    // Fetch results
    try {
      const response = await fetchProducts(url.pathname + url.search);
      
      if (response && response.success) {
        // Update products and UI components
        updateProductsUI(response);
      } else {
        // Show error message
        productsContainer.innerHTML = getErrorHTML();
      }
    } catch (error) {
      console.error('Error fetching products:', error);
      productsContainer.innerHTML = getErrorHTML();
    }
  }, false);
}

// Initialize category filtering with AJAX
function initCategoryFiltering() {
  // Need to use document here for event delegation
  document.addEventListener('click', async function(e) {
    const link = e.target.closest('.category-link');
    if (!link) return;
    
    e.preventDefault();
    
    const categoryId = link.dataset.categoryId;
    const categoryName = link.textContent.trim();
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
      el.classList.remove('bg-accent-teal', 'text-white', 'font-medium');
      el.classList.add('text-gray-700', 'hover:bg-gray-50');
    });
    
    link.classList.add('bg-accent-teal', 'text-white', 'font-medium');
    link.classList.remove('text-gray-700', 'hover:bg-gray-50');
    
    // Scroll to top of products
    const productsContainer = document.querySelector('.products-container');
    productsContainer.scrollIntoView({ behavior: 'smooth' });
    
    // Show loading state
    productsContainer.innerHTML = getLoadingHTML();
    
    // Fetch results
    try {
      const response = await fetchProducts(url.pathname + url.search);
      
      if (response && response.success) {
        // Update products and UI components
        updateProductsUI(response);
        
        // Update page title and heading
        const heading = document.querySelector('h1');
        if (heading) {
          heading.textContent = categoryId ? categoryName : 'All Products';
          document.title = `YOU/Market - ${categoryId ? categoryName : 'All Products'}`;
        }
      } else {
        // Show error message
        productsContainer.innerHTML = getErrorHTML();
      }
    } catch (error) {
      console.error('Error fetching products:', error);
      productsContainer.innerHTML = getErrorHTML();
    }
  }, false);
}

// Central function to update all UI components after fetching products
function updateProductsUI(response) {
  const productsContainer = document.querySelector('.products-container');
  
  // Update products
  renderProducts(response.products, productsContainer);
  
  // Update pagination
  renderPagination(parseInt(response.currentPage), parseInt(response.totalPages), response.categoryId, response.search);
  
  // Update products count display
  const countDisplay = document.querySelector('.text-sm.text-gray-500');
  if (countDisplay && response.products && response.totalProducts) {
    countDisplay.textContent = `Showing ${response.products.length} of ${response.totalProducts} products`;
  }
  
  // Initialize add to cart forms
  if (typeof initAddToCartForms === 'function') {
    initAddToCartForms();
  }
}

// Fetch products from server
async function fetchProducts(url) {
  try {
    const response = await fetch(url, {
      headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'X-AJAX-Request': 'true'
      }
    });
    
    if (!response.ok) {
      throw new Error(`Server responded with status: ${response.status}`);
    }
    
    return await response.json();
  } catch (error) {
    console.error('Error fetching products:', error);
    if (typeof showNotification === 'function') {
      showNotification('Error loading products. Please try again.', 'error');
    }
    throw error;
  }
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
  
  products.forEach((product, index) => {
    // Clone the template
    const productCard = template.content.cloneNode(true);
    
    // Update all product links
    const productLinks = productCard.querySelectorAll('.product-link');
    productLinks.forEach(link => {
      link.href = `/products/view?id=${product.id}`;
    });
    
    // Update product image
    const productImage = productCard.querySelector('.product-image');
    const noImagePlaceholder = productCard.querySelector('.no-image-placeholder');
    
    if (product.image_path) {
      productImage.src = product.image_path;
      productImage.alt = product.name;
      productImage.style.display = 'block';
      if (noImagePlaceholder) {
        noImagePlaceholder.style.display = 'none';
      }
    } else {
      if (productImage) {
        productImage.style.display = 'none';
      }
      if (noImagePlaceholder) {
        noImagePlaceholder.style.display = 'flex';
      }
    }
    
    // Update product details
    const nameElement = productCard.querySelector('.product-name');
    if (nameElement) {
      nameElement.textContent = product.name;
    }
    
    const priceElement = productCard.querySelector('.product-price');
    if (priceElement) {
      priceElement.textContent = `${parseFloat(product.price).toFixed(2)} MAD`;
    }
    
    const descriptionElement = productCard.querySelector('.product-description');
    if (descriptionElement) {
      descriptionElement.textContent = product.description;
    }
    
    // Update product category badge if present
    const categoryBadge = productCard.querySelector('.category-badge');
    if (categoryBadge && product.category_name) {
      categoryBadge.textContent = product.category_name;
    }
    
    // Update form
    const productIdInput = productCard.querySelector('.product-id-input');
    if (productIdInput) {
      productIdInput.value = product.id;
    }
    
    // Update stock badge
    const stockBadge = productCard.querySelector('.stock-badge');
    if (stockBadge) {
      if (product.stock_quantity > 0) {
        stockBadge.textContent = 'In Stock';
        stockBadge.className = 'stock-badge px-2 py-0.5 text-xs bg-green-100 text-green-800 rounded-full';
      } else {
        stockBadge.textContent = 'Out of Stock';
        stockBadge.className = 'stock-badge px-2 py-0.5 text-xs bg-gray-100 text-gray-800 rounded-full';
      }
    }
    
    // Add to container
    container.appendChild(productCard);
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
  
  let html = '<div class="flex justify-center"><div class="flex flex-wrap gap-2 pagination-container">';
  
  // Previous page
  if (currentPage > 1) {
    html += `
      <a href="?page=${currentPage - 1}${queryParams}" 
         class="pagination-link flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md hover:bg-gray-50 shadow-sm"
         data-page="${currentPage - 1}">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
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
         class="pagination-link px-4 py-2 ${isActive ? 'bg-accent-teal text-white font-medium' : 'bg-white text-gray-700 hover:bg-gray-50'} border border-gray-300 rounded-md shadow-sm"
         data-page="${i}">
        ${i}
      </a>
    `;
  }
  
  // Next page
  if (currentPage < totalPages) {
    html += `
      <a href="?page=${currentPage + 1}${queryParams}" 
         class="pagination-link flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md hover:bg-gray-50 shadow-sm"
         data-page="${currentPage + 1}">
        Next
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
      </a>
    `;
  }
  
  html += '</div></div>';
  paginationWrapper.innerHTML = html;
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

// Helper function to generate error HTML
function getErrorHTML() {
  return `
    <div class="col-span-3 bg-white rounded-lg shadow-sm p-8 text-center">
      <p class="text-gray-600 mb-4">Error loading products. Please try again.</p>
      <a href="/products" class="text-accent-teal hover:underline">View all products</a>
    </div>
  `;
}

// Helper function for compatibility with other scripts that might use this
function fetchFromServer(url, options = {}) {
  const defaultOptions = {
    headers: {
      'Content-Type': 'application/json',
      'X-Requested-With': 'XMLHttpRequest'
    }
  };

  return fetch(url, { ...defaultOptions, ...options })
    .then(response => {
      if (!response.ok) {
        throw new Error(`Network response was not ok: ${response.status}`);
      }
      return response.json();
    })
    .catch(error => {
      console.error('Fetch error:', error);
      if (typeof showNotification === 'function') {
        showNotification('Error connecting to server. Please try again.', 'error');
      }
      throw error;
    });
}