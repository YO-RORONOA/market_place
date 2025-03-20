
console.log('test');
async function fetchFromServer(url, options = {})
{
    try{
        const defaultOptions = {
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        };

        const response = await fetch(url,{ ...defaultOptions, ...options});

        if(!response.ok)
        {
            throw new Error(`Network response was not ok: ${response.status}`);
        }

        return await response.json();
    } catch(error)
    {
        console.error('Fetch error:', error);
        showNotification('Error connecting to server. Please try again.', 'error');
        return null;
    }


    }


function showNotification(message, type = 'success', duration = 3000) {
    let notificationContainer = document.getElementById('notification-container');

    if (!notificationContainer) {
        notificationContainer = document.createElement('div');
        notificationContainer.id = 'notification-container';
        notificationContainer.className = 'fixed top-16 right-4 z-50 max-w-md';
        document.body.appendChild(notificationContainer);
    }

    const notification = document.createElement('div');
    notification.className = `p-4 mb-4 rounded-md shadow-md transform transition-all duration-300 ease-in-out translate-x-full ${type === 'success' ? 'bg-green-100 border-l-4 border-green-500 text-green-700' :
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

    //animate in
    notificationContainer.appendChild(notification);

    setTimeout(() => {
        notification.classList.remove('translate-x-full');
    }, 10);

    //remove after duartion ends
    setTimeout(() => {
        notification.classList.add('translate-x-full');
        notification.addEventListener('transitionend', () => {
            notification.remove();
        });
    }, duration);

    function updateCartCounter(count) {
        const cartCounter = document.querySelector('header a[href="/cart" span]');

        if (!cartCounter && count > 0) {
            const cartIcon = document.querySelector('header a[href="/cart"]');
            if (cartIcon) {
                const counter = document.createElement('span');
                counter.className = 'absolute -top-2 -right bg-accent-terracotta text-white text-xs rounded-full h-5 w-5 flex items-center justify-center';
                counter.textContent = count;
                cartIcon.style.position = 'relative';
                cartIcon.appendChild(counter);
            }
        } else if (cartCounter) {
            if (count > 0) {
                cartCounter.textContent = count;
                cartCounter.classList.remove('hidden');
            } else {
                cartCounter.classList.add('hidden');
            }
        }
    }

}