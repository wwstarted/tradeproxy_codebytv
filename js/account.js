// Get WordPress data from PHP
const wpData = window.wpAccountData || {};
const baseURL = wpData.baseUrl || window.location.origin;
const accountPageUrl = wpData.accountUrl || `${baseURL}/account`;
const ajaxUrl = wpData.ajaxUrl || `${baseURL}/wp-admin/admin-ajax.php`;
const nonce = wpData.nonce || '';

// Page slugs configuration
const pages = {
    'profile': 'profile',
    'change-password': 'change-password',
    'wallet': 'wallet',
    'membership': 'membership',
    'deposit-history': 'deposit-history',
    'purchase-history': 'purchase-history'
};

// Load page content via AJAX
async function loadPage(pageName) {
    const contentArea = document.getElementById('page-content');
    const pageSlug = pages[pageName];
    
    if (!pageSlug) {
        contentArea.innerHTML = '<div class="error">Trang không tồn tại</div>';
        return;
    }
    
    try {
        // Prepare form data
        const formData = new FormData();
        formData.append('action', 'load_account_page');
        formData.append('page_slug', pageSlug);
        formData.append('nonce', nonce);
        
        // Fetch page content via AJAX (không hiển thị loading)
        const response = await fetch(ajaxUrl, {
            method: 'POST',
            body: formData
        });
        
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        
        const data = await response.json();
        
        if (!data.success) {
            throw new Error(data.data || 'Failed to load content');
        }
        
        const html = data.data;
        
        // Update content with quick animation (0.1s total)
        contentArea.style.opacity = '0';
        
        setTimeout(() => {
            contentArea.innerHTML = html;
            
            // Execute scripts in loaded content
            const scripts = contentArea.querySelectorAll('script');
            scripts.forEach(oldScript => {
                const newScript = document.createElement('script');
                Array.from(oldScript.attributes).forEach(attr => {
                    newScript.setAttribute(attr.name, attr.value);
                });
                newScript.textContent = oldScript.textContent;
                oldScript.parentNode.replaceChild(newScript, oldScript);
            });

            // === GỌI HÀM KHỞI TẠO THEO TRANG ===
            if (typeof window.initProfilePage === 'function' && pageName === 'profile') {
                window.initProfilePage();
            }
            
            contentArea.style.opacity = '1';
        }, 50);
        
    } catch (error) {
        console.error('Error loading page:', error);
        contentArea.innerHTML = `
            <div class="error-message">
                <i class="fas fa-exclamation-circle"></i>
                <h3>Không thể tải trang</h3>
                <p>${error.message}</p>
            </div>
        `;
    }
}

// Update active menu item
function updateActiveMenu(pageName) {
    // Remove active class from all menu items
    document.querySelectorAll('.menu-item').forEach(item => {
        item.classList.remove('active');
    });
    
    // Add active class to current menu item
    const currentItem = document.querySelector(`[data-page="${pageName}"]`);
    if (currentItem) {
        currentItem.classList.add('active');
    }
}

// Handle menu click
function handleMenuClick(e) {
    e.preventDefault();
    
    const menuItem = e.currentTarget;
    const pageName = menuItem.dataset.page;
    
    if (!pageName) return;
    
    // FIX: Update URL to clean format (không có /account và #)
    const newUrl = `${baseURL}/${pageName}`;
    history.pushState({ page: pageName }, '', newUrl);
    
    // Load page content
    loadPage(pageName);
    
    // Update active menu
    updateActiveMenu(pageName);
}

// Handle browser back/forward
window.addEventListener('popstate', (e) => {
    const pageName = e.state?.page || getPageFromURL();
    loadPage(pageName);
    updateActiveMenu(pageName);
});

// Get page name from current URL
function getPageFromURL() {
    const path = window.location.pathname;
    const segments = path.split('/').filter(Boolean);
    const lastSegment = segments[segments.length - 1];
    
    // Check if last segment matches a page name
    if (pages[lastSegment]) {
        return lastSegment;
    }
    
    // If on account page, default to profile
    if (lastSegment === 'account' || !lastSegment) {
        return 'profile';
    }
    // Default to profile
    return 'profile';
}

// Initialize page
function init() {
    console.log('Account page initialized');
    console.log('Base URL:', baseURL);
    console.log('Account URL:', accountPageUrl);
    console.log('AJAX URL:', ajaxUrl);
    
    // Add click handlers to menu items
    document.querySelectorAll('.menu-item').forEach(item => {
        item.addEventListener('click', handleMenuClick);
    });
    
    // Get initial page from URL or default to profile
    const initialPage = getPageFromURL();
    
    // FIX: Set initial URL to clean format
    const initialUrl = `${baseURL}/${initialPage}`;
    history.replaceState({ page: initialPage }, '', initialUrl);
    
    // Load initial page
    loadPage(initialPage);
    updateActiveMenu(initialPage);
    
    // Add smooth transition
    const contentArea = document.getElementById('page-content');
    if (contentArea) {
        contentArea.style.transition = 'opacity 0.15s ease';
    }
}

// Initialize when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
} else {
    init();
}

// Utility function for showing notifications
function showNotification(message, type = 'success') {
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.innerHTML = `
        <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
        <span>${message}</span>
    `;
    
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: ${type === 'success' ? '#4caf50' : '#f44336'};
        color: white;
        padding: 16px 24px;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        z-index: 10000;
        display: flex;
        align-items: center;
        gap: 12px;
        animation: slideInRight 0.3s ease-out;
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.animation = 'slideOutRight 0.3s ease-out';
        setTimeout(() => {
            document.body.removeChild(notification);
        }, 300);
    }, 3000);
}

// Add CSS animations
const style = document.createElement('style');
style.textContent = `
    @keyframes slideInRight {
        from {
            opacity: 0;
            transform: translateX(100px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
    
    @keyframes slideOutRight {
        from {
            opacity: 1;
            transform: translateX(0);
        }
        to {
            opacity: 0;
            transform: translateX(100px);
        }
    }
    
    .error-message {
        text-align: center;
        padding: 60px 20px;
        color: #666;
    }
    
    .error-message i {
        font-size: 48px;
        color: #f44336;
        margin-bottom: 20px;
    }
    
    .error-message h3 {
        font-size: 20px;
        margin-bottom: 10px;
        color: #333;
    }
    
    .error-message p {
        font-size: 14px;
        color: #999;
    }
`;
document.head.appendChild(style);

// Export utilities
window.accountUtils = {
    loadPage,
    showNotification
};