

document.addEventListener('DOMContentLoaded', function() {
    initializeMobileNav();
    initializeCollapsibleSections();
    initializeResponsiveActions();
    
    // Device detection
    const isMobile = window.innerWidth < 768;
    
    // Apply mobile-specific behavior
    if (isMobile) {
        optimizeForMobile();
    }
});


function initializeMobileNav() {
    // Mobile menu toggle
    const sidebarToggle = document.getElementById('sidebarToggle');
    const mobileSidebar = document.getElementById('mobileSidebar');
    const sidebarBackdrop = document.getElementById('sidebarBackdrop');
    const closeSidebar = document.getElementById('closeSidebar');
    
    if (sidebarToggle && mobileSidebar) {
        // Open sidebar when hamburger is clicked
        sidebarToggle.addEventListener('click', function() {
            mobileSidebar.classList.remove('-translate-x-full');
            document.body.classList.add('overflow-hidden'); // Prevent scrolling when sidebar is open
        });
        
        // Close sidebar functions
        const closeSidebarFunction = function() {
            mobileSidebar.classList.add('-translate-x-full');
            document.body.classList.remove('overflow-hidden');
        };
        
        if (closeSidebar) {
            closeSidebar.addEventListener('click', closeSidebarFunction);
        }
        
        if (sidebarBackdrop) {
            sidebarBackdrop.addEventListener('click', closeSidebarFunction);
        }
        
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && !mobileSidebar.classList.contains('-translate-x-full')) {
                closeSidebarFunction();
            }
        });
        
        const sidebarLinks = mobileSidebar.querySelectorAll('a');
        sidebarLinks.forEach(link => {
            link.addEventListener('click', closeSidebarFunction);
        });
    }
    
    highlightCurrentNavItem();
}


function initializeCollapsibleSections() {
    // Sections that can be collapsed on mobile
    const collapsibleSections = [
        { toggle: 'toggleOrders', content: 'ordersContent' },
        { toggle: 'toggleProducts', content: 'productsContent' }
    ];
    
    collapsibleSections.forEach(section => {
        const toggleBtn = document.getElementById(section.toggle);
        const contentEl = document.getElementById(section.content);
        
        if (toggleBtn && contentEl) {
            toggleBtn.addEventListener('click', function() {
                contentEl.classList.toggle('hidden');
                
                const svg = this.querySelector('svg');
                if (svg) {
                    const path = svg.querySelector('path');
                    if (path) {
                        const currentPathD = path.getAttribute('d');
                        
                        // Switch between down and up arrow paths
                        if (currentPathD.includes('M19 9l-7 7-7-7')) {
                            path.setAttribute('d', 'M5 15l7-7 7 7');
                        } else {
                            path.setAttribute('d', 'M19 9l-7 7-7-7');
                        }
                    }
                }
            });
        }
    });
}


function highlightCurrentNavItem() {
    const currentPath = window.location.pathname;
    const navLinks = document.querySelectorAll('nav a');
    
    navLinks.forEach(link => {
        const href = link.getAttribute('href');
        
        // Check if link matches current path or is a parent path
        if (currentPath === href || 
            (currentPath.startsWith(href) && href !== '/' && href.length > 1)) {
            
            link.classList.add('bg-gray-100', 'text-accent-navy', 'font-medium');
            link.classList.remove('hover:bg-gray-100');
            
            const icon = link.querySelector('svg');
            if (icon) {
                icon.classList.remove('text-gray-500');
                icon.classList.add('text-accent-teal');
            }
        }
    });
}


function initializeResponsiveActions() {
    // Handle window resize
    let resizeTimer;
    window.addEventListener('resize', function() {
        // Debounce the resize event
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(function() {
            const isMobile = window.innerWidth < 768;
            
            if (isMobile) {
                optimizeForMobile();
            } else {
                resetMobileOptimizations();
            }
        }, 250);
    });
}


function optimizeForMobile() {
    if (window.innerWidth < 480) {
        const sections = ['ordersContent', 'productsContent'];
        sections.forEach(id => {
            const element = document.getElementById(id);
            if (element && !element.classList.contains('hidden')) {
                element.classList.add('hidden');
            }
        });
    }
    
    const tables = document.querySelectorAll('table');
    tables.forEach(table => {
        if (!table.classList.contains('mobile-optimized')) {
            table.classList.add('mobile-optimized');
            
            const cells = table.querySelectorAll('td, th');
            cells.forEach(cell => {
                if (cell.classList.contains('px-6')) {
                    cell.classList.remove('px-6');
                    cell.classList.add('px-3');
                }
            });
        }
    });
}


function resetMobileOptimizations() {
    const sections = ['ordersContent', 'productsContent'];
    sections.forEach(id => {
        const element = document.getElementById(id);
        if (element && element.classList.contains('hidden')) {
            element.classList.remove('hidden');
        }
    });
    
    const tables = document.querySelectorAll('table.mobile-optimized');
    tables.forEach(table => {
        const cells = table.querySelectorAll('td, th');
        cells.forEach(cell => {
            if (cell.classList.contains('px-3')) {
                cell.classList.remove('px-3');
                cell.classList.add('px-6');
            }
        });
        
        table.classList.remove('mobile-optimized');
    });
}


function isTouchDevice() {
    return (('ontouchstart' in window) ||
            (navigator.maxTouchPoints > 0) ||
            (navigator.msMaxTouchPoints > 0));
}