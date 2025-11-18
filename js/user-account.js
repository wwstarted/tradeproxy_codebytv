// ===== account.js =====
document.addEventListener("DOMContentLoaded", function () {
  // =======================
  // WordPress data từ PHP
  // =======================
  const wpData = window.wpAccountData || {};
  const baseURL = wpData.baseUrl || window.location.origin;
  const ajaxUrl = wpData.ajaxUrl || `${baseURL}/wp-admin/admin-ajax.php`;
  const nonce = wpData.nonce || '';

  // =======================
  // Page slugs configuration
  // =======================
  const pages = {
    profile: 'profile',
    'change-password': 'change-password',
    wallet: 'wallet',
    membership: 'membership',
    'deposit-history': 'deposit-history',
    'purchase-history': 'purchase-history'
  };

  const accountPages = Object.keys(pages);

  // =======================
  // Call page init functions
  // =======================
  const PAGE_INIT = {
    profile: () => typeof window.initProfilePage === 'function' && window.initProfilePage(),
    'change-password': () => typeof window.initChangePasswordPage === 'function' && window.initChangePasswordPage(),
    wallet: () => typeof window.initWalletPage === 'function' && window.initWalletPage(),
    membership: () => typeof window.initMembershipPage === 'function' && window.initMembershipPage(),
    'deposit-history': () => typeof window.initDepositHistoryPage === 'function' && window.initDepositHistoryPage(),
    'purchase-history': () => typeof window.initPurchaseHistoryPage === 'function' && window.initPurchaseHistoryPage()
  };

  // =======================
  // Load page content via AJAX
  // =======================
  async function loadPage(pageName) {
    const contentArea = document.getElementById('page-content');
    if (!contentArea) return;

    if (!pageName || !accountPages.includes(pageName)) {
      contentArea.innerHTML = '<div>Trang không tồn tại hoặc không phải account</div>';
      return;
    }

    contentArea.style.opacity = '0.3';
    contentArea.innerHTML = '<p style="text-align:center;padding:30px;">Đang tải...</p>';

    try {
      const formData = new FormData();
      formData.append('action', 'load_account_page');
      formData.append('page_slug', pages[pageName]);
      formData.append('nonce', nonce);

      const response = await fetch(ajaxUrl, { method: 'POST', body: formData });
      if (!response.ok) throw new Error('Network response was not ok');

      const data = await response.json();
      if (!data.success) throw new Error(data.data || 'Failed to load content');

      contentArea.innerHTML = data.data;

      // Thực thi tất cả script trong nội dung load
      const scripts = contentArea.querySelectorAll('script');
      scripts.forEach(oldScript => {
        const newScript = document.createElement('script');
        if (oldScript.src) newScript.src = oldScript.src;
        Array.from(oldScript.attributes).forEach(attr => {
          if (attr.name !== 'src') newScript.setAttribute(attr.name, attr.value);
        });
        newScript.textContent = oldScript.textContent;
        oldScript.parentNode.replaceChild(newScript, oldScript);
      });

      // Init page
      setTimeout(() => PAGE_INIT[pageName]?.(), 10);
      contentArea.style.opacity = '1';
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

  // =======================
  // Update active menu
  // =======================
  function updateActiveMenu(pageName) {
    document.querySelectorAll('.menu-item').forEach(item => item.classList.remove('active'));
    const currentItem = document.querySelector(`[data-page="${pageName}"]`);
    if (currentItem) currentItem.classList.add('active');
  }

  // =======================
  // Handle menu click
  // =======================
 function handleMenuClick(e) {
    e.preventDefault();

    const menuItem = e.currentTarget;
    const pageName = menuItem.dataset.page;
    if (!pageName) return;

    // Tạo URL chuẩn: /profile, /change-password, /wallet ...
    const newUrl = `${baseURL}/${pageName}`;
    history.pushState({ page: pageName }, '', newUrl);

    // Load nội dung page và cập nhật menu active
    loadPage(pageName);
    updateActiveMenu(pageName);
}


  // =======================
  // Browser back/forward
  // =======================
  window.addEventListener('popstate', e => {
    const pageName = e.state?.page || getPageFromURL();
    if (pageName) {
      loadPage(pageName);
      updateActiveMenu(pageName);
    }
  });

  // =======================
  // Get page from URL
  // =======================
  function getPageFromURL() {
    const path = window.location.pathname.replace(/\/$/, '');
    const segments = path.split('/').filter(Boolean);
    const lastSegment = segments[segments.length - 1];
    if (accountPages.includes(lastSegment)) return lastSegment;
    return null; // Không phải account SPA
  }

  // =======================
  // Initialize SPA
  // =======================
function init() {
  console.log('Account page SPA initialized');
  console.log('Base URL:', baseURL);
  console.log('AJAX URL:', ajaxUrl);

  // Gắn sự kiện menu
  document.querySelectorAll('.menu-item').forEach(item => item.addEventListener('click', handleMenuClick));

  // Lấy page ban đầu từ URL
  let initialPage = getPageFromURL();

  // Nếu vào /account hoặc /account/ mà không có page con, mặc định là profile
  if (!initialPage) {
    initialPage = 'profile';
  }

  // Chỉ thay đổi URL nếu đang trong /account
  if (window.location.pathname.includes('/account')) {
    history.replaceState({ page: initialPage }, '', `${baseURL}/${initialPage}`);
  }

  // Load page đầu tiên
  loadPage(initialPage);
  updateActiveMenu(initialPage);

  // Thêm hiệu ứng fade cho content
  const contentArea = document.getElementById('page-content');
  if (contentArea) contentArea.style.transition = 'opacity 0.15s ease';
}



  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }

  // =======================
  // Notification
  // =======================
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
      setTimeout(() => notification.remove(), 300);
    }, 3000);
  }

  // =======================
  // CSS animations
  // =======================
  const style = document.createElement('style');
  style.textContent = `
    @keyframes slideInRight { from {opacity:0; transform:translateX(100px);} to {opacity:1; transform:translateX(0);} }
    @keyframes slideOutRight { from {opacity:1; transform:translateX(0);} to {opacity:0; transform:translateX(100px);} }
    .error-message { text-align:center; padding:60px 20px; color:#666; }
    .error-message i { font-size:48px; color:#f44336; margin-bottom:20px; }
    .error-message h3 { font-size:20px; margin-bottom:10px; color:#333; }
    .error-message p { font-size:14px; color:#999; }
  `;
  document.head.appendChild(style);

  // =======================
  // Export
  // =======================
  window.accountUtils = { loadPage, showNotification };
});
