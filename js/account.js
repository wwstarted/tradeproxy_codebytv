// ===== account.js =====

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

// === call content ===
const PAGE_INIT = {
    profile: () => {
        if (typeof window.initProfilePage === 'function') {
            window.initProfilePage();
        }
    },
    'change-password': () => {
        if (typeof window.initChangePasswordPage === 'function') {
            window.initChangePasswordPage();
        }
    },
    wallet: () => {
        if (typeof window.initWalletPage === 'function') {
            window.initWalletPage();
        }
    },
    membership: () => {
        if (typeof window.initMembershipPage === 'function') {
            window.initMembershipPage();
        }
    },
    'deposit-history': () => {
        if (typeof window.initDepositHistoryPage === 'function') {
            window.initDepositHistoryPage();
        }
    },
    'purchase-history': () => {
        if (typeof window.initPurchaseHistoryPage === 'function') {
            window.initPurchaseHistoryPage();
        }
    }
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
        
        // Fetch page content via AJAX
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
        
        // Update content with smooth fade
        contentArea.style.opacity = '0';
        
        setTimeout(() => {
            contentArea.innerHTML = html;
            
            // === TRONG NỘI DUNG ĐÃ LOAD ===
            const scripts = contentArea.querySelectorAll('script');
            scripts.forEach(oldScript => {
                const newScript = document.createElement('script');
                Array.from(oldScript.attributes).forEach(attr => {
                    newScript.setAttribute(attr.name, attr.value);
                });
                newScript.textContent = oldScript.textContent;
                oldScript.parentNode.replaceChild(newScript, oldScript);
            });

            // === GỌI HÀM KHỞI TẠO THEO TRANG (SAU KHI DOM ĐÃ CẬP NHẬT) ===
            setTimeout(() => {
                PAGE_INIT[pageName]?.();
            }, 10);

            contentArea.style.opacity = '1';
        }, 100);
        
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
    document.querySelectorAll('.menu-item').forEach(item => {
        item.classList.remove('active');
    });
    
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
    
    // Cập nhật URL sạch (không có #)
    const newUrl = `${baseURL}/${pageName}`;
    history.pushState({ page: pageName }, '', newUrl);
    
    // Load nội dung
    loadPage(pageName);
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
    
    if (pages[lastSegment]) {
        return lastSegment;
    }
    
    if (lastSegment === 'account' || !lastSegment) {
        return 'profile';
    }
    
    return 'profile';
}

// Initialize page
function init() {
    console.log('Account page initialized');
    console.log('Base URL:', baseURL);
    console.log('AJAX URL:', ajaxUrl);
    
    // Gắn sự kiện cho menu
    document.querySelectorAll('.menu-item').forEach(item => {
        item.addEventListener('click', handleMenuClick);
    });
    
    // Lấy trang ban đầu
    const initialPage = getPageFromURL();
    const initialUrl = `${baseURL}/${initialPage}`;
    history.replaceState({ page: initialPage }, '', initialUrl);
    
    // Load trang đầu
    loadPage(initialPage);
    updateActiveMenu(initialPage);
    
    // Thêm hiệu ứng fade
    const contentArea = document.getElementById('page-content');
    if (contentArea) {
        contentArea.style.transition = 'opacity 0.15s ease';
    }
}

// Chạy khi DOM sẵn sàng
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
} else {
    init();
}

// === NOTIFICATION ===
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
        font-size: 14px;
        font-weight: 500;
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.animation = 'slideOutRight 0.3s ease-out';
        setTimeout(() => {
            if (notification.parentNode) {
                document.body.removeChild(notification);
            }
        }, 300);
    }, 3000);
}

// === CSS ANIMATIONS ===
const style = document.createElement('style');
style.textContent = `
    @keyframes slideInRight {
        from { opacity: 0; transform: translateX(100px); }
        to { opacity: 1; transform: translateX(0); }
    }
    @keyframes slideOutRight {
        from { opacity: 1; transform: translateX(0); }
        to { opacity: 0; transform: translateX(100px); }
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