<?php
/* 
 * File: views/vendor/orders/view.php
 * Order Detail View for YOU/Market Vendor Dashboard
 */
?>

<div class="mb-6">
    <a href="/vendor/orders" class="inline-flex items-center text-accent-teal hover:text-accent-navy transition">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        Back to Orders
    </a>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <!-- Left Column: Order Information -->
    <div class="md:col-span-2 space-y-6">
        <!-- Order Header -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4">
                <div>
                    <h1 class="text-2xl font-bold text-accent-navy">Order #<?= $order['id'] ?></h1>
                    <p class="text-gray-500 mt-1">Placed on <?= date('F j, Y', strtotime($order['created_at'])) ?> at <?= date('g:i A', strtotime($order['created_at'])) ?></p>
                </div>
                
                <div class="mt-4 sm:mt-0">
                    <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full 
                        <?php
                            switch ($order['status']) {
                                case 'paid':
                                case 'completed':
                                    echo 'bg-green-100 text-green-800';
                                    break;
                                case 'pending':
                                    echo 'bg-yellow-100 text-yellow-800';
                                    break;
                                case 'processing':
                                    echo 'bg-blue-100 text-blue-800';
                                    break;
                                case 'shipped':
                                    echo 'bg-indigo-100 text-indigo-800';
                                    break;
                                case 'cancelled':
                                    echo 'bg-red-100 text-red-800';
                                    break;
                                default:
                                    echo 'bg-gray-100 text-gray-800';
                            }
                        ?>">
                        <?= ucfirst($order['status']) ?>
                    </span>
                </div>
            </div>
            
            <!-- Order Timeline -->
            <div class="mt-6 border-t border-gray-200 pt-4">
                <h3 class="text-lg font-medium text-accent-navy mb-3">Order Timeline</h3>
                <div class="relative pb-8">
                    <div class="absolute left-0 top-0 ml-2 h-full w-0.5 bg-gray-200"></div>
                    
                    <!-- Order Created Event -->
                    <div class="relative flex items-start mb-4">
                        <div class="flex-shrink-0">
                            <div class="h-4 w-4 rounded-full border-2 border-accent-ochre bg-white"></div>
                        </div>
                        <div class="ml-4">
                            <div class="flex items-center">
                                <h4 class="text-sm font-medium text-gray-900">Order Placed</h4>
                                <span class="ml-2 text-xs text-gray-500"><?= date('M j, g:i A', strtotime($order['created_at'])) ?></span>
                            </div>
                            <p class="text-sm text-gray-500">Customer placed the order</p>
                        </div>
                    </div>
                    
                    <!-- Payment Confirmed Event (if applicable) -->
                    <?php if ($order['status'] !== 'pending'): ?>
                    <div class="relative flex items-start mb-4">
                        <div class="flex-shrink-0">
                            <div class="h-4 w-4 rounded-full border-2 border-accent-ochre bg-white"></div>
                        </div>
                        <div class="ml-4">
                            <div class="flex items-center">
                                <h4 class="text-sm font-medium text-gray-900">Payment Confirmed</h4>
                                <span class="ml-2 text-xs text-gray-500"><?= date('M j, g:i A', strtotime($order['updated_at'])) ?></span>
                            </div>
                            <p class="text-sm text-gray-500">Payment was successfully processed</p>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <!-- Additional Timeline Events Based on Status -->
                    <?php if (in_array($order['status'], ['processing', 'shipped', 'completed'])): ?>
                    <div class="relative flex items-start mb-4">
                        <div class="flex-shrink-0">
                            <div class="h-4 w-4 rounded-full border-2 border-accent-teal bg-white"></div>
                        </div>
                        <div class="ml-4">
                            <div class="flex items-center">
                                <h4 class="text-sm font-medium text-gray-900">Processing Started</h4>
                                <span class="ml-2 text-xs text-gray-500">
                                    <!-- In a real implementation, you would fetch the actual timestamp -->
                                    <?= date('M j', strtotime('+1 day', strtotime($order['created_at']))) ?>
                                </span>
                            </div>
                            <p class="text-sm text-gray-500">Order is being prepared for shipping</p>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (in_array($order['status'], ['shipped', 'completed'])): ?>
                    <div class="relative flex items-start mb-4">
                        <div class="flex-shrink-0">
                            <div class="h-4 w-4 rounded-full border-2 border-accent-teal bg-white"></div>
                        </div>
                        <div class="ml-4">
                            <div class="flex items-center">
                                <h4 class="text-sm font-medium text-gray-900">Shipped</h4>
                                <span class="ml-2 text-xs text-gray-500">
                                    <!-- In a real implementation, you would fetch the actual timestamp -->
                                    <?= date('M j', strtotime('+2 days', strtotime($order['created_at']))) ?>
                                </span>
                            </div>
                            <p class="text-sm text-gray-500">
                                Order has been shipped
                                <?php if (!empty($order['tracking_number'])): ?>
                                    with tracking number: <span class="font-medium"><?= $order['tracking_number'] ?></span>
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <?php if ($order['status'] === 'completed'): ?>
                    <div class="relative flex items-start">
                        <div class="flex-shrink-0">
                            <div class="h-4 w-4 rounded-full border-2 border-green-500 bg-white"></div>
                        </div>
                        <div class="ml-4">
                            <div class="flex items-center">
                                <h4 class="text-sm font-medium text-gray-900">Delivered</h4>
                                <span class="ml-2 text-xs text-gray-500">
                                    <!-- In a real implementation, you would fetch the actual timestamp -->
                                    <?= date('M j', strtotime('+5 days', strtotime($order['created_at']))) ?>
                                </span>
                            </div>
                            <p class="text-sm text-gray-500">Order has been delivered to the customer</p>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <!-- Order Items -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h2 class="text-lg font-semibold text-accent-navy mb-4">Order Items</h2>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Product
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Price
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Quantity
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Total
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($items as $item): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10 bg-gray-100 rounded-md overflow-hidden">
                                            <?php if (!empty($item['product']['image_path'])): ?>
                                                <img src="<?= htmlspecialchars($item['product']['image_path']) ?>" alt="<?= htmlspecialchars($item['product']['name']) ?>" class="h-10 w-10 object-cover">
                                            <?php else: ?>
                                                <div class="h-10 w-10 flex items-center justify-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                    </svg>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">
                                                <?= htmlspecialchars($item['product']['name']) ?>
                                            </div>
                                            <div class="text-xs text-gray-500">
                                                SKU: <?= htmlspecialchars($item['product']['id']) ?>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?= number_format($item['price'], 2) ?> MAD
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?= $item['quantity'] ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    <?= number_format($item['price'] * $item['quantity'], 2) ?> MAD
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr class="bg-gray-50">
                            <td colspan="3" class="px-6 py-4 text-sm font-medium text-gray-900 text-right">
                                Subtotal
                            </td>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                <?= number_format($orderTotal, 2) ?> MAD
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        
        <!-- Order Notes -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h2 class="text-lg font-semibold text-accent-navy mb-4">Order Notes</h2>
            
            <div id="notesContainer">
                <!-- This section would display existing notes from the database -->
                <div class="text-gray-500 italic mb-4 text-sm">No notes added yet.</div>
            </div>
            
            <form id="addNoteForm" class="mt-4">
                <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                <label for="noteText" class="block text-sm font-medium text-gray-700 mb-1">Add Note</label>
                <textarea id="noteText" name="note" rows="2" class="w-full rounded-md border-gray-300 shadow-sm focus:border-accent-teal focus:ring-accent-teal mb-2"></textarea>
                <div class="flex justify-end">
                    <button type="submit" class="bg-accent-teal hover:bg-accent-ceramicblue text-white py-2 px-4 rounded-md transition-colors duration-200 text-sm">
                        Add Note
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Right Column: Order Actions and Customer Info -->
    <div class="space-y-6">
        <!-- Order Status Update -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h2 class="text-lg font-semibold text-accent-navy mb-4">Update Order Status</h2>
            
            <form id="updateStatusForm">
                <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                
                <div class="mb-4">
                    <label for="newStatus" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select id="newStatus" name="status" class="w-full rounded-md border-gray-300 shadow-sm focus:border-accent-teal focus:ring-accent-teal">
                        <option value="pending" <?= $order['status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                        <option value="processing" <?= $order['status'] === 'processing' ? 'selected' : '' ?>>Processing</option>
                        <option value="shipped" <?= $order['status'] === 'shipped' ? 'selected' : '' ?>>Shipped</option>
                        <option value="completed" <?= $order['status'] === 'completed' ? 'selected' : '' ?>>Completed</option>
                        <option value="cancelled" <?= $order['status'] === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                    </select>
                </div>
                
                <div id="shippingFields" class="mb-4 <?= $order['status'] === 'shipped' ? '' : 'hidden' ?>">
                    <label for="trackingNumber" class="block text-sm font-medium text-gray-700 mb-1">Tracking Number</label>
                    <input type="text" id="trackingNumber" name="tracking_number" value="<?= $order['tracking_number'] ?? '' ?>" class="w-full rounded-md border-gray-300 shadow-sm focus:border-accent-teal focus:ring-accent-teal">
                    
                    <div class="mt-3">
                        <label for="carrier" class="block text-sm font-medium text-gray-700 mb-1">Shipping Carrier</label>
                        <select id="carrier" name="carrier" class="w-full rounded-md border-gray-300 shadow-sm focus:border-accent-teal focus:ring-accent-teal">
                            <option value="">Select Carrier</option>
                            <option value="dhl" <?= ($order['carrier'] ?? '') === 'dhl' ? 'selected' : '' ?>>DHL</option>
                            <option value="fedex" <?= ($order['carrier'] ?? '') === 'fedex' ? 'selected' : '' ?>>FedEx</option>
                            <option value="ups" <?= ($order['carrier'] ?? '') === 'ups' ? 'selected' : '' ?>>UPS</option>
                            <option value="usps" <?= ($order['carrier'] ?? '') === 'usps' ? 'selected' : '' ?>>USPS</option>
                            <option value="other" <?= ($order['carrier'] ?? '') === 'other' ? 'selected' : '' ?>>Other</option>
                        </select>
                    </div>
                </div>
                
                <div class="mb-4">
                    <label for="statusNote" class="block text-sm font-medium text-gray-700 mb-1">Note (Optional)</label>
                    <textarea id="statusNote" name="note" rows="2" class="w-full rounded-md border-gray-300 shadow-sm focus:border-accent-teal focus:ring-accent-teal"></textarea>
                </div>
                
                <button type="submit" class="w-full bg-accent-ochre hover:bg-accent-terracotta text-white py-2 px-4 rounded-md transition-colors duration-200">
                    Update Order
                </button>
            </form>
        </div>
        
        <!-- Customer Information -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h2 class="text-lg font-semibold text-accent-navy mb-4">Customer Information</h2>
            
            <div class="mb-4">
                <h3 class="text-sm font-medium text-gray-700 mb-1">Customer</h3>
                <p class="text-sm"><?= htmlspecialchars($customer['firstname'] . ' ' . $customer['lastname']) ?></p>
                <p class="text-sm text-gray-500"><?= htmlspecialchars($customer['email']) ?></p>
            </div>
            
            <div>
                <h3 class="text-sm font-medium text-gray-700 mb-1">Shipping Address</h3>
                <p class="text-sm whitespace-pre-line"><?= nl2br(htmlspecialchars($order['shipping_address'])) ?></p>
            </div>
        </div>
        
        <!-- Download Options -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h2 class="text-lg font-semibold text-accent-navy mb-4">Documents</h2>
            
            <div class="space-y-3">
                <a href="#" class="flex items-center text-accent-teal hover:text-accent-ceramicblue transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                    </svg>
                    Download Invoice
                </a>
                
                <a href="#" class="flex items-center text-accent-teal hover:text-accent-ceramicblue transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    Print Packing Slip
                </a>
                
                <a href="#" class="flex items-center text-accent-teal hover:text-accent-ceramicblue transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                    Generate Shipping Label
                </a>
            </div>
        </div>
    </div>
</div>

<script>// Improved JavaScript for views/seller/orders/view.php - Updates DOM without page refresh
document.addEventListener('DOMContentLoaded', function() {
    // Show/hide shipping fields based on status
    const newStatusSelect = document.getElementById('newStatus');
    const shippingFields = document.getElementById('shippingFields');
    
    newStatusSelect.addEventListener('change', function() {
        if (this.value === 'shipped') {
            shippingFields.classList.remove('hidden');
        } else {
            shippingFields.classList.add('hidden');
        }
    });
    
    // Update order status form submission
    const updateStatusForm = document.getElementById('updateStatusForm');
    
    updateStatusForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(updateStatusForm);
        const formDataObj = {};
        
        formData.forEach((value, key) => {
            formDataObj[key] = value;
        });
        
        // Send AJAX request to update status
        fetch('/vendor/orders/update-status', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-AJAX-REQUEST': 'true'
            },
            body: JSON.stringify(formDataObj)
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(data => {
                    throw new Error(data.message || 'Server returned an error');
                });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Instead of reloading the page, update the DOM
                updateStatusDisplay(formDataObj.status);
                
                // Show success message
                showToast('Order status updated successfully', 'success');
            } else {
                showToast('Failed to update order status: ' + data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('An error occurred while updating order status: ' + error.message, 'error');
        });
    });
    
    // Add note form submission
    const addNoteForm = document.getElementById('addNoteForm');
    const notesContainer = document.getElementById('notesContainer');
    
    addNoteForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(addNoteForm);
        const formDataObj = {};
        
        formData.forEach((value, key) => {
            formDataObj[key] = value;
        });
        
        // Send AJAX request to add note
        fetch('/vendor/orders/add-note', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-AJAX-REQUEST': 'true'
            },
            body: JSON.stringify(formDataObj)
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(data => {
                    throw new Error(data.message || 'Server returned an error');
                });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Clear the note field
                document.getElementById('noteText').value = '';
                
                // Update notes section with the new note
                addNoteToContainer(data.note);
                
                // Show success message
                showToast('Note added successfully', 'success');
            } else {
                showToast('Failed to add note: ' + data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('An error occurred while adding the note: ' + error.message, 'error');
        });
    });
    
    // Function to update the status display in the UI
    function updateStatusDisplay(newStatus) {
        // Update the status pill/badge
        const statusSpan = document.querySelector('.rounded-full');
        if (statusSpan) {
            // Remove existing status classes
            statusSpan.classList.remove(
                'bg-green-100', 'text-green-800',
                'bg-yellow-100', 'text-yellow-800',
                'bg-blue-100', 'text-blue-800',
                'bg-indigo-100', 'text-indigo-800',
                'bg-red-100', 'text-red-800',
                'bg-gray-100', 'text-gray-800'
            );
            
            // Add appropriate class based on new status
            switch (newStatus) {
                case 'completed':
                case 'paid':
                    statusSpan.classList.add('bg-green-100', 'text-green-800');
                    break;
                case 'pending':
                    statusSpan.classList.add('bg-yellow-100', 'text-yellow-800');
                    break;
                case 'processing':
                    statusSpan.classList.add('bg-blue-100', 'text-blue-800');
                    break;
                case 'shipped':
                    statusSpan.classList.add('bg-indigo-100', 'text-indigo-800');
                    break;
                case 'cancelled':
                    statusSpan.classList.add('bg-red-100', 'text-red-800');
                    break;
                default:
                    statusSpan.classList.add('bg-gray-100', 'text-gray-800');
            }
            
            // Update text
            statusSpan.textContent = newStatus.charAt(0).toUpperCase() + newStatus.slice(1);
        }
        
        // Update the timeline if it exists
        updateTimeline(newStatus);
    }
    
    // Function to update the order timeline based on status
    function updateTimeline(newStatus) {
        // This would need to be customized based on your timeline structure
        // For now, we'll just update the status circles
        const timelineSteps = document.querySelectorAll('.relative.flex.items-start');
        
        if (timelineSteps.length > 0) {
            // Reset all steps
            timelineSteps.forEach(step => {
                const circle = step.querySelector('.h-4.w-4');
                if (circle) {
                    // Reset to default styling
                    circle.classList.remove('border-green-500', 'bg-green-500');
                    circle.classList.add('border-2', 'border-accent-ochre', 'bg-white');
                }
                
                // Remove current marker
                const textElements = step.querySelectorAll('.text-sm');
                textElements.forEach(text => {
                    if (text.textContent.includes('current')) {
                        text.textContent = text.textContent.replace(' (current)', '');
                    }
                });
            });
            
            // Update based on new status
            // This is simplified logic - you would need to adjust based on your actual timeline structure
            let stepToUpdate = null;
            
            switch (newStatus) {
                case 'pending':
                    stepToUpdate = timelineSteps[0]; // First step
                    break;
                case 'processing':
                    stepToUpdate = timelineSteps.length > 1 ? timelineSteps[1] : null;
                    break;
                case 'shipped':
                    stepToUpdate = timelineSteps.length > 2 ? timelineSteps[2] : null;
                    break;
                case 'completed':
                    stepToUpdate = timelineSteps.length > 3 ? timelineSteps[3] : null;
                    break;
                case 'cancelled':
                    // Special case for cancelled
                    stepToUpdate = timelineSteps[0]; // Just mark the first step
                    const title = stepToUpdate.querySelector('h4');
                    if (title) {
                        title.textContent = 'Cancelled';
                    }
                    break;
            }
            
            if (stepToUpdate) {
                const circle = stepToUpdate.querySelector('.h-4.w-4');
                if (circle) {
                    circle.classList.remove('border-2', 'border-accent-ochre', 'bg-white');
                    circle.classList.add('border-green-500', 'bg-green-500');
                }
                
                // Mark as current
                const title = stepToUpdate.querySelector('h4');
                if (title) {
                    title.textContent = title.textContent + ' (current)';
                }
            }
        }
    }
    
    // Function to add a note to the notes container
    function addNoteToContainer(noteData) {
        // Clear the "no notes" message if it exists
        if (notesContainer.innerHTML.includes('No notes added yet')) {
            notesContainer.innerHTML = '';
        }
        
        // Create and append the new note
        const noteElement = document.createElement('div');
        noteElement.className = 'p-4 border-b border-gray-200';
        noteElement.innerHTML = `
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm">${noteData.note}</p>
                    <span class="text-xs text-gray-500">${noteData.created_at}</span>
                </div>
            </div>
        `;
        
        notesContainer.appendChild(noteElement);
    }
    
    // Create toast notification function
    function showToast(message, type = 'success') {
        // Create toast container if it doesn't exist
        let toastContainer = document.getElementById('toast-container');
        if (!toastContainer) {
            toastContainer = document.createElement('div');
            toastContainer.id = 'toast-container';
            toastContainer.className = 'fixed top-4 right-4 z-50';
            document.body.appendChild(toastContainer);
        }
        
        // Create toast element
        const toast = document.createElement('div');
        toast.className = `p-4 rounded-md mb-2 transition-all duration-300 transform translate-x-full opacity-0 ${
            type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
        }`;
        toast.innerHTML = `
            <div class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    ${type === 'success' 
                        ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />' 
                        : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />'}
                </svg>
                ${message}
            </div>
        `;
        
        // Add to DOM
        toastContainer.appendChild(toast);
        
        // Animate in
        setTimeout(() => {
            toast.classList.remove('translate-x-full', 'opacity-0');
        }, 10);
        
        // Remove after delay
        setTimeout(() => {
            toast.classList.add('translate-x-full', 'opacity-0');
            setTimeout(() => {
                toast.remove();
            }, 300);
        }, 3000);
    }
});
</script>