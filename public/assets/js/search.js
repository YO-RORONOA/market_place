/**
 * YOU/Market Search Functionality
 * Handles search forms in the main navigation and mobile menu
 */

document.addEventListener('DOMContentLoaded', function() {
    // Initialize desktop and mobile search functionality
    initSearchForms();
    initMobileSearchToggle();
});

/**
 * Initialize search forms with proper behavior
 */
function initSearchForms() {
    // Get all search forms
    const searchForms = document.querySelectorAll('.main-search-form');
    
    searchForms.forEach(form => {
        // Ensure form has correct action and method
        form.action = '/products';
        form.method = 'GET';
        
        // Get search input
        const searchInput = form.querySelector('input[type="text"]');
        if (searchInput) {
            // Ensure input has name="search"
            searchInput.name = 'search';
            
            // If URL has a search parameter, pre-fill the input
            const urlParams = new URLSearchParams(window.location.search);
            const searchParam = urlParams.get('search');
            if (searchParam) {
                searchInput.value = searchParam;
            }
            
            // Handle form submission validation
            form.addEventListener('submit', function(e) {
                if (!searchInput.value.trim()) {
                    e.preventDefault();
                    return false;
                }
            });
        }
        
        // Ensure submit button exists and works
        let submitButton = form.querySelector('button[type="submit"]');
        if (!submitButton) {
            // Create a submit button if it doesn't exist
            const inputContainer = form.querySelector('.relative');
            if (inputContainer) {
                submitButton = document.createElement('button');
                submitButton.type = 'submit';
                submitButton.className = 'absolute right-3 top-2 text-accent-teal hover:text-accent-navy focus:outline-none';
                submitButton.innerHTML = `
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                `;
                inputContainer.appendChild(submitButton);
            }
        }
    });
}

function initMobileSearchToggle() {
    const mobileSearchButton = document.querySelector('button.md\\:hidden');
    const mobileSearchContainer = document.getElementById('mobile-search');
    
    if (mobileSearchButton && mobileSearchContainer) {
        mobileSearchButton.addEventListener('click', function() {
            mobileSearchContainer.classList.toggle('hidden');

            if (!mobileSearchContainer.classList.contains('hidden')) {
                const searchInput = mobileSearchContainer.querySelector('input');
                if (searchInput) {
                    setTimeout(() => searchInput.focus(), 100);
                }
            }
        });
    }
}

