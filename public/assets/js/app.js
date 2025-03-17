

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


    function showNotification(message, type = 'success', duration = 3000)
    {
        let notificationContainer = document.getElementById('notification-container');

        if(!notificationContainer)
        {
            notificationContainer = document.createElement('div');
            notificationContainer.id = 'notification-container';
            notificationContainer.className = 'fixed top-16 right-4 z-50 max-w-md';
            document.body.appendChild(notificationContainer);
        }

        const notification = document.createElement('div');
        notification.className =  `p-4 mb-4 rounded-md shadow-md transform transition-all duration-300 ease-in-out translate-x-full ${
            type === 'success' ? 'bg-green-100 border-l-4 border-green-500 text-green-700' : 
            type === 'error' ? 'bg-red-100 border-l-4 border-red-500 text-red-700' : 
            'bg-blue-100 border-l-4 border-blue-500 text-blue-700'
          }`;




    }