// ==================================== fetch details provider ======================================

document.addEventListener("DOMContentLoaded", async () => { 
  const API_BASE = "http://localhost/tradeproxy/wordpress-6.8.3-vi/wordpress/wp-json/wp/v2";

  function getSlugFromPath() {
    const parts = location.pathname.split('/').filter(Boolean); 
    const slug = parts[parts.length - 1] || null;
    return slug;
  }

  const slug = getSlugFromPath(); 

  /* ================fetch provider details================== */
  const container = document.querySelector(".fetch-data");

  container.innerHTML = '<p style="text-align:center;padding:40px;"><i class="fas fa-spinner fa-spin"></i> Đang tải nhà cung cấp...</p>';

  try {
    const response = await fetch(`${API_BASE}/provider?slug=${encodeURIComponent(slug)}`);
    
    const posts = await response.json();

    const post = posts[0];

    // Lấy dữ liệu
    const title = post.title?.rendered || "No title";
    const postData = post.provider_data || {};
    
    const tags = Array.isArray(postData.tags) ? postData.tags : [];
    const content = postData.content || "Không có nội dung";
    const logo = postData.logo || "https://via.placeholder.com/200x200";
    const features_overview = postData.features_overview || [];

    // ========== XỬ LÝ FEATURES OVERVIEW ==========
    let featuresHTML = '';
    
    if (Array.isArray(features_overview) && features_overview.length > 0) {
      featuresHTML = features_overview.map(section => {
        const sectionTitle = section.title || "Không có tiêu đề";
        const items = section.items || [];
        
        // Render từng item trong section
        const itemsHTML = items.map(item => {
          const itemTitle = item.title || "No item";
          return `
            <div class="service-item">
              <i class="fas fa-check"></i>
              ${itemTitle}
            </div>
          `;
        }).join('');
        
        return `
          <div class="sidebar-section">
            <h3 class="section-title">${sectionTitle}</h3>
            ${itemsHTML}
          </div>
        `;
      }).join('');
    } else {
      console.log("Không có features_overview");
    }

    // Format content
    const formattedContent = content
      .split('\r\n\r\n')
      .map(para => para.trim())
      .filter(para => para)
      .map(para => `<p>${para.replace(/\r\n/g, '<br>')}</p>`)
      .join('');

    // Render HTML
    const articleHTML = `
      <h1 class="page-title">${title}</h1>

      <!-- Main Grid Layout -->
      <div class="detail-grid">
        <!-- Left Content -->
        <div class="left-content">
          ${tags.length > 0 ? `
            <div class="article-tags" style="margin: 20px 0;">
              ${tags.map(tag => `<span class="tag tag-label">${tag}</span>`).join('')}
            </div>
          ` : ''}

          <div class="action-buttons">
            <a href="${postData.website || '#'}" class="btn-detail-provider btn-primary-detail-provider" target="_blank">
              <i class="fas fa-plus"></i> Tìm hiểu thêm
            </a>
            <a href="${postData.contact_link || '#'}" class="btn-detail-provider btn-secondary-detail-provider" target="_blank">
              <i class="fas fa-link"></i> Liên hệ chúng tôi
            </a>
          </div>

          <div class="description">
            ${formattedContent}
          </div>

          ${postData.website ? `
            <div class="link-box">
              <p>Link:</p>
              <a href="${postData.website}" target="_blank">${postData.website}</a>
            </div>
          ` : ''}
        </div>

        <!-- Right Sidebar -->
        <div class="right-sidebar">
          <!-- Provider Card -->
          <div class="sidebar-card">
            <div class="provider-logo-section">
              <div class="logo-icon">
                <img src="${logo}" alt="${title}">
              </div>
              <div class="provider-name">${title}</div>
            </div>

            ${featuresHTML}
          </div>
        </div>
      </div>
    `;
    
    container.innerHTML = articleHTML;
    console.log("Render thành công!");

  } catch (error) {
    console.error("Lỗi khi fetch:", error);
  }
});