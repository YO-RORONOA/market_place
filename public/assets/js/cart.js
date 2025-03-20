/**
 * YOU/Market Shopping Cart Functionality
 * Handles dynamic cart operations without page reloads
 */

document.addEventListener('DOMContentLoaded', function() {
    console.log('Cart.js loaded');
    
    // Handle cart quantity updates
    initCartQuantityButtons();
    
    // Handle cart item removal
    initCartRemoveButtons();
    
    // Handle clear cart button
    initClearCartButton();
    
    // Handle "Add to Cart" form submissions
    initAddToCartForms();
});

// Initialize add to cart functionality
function initAddToCartForms() {
    const addToCartForms = document.querySelectorAll('.add-to-cart-form');
    console.log('Add to cart forms found:', addToCartForms.length);
    
    addToCartForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const productId = this.querySelector('[name="product_id"]').value;
            const quantity = this.querySelector('[name="quantity"]').value;
            
            console.log('Adding product:', productId, 'quantity:', quantity);
            
            // Use XMLHttpRequest for compatibility
            const xhr = new XMLHttpRequest();
            xhr.open('POST', '/cart/add', true);
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
            
            xhr.onload = function() {
                if (xhr.status === 200) {
                    try {
                        const response = JSON.parse(xhr.responseText);
                        console.log('Response:', response);
                        
                        if (response && response.success) {
                            // Update cart counter
                            updateCartCounter(response.cartCount);
                            
                            // Temporarily change the button text/style
                            const button = form.querySelector('button');
                            const originalText = button.textContent.trim();
                            button.textContent = 'Added ✓';
                            button.classList.remove('bg-accent-ochre');
                            button.classList.add('bg-green-600');
                            
                            // Show success notification
                            if (typeof showNotification === 'function') {
                                showNotification(response.message);
                            } else {
                                alert(response.message);
                            }
                            
                            // Revert button back after delay
                            setTimeout(() => {
                                button.textContent = originalText;
                                button.classList.remove('bg-green-600');
                                button.classList.add('bg-accent-ochre');
                            }, 1500);
                        } else {
                            if (typeof showNotification === 'function') {
                                showNotification(response.message || 'Error adding to cart', 'error');
                            } else {
                                alert(response.message || 'Error adding to cart');
                            }
                        }
                    } catch (e) {
                        console.error('Error parsing response:', e);
                        alert('Error processing response');
                    }
                }
            };
            
            // Create FormData
            const formData = new FormData();
            formData.append('product_id', productId);
            formData.append('quantity', quantity);
            
            // Send request
            xhr.send(formData);
        });
    });
}

// Initialize cart quantity update functionality
function initCartQuantityButtons() {
    const quantityButtons = document.querySelectorAll('form[action="/cart/update"] button');
    console.log('Quantity buttons found:', quantityButtons.length);
    
    quantityButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
    
            const form = this.closest('form');
            const productId = form.querySelector('[name="product_id"]').value;
            const quantityDisplay = form.querySelector('span');
            const currentQuantity = parseInt(quantityDisplay.textContent);
            
            // new quantity based on which button was clicked
            let newQuantity;
            if (this.textContent.trim() === '+') {
                newQuantity = currentQuantity + 1;
            } else {
                newQuantity = Math.max(1, currentQuantity - 1);
            }
            
            console.log('Updating quantity from', currentQuantity, 'to', newQuantity);
            
            const xhr = new XMLHttpRequest();
            xhr.open('POST', '/cart/update', true);
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
            
            xhr.onload = function() {
                if (xhr.status === 200) {
                    try {
                        const response = JSON.parse(xhr.responseText);
                        console.log('Response:', response);
                        
                        if (response && response.success) {
                            // Update the quantity display
                            quantityDisplay.textContent = response.quantity;
                            
                            // IMPORTANT: Update button values for next click
                            const decreaseBtn = form.querySelector('.decrease-btn') || form.querySelector('button:first-child');
                            const increaseBtn = form.querySelector('.increase-btn') || form.querySelector('button:last-child');
                            
                            if (decreaseBtn) {
                                decreaseBtn.value = Math.max(1, response.quantity - 1);
                            }
                            
                            if (increaseBtn) {
                                increaseBtn.value = response.quantity + 1;
                            }
                            
                            // Update item total
                            const itemRow = form.closest('tr');
                            const itemTotal = itemRow.querySelector('td:nth-child(4)');
                            if (itemTotal) {
                                itemTotal.textContent = formatPrice(response.itemTotal);
                                itemTotal.classList.add('bg-yellow-100');
                                setTimeout(() => {
                                    itemTotal.classList.remove('bg-yellow-100');
                                }, 500);
                            }
                            
                            // Update cart totals
                            updateCartTotals(response.cartTotal);
                            
                            // Update cart counter in header
                            updateCartCounter(response.cartCount);
                            
                            // Show success notification
                            if (typeof showNotification === 'function') {
                                showNotification('Cart updated');
                            }
                        } else {
                            // Show error
                            if (typeof showNotification === 'function') {
                                showNotification(response?.message || 'Error updating cart', 'error');
                            } else {
                                alert(response?.message || 'Error updating cart');
                            }
                        }
                    } catch (e) {
                        console.error('Error parsing response:', e);
                        alert('Error processing response');
                    }
                }
            };
            
            // Create FormData
            const formData = new FormData();
            formData.append('product_id', productId);
            formData.append('quantity', newQuantity);  // Send the calculated new quantity
            
            // Send request
            xhr.send(formData);
        });
    });
}

// Initialize cart item removal functionality
function initCartRemoveButtons() {
    const removeLinks = document.querySelectorAll('a[href^="/cart/remove"]');
    console.log('Remove links found:', removeLinks.length);
    
    removeLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            
            const url = new URL(this.href);
            const productId = url.searchParams.get('id');
            const itemRow = this.closest('tr');
            
            console.log('Removing product:', productId);
            
            // Show loading state
            itemRow.style.opacity = '0.5';
            
            const xhr = new XMLHttpRequest();
            xhr.open('GET', `/cart/remove?id=${productId}`, true);
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
            
            xhr.onload = function() {
                if (xhr.status === 200) {
                    try {
                        const response = JSON.parse(xhr.responseText);
                        console.log('Response:', response);
                        
                        if (response && response.success) {
                            // Animate removing the row
                            itemRow.style.height = itemRow.offsetHeight + 'px';
                            itemRow.classList.add('overflow-hidden');
                            
                            setTimeout(() => {
                                itemRow.style.height = '0';
                                itemRow.style.padding = '0';
                                itemRow.style.margin = '0';
                                
                                setTimeout(() => {
                                    itemRow.remove();
                                    
                                    // If cart is now empty, refresh the page to show empty cart message
                                    if (response.cartCount === 0) {
                                        window.location.reload();
                                        return;
                                    }
                                    
                                    // Update cart totals
                                    updateCartTotals(response.cartTotal);
                                    
                                    // Update cart counter in header
                                    updateCartCounter(response.cartCount);
                                    
                                    // Show notification
                                    if (typeof showNotification === 'function') {
                                        showNotification(response.message);
                                    }
                                }, 300);
                            }, 10);
                        } else {
                            // Restore opacity on error
                            itemRow.style.opacity = '1';
                            if (typeof showNotification === 'function') {
                                showNotification(response?.message || 'Error removing item', 'error');
                            } else {
                                alert(response?.message || 'Error removing item');
                            }
                        }
                    } catch (e) {
                        console.error('Error parsing response:', e);
                        itemRow.style.opacity = '1';
                        alert('Error processing response');
                    }
                }
            };
            
            // Send request
            xhr.send();
        });
    });
}

// Initialize clear cart button
function initClearCartButton() {
    const clearCartLink = document.querySelector('a[href="/cart/clear"]');
    console.log('Clear cart link found:', clearCartLink ? 'Yes' : 'No');
    
    if (!clearCartLink) return;
    
    clearCartLink.addEventListener('click', function(e) {
        e.preventDefault();
        
        if (!confirm('Are you sure you want to clear your cart?')) {
            return;
        }
        
        console.log('Clearing cart');
        
        // Show loading state
        const cartContainer = document.querySelector('table');
        if (cartContainer) {
            cartContainer.style.opacity = '0.5';
        }
        
        const xhr = new XMLHttpRequest();
        xhr.open('GET', '/cart/clear', true);
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        
        xhr.onload = function() {
            if (xhr.status === 200) {
                try {
                    const response = JSON.parse(xhr.responseText);
                    console.log('Response:', response);
                    
                    if (response && response.success) {
                        // Reload the page to show empty cart
                        window.location.reload();
                    } else {
                        // Restore opacity on error
                        if (cartContainer) {
                            cartContainer.style.opacity = '1';
                        }
                        if (typeof showNotification === 'function') {
                            showNotification(response?.message || 'Error clearing cart', 'error');
                        } else {
                            alert(response?.message || 'Error clearing cart');
                        }
                    }
                } catch (e) {
                    console.error('Error parsing response:', e);
                    if (cartContainer) {
                        cartContainer.style.opacity = '1';
                    }
                    alert('Error processing response');
                }
            }
        };
        
        // Send request
        xhr.send();
    });
}

// Format price with MAD currency
function formatPrice(price) {
    return parseFloat(price).toFixed(2) + ' MAD';
}

// Update the cart counter in the header
function updateCartCounter(count) {
    console.log('Updating cart counter to:', count);
    
    const cartCounterContainer = document.querySelector('header a[href="/cart"]');
    if (!cartCounterContainer) return;
    
    // If there's already a counter, update it
    let cartCounter = cartCounterContainer.querySelector('span');
    
    if (!cartCounter && count > 0) {
        // Create counter if it doesn't exist
        cartCounter = document.createElement('span');
        cartCounter.className = 'absolute -top-2 -right-2 bg-accent-terracotta text-white text-xs rounded-full h-5 w-5 flex items-center justify-center';
        cartCounterContainer.style.position = 'relative';
        cartCounterContainer.appendChild(cartCounter);
    }
    
    if (cartCounter) {
        if (count > 0) {
            cartCounter.textContent = count;
            cartCounter.classList.remove('hidden');
        } else {
            cartCounter.classList.add('hidden');
        }
    }
}

// Update all cart totals displayed on page
function updateCartTotals(total) {
    console.log('Updating cart totals to:', total);
    
    // Update all elements with cart-total class
    document.querySelectorAll('.cart-total, .cart-subtotal').forEach(element => {
        element.textContent = formatPrice(total);
        
        // Add highlight effect
        element.classList.add('bg-yellow-100');
        setTimeout(() => {
            element.classList.remove('bg-yellow-100');
        }, 500);
    });
    
    // Also try to update based on position
    const subtotalElement = document.querySelector('.flex.justify-between:nth-child(1) span:nth-child(2)');
    const totalElement = document.querySelector('.flex.justify-between:nth-child(3) span:nth-child(2)');
    
    if (subtotalElement) {
        subtotalElement.textContent = formatPrice(total);
        subtotalElement.classList.add('bg-yellow-100');
        setTimeout(() => subtotalElement.classList.remove('bg-yellow-100'), 500);
    }
    
    if (totalElement) {
        totalElement.textContent = formatPrice(total);
        totalElement.classList.add('bg-yellow-100');
        setTimeout(() => totalElement.classList.remove('bg-yellow-100'), 500);
    }
}

// Show notification if not already defined
if (typeof showNotification !== 'function') {
    function showNotification(message, type = 'success', duration = 3000) {
        // Create notification container if it doesn't exist
        let notificationContainer = document.getElementById('notification-container');
        
        if (!notificationContainer) {
            notificationContainer = document.createElement('div');
            notificationContainer.id = 'notification-container';
            notificationContainer.className = 'fixed top-16 right-4 z-50 max-w-md';
            document.body.appendChild(notificationContainer);
        }
        
        // Create notification
        const notification = document.createElement('div');
        notification.className = `p-4 mb-4 rounded-md shadow-md ${
            type === 'success' ? 'bg-green-100 border-l-4 border-green-500 text-green-700' : 
            type === 'error' ? 'bg-red-100 border-l-4 border-red-500 text-red-700' : 
            'bg-blue-100 border-l-4 border-blue-500 text-blue-700'
        }`;
        
        notification.innerHTML = `
            <div class="flex items-center justify-between">
                <p>${message}</p>
                <button class="ml-4 text-gray-500 hover:text-gray-700" onclick="this.parentElement.parentElement.remove()">
                    &times;
                </button>
            </div>
        `;
        
        // Add to container
        notificationContainer.appendChild(notification);
        
        // Auto-remove after duration
        setTimeout(() => {
            notification.remove();
        }, duration);
    }
}