// Get WordPress base URL from PHP (passed via wp_localize_script)
const wpData = window.wpAccountData || {};
const baseURL = wpData.baseUrl || window.location.origin;
const accountPageUrl = wpData.accountUrl || `${baseURL}/account`;

// Page configuration
const pages = {
    'profile': `http://localhost/tradeproxy/wordpress-6.8.3-vi/wordpress/profile`,
    'change-password': `http://localhost/tradeproxy/wordpress-6.8.3-vi/wordpress/change-password`,
    'wallet': `http://localhost/tradeproxy/wordpress-6.8.3-vi/wordpress/wallet`,
    'membership': `http://localhost/tradeproxy/wordpress-6.8.3-vi/wordpress/membership`,
    'deposit-history': `http://localhost/tradeproxy/wordpress-6.8.3-vi/wordpress/deposit-history`,
    'purchase-history': `http://localhost/tradeproxy/wordpress-6.8.3-vi/wordpress/purchase-history`
};

// Load page content
async function loadPage(pageName) {
    const contentArea = document.getElementById('page-content');
    const pageUrl = pages[pageName];
    
    if (!pageUrl) {
        contentArea.innerHTML = '<div class="error">Trang không tồn tại</div>';
        return;
    }
    
    try {
        // Show loading
        contentArea.innerHTML = `
            <div class="loading">
                <i class="fas fa-spinner fa-spin"></i>
                <p>Đang tải...</p>
            </div>
        `;

        
        // Fetch page content
        const response = await fetch(pageUrl);
        
        if (!response.ok) {
            throw new Error('Failed to load page');
        }
        
        const html = await response.text();
        
        // Update content with animation
        setTimeout(() => {
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
                
                contentArea.style.opacity = '1';
            }, 150);
        }, 100);
        
    } catch (error) {
        console.error('Error loading page:', error);
        contentArea.innerHTML = `
            <div class="error-message">
                <i class="fas fa-exclamation-circle"></i>
                <h3>Không thể tải trang</h3>
                <p>Vui lòng thử lại sau.</p>
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
    
    // Update URL without reload - FIX: Sử dụng đường dẫn tương đối từ current page
    const newUrl = `${accountPageUrl}#${pageName}`;
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

// Get page name from current URL (hash-based routing)
function getPageFromURL() {
    // Try to get from hash first
    const hash = window.location.hash.replace('#', '');
    if (hash && pages[hash]) {
        return hash;
    }
    
    // Fallback: try to get from pathname
    const path = window.location.pathname;
    const segments = path.split('/').filter(Boolean);
    const lastSegment = segments[segments.length - 1];
    
    // If last segment matches a page name, use it
    if (pages[lastSegment]) {
        return lastSegment;
    }
    
    // Default to profile
    return 'profile';
}

// Initialize page
function init() {
    // Add click handlers to menu items
    document.querySelectorAll('.menu-item').forEach(item => {
        item.addEventListener('click', handleMenuClick);
    });
    
    // Get initial page from URL or default to profile
    const initialPage = getPageFromURL();
    
    // Set initial state - FIX: Không thay đổi URL nếu đã đúng
    const currentUrl = `${accountPageUrl}#${initialPage}`;
    history.replaceState({ page: initialPage }, '', currentUrl);
    
    // Load initial page
    loadPage(initialPage);
    updateActiveMenu(initialPage);
    
    // Add smooth transition
    const contentArea = document.getElementById('page-content');
    if (contentArea) {
        contentArea.style.transition = 'opacity 0.3s ease';
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
    
    // Add styles
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
    
    // Auto remove after 3 seconds
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
    
    .loading {
        text-align: center;
        padding: 60px 20px;
        color: #999;
    }
    
    .loading i {
        font-size: 48px;
        margin-bottom: 20px;
    }
`;
document.head.appendChild(style);

// Export utilities
window.accountUtils = {
    loadPage,
    showNotification
};